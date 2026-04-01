<?php

namespace FunctionalTest;

use App\Document\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends ApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->documentManager->getSchemaManager()->dropDocumentCollection(User::class);
    }

    public function testRegister(): void
    {
        $this->createRequest('POST', '/api/register', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
            'password' => '123456'
        ]);

        self::assertResponseStatusCodeSame(201);
    }

    public function testLogin(): void
    {
        $this->createRequest('POST', '/api/register', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
            'password' => '123456'
        ]);

        $this->createRequest('POST', '/api/login', [
            'email' => 'test@example.com',
            'password' => '123456'
        ]);

        self::assertResponseStatusCodeSame(200);

        $data = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('token', $data);
    }

    public function testMe(): void
    {
        $this->createRequest('POST', '/api/register', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
            'password' => '123456'
        ]);

        $this->createRequest('POST', '/api/login', [
            'email' => 'test@example.com',
            'password' => '123456'
        ]);

        $token = json_decode($this->client->getResponse()->getContent(), true)['token'];

        $this->createRequest('GET', '/api/users/me', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);

        self::assertResponseStatusCodeSame(200);

        $data = json_decode($this->client->getResponse()->getContent(), true);

        self::assertEquals('Test Name', $data['name']);
        self::assertEquals('test@example.com', $data['email']);
    }

}
