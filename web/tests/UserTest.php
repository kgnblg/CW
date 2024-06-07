<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserTest extends WebTestCase
{
    public function testNonExistingRoute()
    {
        $client = static::createClient();

        $client->request('GET', '/api/some_non_existing_page');
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $jsonContent = $response->getContent();
        $this->assertJson($jsonContent);

        $jsonContent = json_decode($jsonContent);
        $this->assertEquals('error', $jsonContent->status);
        $this->assertEquals('Endpoint not valid.', $jsonContent->message);
    }

    public function testExistingRouteWithWrongMethod()
    {
        $client = static::createClient();

        $client->request('GET', '/api/users/register');
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $jsonContent = $response->getContent();
        $this->assertJson($jsonContent);

        $jsonContent = json_decode($jsonContent);
        $this->assertEquals('error', $jsonContent->status);
        $this->assertEquals('Endpoint not valid.', $jsonContent->message);
    }

    public function testRegisterLoginAndMeEndpoints()
    {
        $client = static::createClient();

        $container = self::$container;

        $testPath  = $container->getParameter('test_directory');
        $testImage = $container->getParameter('test_image');

        copy($testPath . '/' . $testImage, $testPath . '/upload_avatar.png');
        $avatar = new UploadedFile($testPath . '/upload_avatar.png', '/upload_avatar.png', 'image/png', null, true);

        copy($testPath . '/' . $testImage, $testPath . '/upload_photo_1.png');
        copy($testPath . '/' . $testImage, $testPath . '/upload_photo_2.png');
        copy($testPath . '/' . $testImage, $testPath . '/upload_photo_3.png');
        copy($testPath . '/' . $testImage, $testPath . '/upload_photo_4.png');
        $photos = [
            new UploadedFile($testPath . '/upload_photo_1.png', '/upload_photo_1.png', 'image/png', null, true),
            new UploadedFile($testPath . '/upload_photo_2.png', '/upload_photo_2.png', 'image/png', null, true),
            new UploadedFile($testPath . '/upload_photo_3.png', '/upload_photo_3.png', 'image/png', null, true),
            new UploadedFile($testPath . '/upload_photo_4.png', '/upload_photo_4.png', 'image/png', null, true),
        ];

        $client->request(
            'POST',
            '/api/users/register',
            [
                'firstname' => 'Kagann',
                'lastname'  => 'Balgaa',
                'email'     => 'kagan12345123@kagan.com',
                'password'  => 'Kagan123Bal',
            ],
            [
                'avatar' => $avatar,
                'photos' => $photos
            ]
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $jsonContent = $response->getContent();
        $this->assertJson($jsonContent);

        $jsonContent = json_decode($jsonContent);
        $this->assertEquals('successful', $jsonContent->status);

        $client->request(
            'POST',
            '/api/users/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'kagan12345123@kagan.com',
                'password' => 'Kagan123Bal',
            ])
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $jsonContent = $response->getContent();
        $this->assertJson($jsonContent);

        $jsonContent = json_decode($jsonContent, true);
        $this->assertArrayHasKey('token', $jsonContent);

        $client->request(
            'GET',
            '/api/users/me',
            [],
            [],
            [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_Authorization' => 'Bearer ' . $jsonContent['token']
            ],
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $jsonContent = $response->getContent();
        $this->assertJson($jsonContent);
    }
}