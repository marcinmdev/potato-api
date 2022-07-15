<?php

namespace App\Tests\Controller;

use App\Story\UserAccountStory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Throwable;

/**
 * @covers \App\Controller\V1\IngredientController
 */
class IngredientControllerTest extends WebTestCase
{
    /**
     * @covers \App\Controller\V1\IngredientController::index()
     */
    public function testIndex(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/v1/ingredient/');
        $this->assertResponseIsSuccessful();
    }

    /**
     * @covers \App\Controller\V1\IngredientController::get()
     */
    public function testGet(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/v1/ingredient/1');
        $this->assertResponseIsSuccessful();
    }

    /**
     * @covers \App\Controller\V1\IngredientController::create()
     */
    public function testCreate(): void
    {
        $client = $this->createAuthenticatedClient();

        try {
            $client->request(
                method: 'POST',
                uri: '/v1/ingredient/',
                content: json_encode([
                    'name' => 'Big Potato',
                    'weight' => 10,
                    'price' => 20,
                ], JSON_THROW_ON_ERROR)
            );
        } catch (Throwable $e) {
            $this->fail($e->getMessage());
        }

        $this->assertResponseIsSuccessful();
    }

    /**
     * @covers \App\Controller\V1\IngredientController::delete()
     */
    public function testDelete(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('DELETE', '/v1/ingredient/1');
        $this->assertResponseIsSuccessful();
    }

    private function createAuthenticatedClient(): KernelBrowser
    {
        return static::createClient(server: ['HTTP_X-AUTH-TOKEN' => UserAccountStory::API_TOKEN]);
    }
}
