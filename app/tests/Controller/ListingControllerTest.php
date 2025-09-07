<?php

/**
 * Listing controller Tests.
 */

namespace App\Tests\Controller;

use App\Service\ListingService;
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
        // then
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests if user is informed if there are no listings.
     */
    public function testListingPageShowsEmptyMessageWhenNoListings(): void
    {
        // given
        $client = static::createClient();
        $mockService = $this->createMock(ListingService::class);
        $mockService->method('getPaginatedListings')->willReturn([]);
        static::getContainer()->set(ListingService::class, $mockService);

        // when
        $client->request('GET', '/');
        // then
        $this->assertSelectorTextContains('p', 'message.empty_list');
    }

    /**
     * Tests the filtering listings by category.
     *
     * @return void
     */
    public function testListingFilteredByCategory(): void
    {
        //given
        $client = static::createClient();

        //when
        $crawler = $client->request('GET', '/?categoryId=1');

        //then
    }
}
