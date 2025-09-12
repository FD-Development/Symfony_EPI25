<?php

/**
 * Listing controller Tests.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @class CategoryControllerTest
 */
class CategoryControllerTest extends WebTestCase
{
    /**
     * Tests route if '/category'  exists.
     */
    public function testCategoryPage(): void
    {
        // given
        $client = $this->createAdminClient();
        // when
        $client->request('GET', '/category');
        // then
        $this->assertResponseIsSuccessful();
    }

    /**
     * Tests if route '/category/create'  exists.
     */
    public function testCategoryCreate(): void
    {
        // given
        $client = $this->createAdminClient();
        // when
        $client->request('GET', '/category/create');
        // then
        $this->assertResponseIsSuccessful();
    }

    /**
     * Tests if route `/category/update/[id]` exists.
     */
    public function testCategoryUpdate(): void
    {
        // assuming Category with id 1 exists
        // given
        $client = $this->createAdminClient();

        // when
        $client->request('GET', '/category/update/1');

        // then
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test if route '/category/delete[id]' exists.
     */
    public function testCategoryDelete(): void
    {
        // assuming Category with id 1 exists

        // given
        $client = $this->createAdminClient();
        // when
        $client->request('GET', '/category/delete/1');
        // then
        $this->assertResponseIsSuccessful();
    }

    /**
     * Create admin client.
     *
     * @return KernelBrowser Kernel Browser
     */
    private function createAdminClient(): KernelBrowser
    {
        $client = static::createClient();

        $user = self::getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'admin_0']);

        $client->loginUser($user);

        return $client;
    }
}
