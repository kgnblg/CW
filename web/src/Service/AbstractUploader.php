<?php

namespace App\Service;

use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validation;

abstract class AbstractUploader
{
    /**
     * @param UploadedFile $file
     * @return string
     */
    public function getFileName(UploadedFile $file): string
    {
        return substr(md5(time() . uniqid()), 0, 10) . '-' . uniqid() . '.' . $file->guessExtension();
    }

    /**
     * validate the provided file
     * 
     * @param UploadedFile $file
     * @param File $fileConstraints
     * @throws ApiException if file is not valid
     * @return void
     */
    public function validate(UploadedFile $file, File $fileConstraints): void
    {
        $validator = Validation::createValidator();
        $validatorErrors = $validator->validate($file, $fileConstraints);
        if (count($validatorErrors) > 0) {
            $validatorErrors = join(',', array_map(fn ($e) => $e->getMessage(), iterator_to_array($validatorErrors)));
            throw new ApiException('File is not valid; ' . $validatorErrors, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param UploadFile $file
     * @param File|null $fileConstraints if provided, it makes a validation before uploading the file
     * @param $returnType this can be "url" or "name" of the file (default is "url" because it's required in the test case)
     * @return string
     */
    public abstract function upload(UploadedFile $file, ?File $fileConstraints = null, $returnType = 'url'): string;
}