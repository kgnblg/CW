<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\FileUploader;
use App\Service\S3Uploader;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;

class UploadTest extends KernelTestCase
{
    public function testUploadFiletoPublic(): void
    {
        self::bootKernel();

        $container = self::$container;

        $testPath  = $container->getParameter('test_directory');
        $testImage = $container->getParameter('test_image');
        $uploadPath = $container->getParameter('upload_directory');
        $publicPath = $container->getParameter('public_directory');

        $fileUploader = new FileUploader(
            $uploadPath,
            $publicPath,
            new UrlHelper(new RequestStack)
        );

        copy($testPath . '/' . $testImage, $testPath . '/upload_public_test.png');
        $file = new UploadedFile($testPath . '/upload_public_test.png', 'upload_public_test.png', 'image/png', null, true);

        $fileName = $fileUploader->getFileName($file);

        $this->assertIsString($fileName);
        $this->assertStringContainsString('.png', $fileName);

        $uploadedFileName = $fileUploader->upload($file, null, 'file');

        $this->assertIsString($uploadedFileName);
        $this->assertFileExists($uploadPath . '/' . $uploadedFileName);

        unlink($uploadPath . '/' . $uploadedFileName);
        $this->assertFileNotExists($uploadPath . '/' . $uploadedFileName);
    }

    public function testUploadFiletoAWS(): void
    {
        self::bootKernel();

        $container = self::$container;

        $testPath  = $container->getParameter('test_directory');
        $testImage = $container->getParameter('test_image');

        $fileUploader = new S3Uploader(
            $container->getParameter('aws_s3_bucket'),
            $container->getParameter('aws_region'),
            $container->getParameter('aws_key'),
            $container->getParameter('aws_secret')
        );

        copy($testPath . '/' . $testImage, $testPath . '/upload_aws_test.png');
        $file = new UploadedFile($testPath . '/upload_aws_test.png', 'upload_aws_test.png', 'image/png', null, true);

        $fileName = $fileUploader->getFileName($file);

        $this->assertIsString($fileName);
        $this->assertStringContainsString('.png', $fileName);

        $uploadedFileUrl = $fileUploader->upload($file, null, 'url');

        $this->assertIsString($uploadedFileUrl);
        $this->assertNotFalse(filter_var($uploadedFileUrl, FILTER_VALIDATE_URL));

        unlink($testPath . '/upload_aws_test.png');
        $this->assertFileNotExists($testPath . '/upload_aws_test.png');
    }
}
