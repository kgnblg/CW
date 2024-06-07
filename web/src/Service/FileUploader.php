<?php

namespace App\Service;

use App\Exception\ApiException;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;

class FileUploader extends AbstractUploader
{
    private string $uploadPath;
    private string $publicPath;
    private UrlHelper $urlHelper;

    public function __construct($uploadPath, $publicPath, UrlHelper $urlHelper)
    {
        $this->uploadPath = $uploadPath;
        $this->publicPath = $publicPath;
        $this->urlHelper  = $urlHelper;
    }

    /**
     * @param UploadFile $file
     * @param File|null $fileConstraints if provided, it makes a validation before uploading the file
     * @param $returnType this can be "url" or "name" of the file (default is "url" because it's required in the test case)
     * @return string
     */
    public function upload(UploadedFile $file, ?File $fileConstraints = null, $returnType = 'url'): string
    {
        // validate file
        if ($fileConstraints) {
            $this->validate($file, $fileConstraints);
        }

        // create file name
        do {
            $fileName = $this->getFileName($file);
        }
        while (file_exists($this->getUploadPath() . $fileName));

        // upload the file
        try {
            $file->move($this->getUploadPath(), $fileName);
        } catch (FileException $e) {
            error_log($e);
            throw new ApiException('Files could not be uploaded.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($returnType === 'url') {
            return $this->urlHelper->getAbsoluteUrl(
                str_replace($this->getPublicPath(), '', $this->getUploadPath()) . '/' . $fileName
            );
        }

        return $fileName;
    }

    public function getPublicPath()
    {
        return $this->publicPath;
    }

    public function getUploadPath()
    {
        return $this->uploadPath;
    }
}