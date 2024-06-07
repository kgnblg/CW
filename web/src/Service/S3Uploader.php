<?php

namespace App\Service;

use App\Exception\ApiException;
use Aws\S3\S3Client;
use Aws\S3\Exception\AwsException;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class S3Uploader extends AbstractUploader
{
    private string $bucket;
    private S3Client $client;

    public function __construct(string $awsBucket, string $awsRegion, string $awsKey, string $awsSecret)
    {
        $this->bucket = $awsBucket;
        $this->client = new S3Client([
            'scheme'  => 'http',
            'version' => 'latest',
            'region'  => $awsRegion,
            'credentials' => [
                'key'    => $awsKey,
                'secret' => $awsSecret
            ]
        ]);
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
        $fileName = $this->getFileName($file);

        try {
            // put it to the S3
            $result = $this->client->putObject([
                'ACL' => 'public-read',
                'Bucket' => $this->bucket,
                'SourceFile' => $file->getRealPath(),
                'Key' => 'kbalga/' . $fileName
            ]);

            if ($returnType === 'url') {
                return $result->get('ObjectURL');
            }

            return json_encode((array) $result);
        } catch (AwsException $e) {
            error_log($e);
            throw new ApiException(
                'Error occured while uploading the file.',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

}