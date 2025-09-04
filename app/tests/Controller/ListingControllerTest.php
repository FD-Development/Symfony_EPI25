<?php

/**
 * Listing controller Tests.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @class ListingControllerTest
 */
class ListingControllerTest extends WebTestCase
{
    /**
     * Test '/' route.
     */
    public function testListingPage(): void
    {
        // given
        $client = static::createClient();
        // when
        $client->request('GET', '/');
        // than
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
