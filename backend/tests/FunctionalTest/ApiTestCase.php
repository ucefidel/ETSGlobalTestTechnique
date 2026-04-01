<?php

namespace FunctionalTest;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;

class ApiTestCase extends WebTestCase
{

    public KernelBrowser $client;

    public DocumentManager $documentManager;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->documentManager = static::getContainer()->get('doctrine_mongodb.odm.document_manager');
    }

    public function createRequest(
        string $method,
        string $uri, array $content = [],
        array $parameters = [],
        array $server = [], array $files = []): Crawler
    {
        return $this->client->request(
            $method, $uri,
            $parameters, $files,
            array_merge($server,['CONTENT_TYPE' => 'application/json']),
            json_encode($content)
        );
    }
}
