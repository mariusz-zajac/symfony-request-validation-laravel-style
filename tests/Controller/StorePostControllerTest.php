<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StorePostControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
        ]);
    }

    public function testStorePostWithValidData(): void
    {
        $data = [
            'title' => 'Test title',
            'body' => 'Test body',
        ];

        $this->client->request('POST', '/post', [], [], [], json_encode($data));

        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testCannotStorePostWithInvalidData(): void
    {
        $data = [
            'title' => 'A',
        ];

        $this->client->request('POST', '/post', [], [], [], json_encode($data));

        $this->assertResponseStatusCodeSame(400);
        $this->assertJson($this->client->getResponse()->getContent());
        $result = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('title', $result);
        $this->assertSame('The given data was invalid', $result['title']);
        $this->assertArrayHasKey('violations', $result);
        $this->assertCount(2, $result['violations']);
    }
}
