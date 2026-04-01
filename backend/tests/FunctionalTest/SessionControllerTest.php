<?php

namespace FunctionalTest;

use App\Document\Session;

class SessionControllerTest extends ApiTestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->documentManager->getSchemaManager()->dropDocumentCollection(Session::class);
    }

    public function testCreateSession(): void
    {
        $this->createRequest('POST', '/api/login', [
            'email' => 'test@example.com',
            'password' => '123456'
        ]);

        $token = json_decode($this->client->getResponse()->getContent(), true)['token'];

        $this->createRequest('POST', '/api/sessions',[
            'language' => 'Anglais',
            'dateAt' => '2024-02-01',
            'hourAt' => '09:00',
            'location' => 'Londres',
            'availableSeats' => 10
        ], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '. $token
        ]);

        self::assertResponseStatusCodeSame(201);

        $data = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('id', $data);
        self::assertEquals('Anglais', $data['language']);
        self::assertEquals('2024-02-01', $data['dateAt']);

    }
}
