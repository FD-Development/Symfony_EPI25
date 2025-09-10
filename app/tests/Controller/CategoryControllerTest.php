<?php

/**
 * Listing controller Tests.
 */

namespace App\Tests\Controller;

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
        $client = static::createClient();
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
        $client = static::createClient();
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
        $client = static::createClient();

        // when
        $client->request('GET', '/category/update/1');

        // then
        $this->assertResponseIsSuccessful();
    }
}
