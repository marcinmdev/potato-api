<?php

namespace App\Tests\Controller;

use App\Story\UserAccountStory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Throwable;

/**
 * @covers \App\Controller\V1\MealController
 */
class MealControllerTest extends WebTestCase
{
    /**
     * @covers \App\Controller\V1\MealController::index()
     */
    public function testIndex(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/v1/meal/');
        $this->assertResponseIsSuccessful();

        try {
            /** @var array{array{id: int, name: string, ingredients: array{array{id: int, name: string, price: int, weight: int}}}} $responseContent */
            $responseContent = json_decode((string) $client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $this->assertNotEmpty($responseContent[0]['id']);
            $this->assertNotEmpty($responseContent[0]['name']);
            $this->assertNotEmpty($responseContent[0]['ingredients']);
            $this->assertNotEmpty($responseContent[0]['ingredients'][0]['name']);
            $this->assertNotEmpty($responseContent[0]['ingredients'][0]['price']);
            $this->assertNotEmpty($responseContent[0]['ingredients'][0]['weight']);
        } catch (Throwable $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @covers \App\Controller\V1\MealController::get()
     */
    public function testGet(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/v1/meal/1');

        try {
            /** @var array{id: int, name: string, price: int, weight: int, ingredients: array{array{id: int, name: string, price: int, weight: int}}} $responseContent */
            $responseContent = json_decode((string) $client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $this->assertNotEmpty($responseContent['id']);
            $this->assertNotEmpty($responseContent['name']);
            $this->assertNotEmpty($responseContent['ingredients']);
            $this->assertNotEmpty($responseContent['ingredients'][0]['name']);
            $this->assertNotEmpty($responseContent['ingredients'][0]['price']);
            $this->assertNotEmpty($responseContent['ingredients'][0]['weight']);
        } catch (Throwable $e) {
            $this->fail($e->getMessage());
        }

        $this->assertResponseIsSuccessful();
    }

    /**
     * @covers \App\Controller\V1\MealController::create()
     */
    public function testCreate(): void
    {
        $client = $this->createAuthenticatedClient();

        try {
            $client->request(
                method: 'POST',
                uri: '/v1/meal/',
                content: json_encode([
                    'name' => 'Mashed potatoes',
                    'ingredients' => [
                        'id' => 1,
                    ],
                ], JSON_THROW_ON_ERROR)
            );

            /** @var array{id: int, name: string, price: int, weight: int, ingredients: array{array{id: int, name: string, price: int, weight: int}}} $responseContent */
            $responseContent = json_decode((string) $client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $this->assertNotEmpty($responseContent['id']);
            $this->assertNotEmpty($responseContent['name']);
            $this->assertNotEmpty($responseContent['ingredients']);
            $this->assertNotEmpty($responseContent['ingredients'][0]['name']);
            $this->assertNotEmpty($responseContent['ingredients'][0]['price']);
            $this->assertNotEmpty($responseContent['ingredients'][0]['weight']);
        } catch (Throwable $e) {
            $this->fail($e->getMessage());
        }

        $this->assertResponseIsSuccessful();
    }

    /**
     * @covers \App\Controller\V1\MealController::delete()
     */
    public function testDelete(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('DELETE', '/v1/meal/1');
        $this->assertResponseIsSuccessful();
    }

    private function createAuthenticatedClient(): KernelBrowser
    {
        return static::createClient(server: ['HTTP_X-AUTH-TOKEN' => UserAccountStory::API_TOKEN]);
    }
}
