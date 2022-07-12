<?php

namespace App\Tests\Controller;

use App\Story\UserAccountStory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

    private function createAuthenticatedClient(): KernelBrowser
    {
        return static::createClient(server: ['HTTP_X-AUTH-TOKEN' => UserAccountStory::API_TOKEN]);
    }
}
