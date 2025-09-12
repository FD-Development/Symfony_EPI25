<?php

/**
 * User Controller Test.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * @class UserControllerTest
 */
class UserControllerTest extends WebTestCase
{
    /**
     * Create Authenticated Client1.
     *
     * @return KernelBrowser Kernel browser
     */
    private function createAuthenticatedClient(): KernelBrowser
    {
        // Assuming users exsist
        $client = static::createClient();
        $user = self::getContainer()->get('doctrine')->getRepository(User::class)->findOneBy([]);

        $client->loginUser($user);

        return $client;
    }

    /**
     * Test '/user' route.
     */
    public function testUserViewPage(): void
    {
        // given
        $client = $this->createAuthenticatedClient();
        // when
        $client->request('GET', '/user');
        // then
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Test '/user/[id]/update' route.
     */
    public function testUserUpdatePage(): void
    {
        // given
        $client = $this->createAuthenticatedClient();
        // when
        $client->request('GET', '/user/1/update');
        // then
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Test '/user/[id]/password-update' route.
     */
    public function testPasswordUpdatePage(): void
    {
        // given
        $client = $this->createAuthenticatedClient();
        // when
        $client->request('GET', '/user/1/password-update');
        // then
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
