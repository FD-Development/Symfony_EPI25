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
        //given
        $client = static::createClient();
        //when
        $client->request('GET', '/category');
        //then
        $this->assertResponseIsSuccessful();
    }
}