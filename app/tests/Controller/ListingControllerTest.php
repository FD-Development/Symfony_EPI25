<?php

/**
 * Listing controller Tests.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use App\Service\ListingService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @class ListingControllerTest
 */
class ListingControllerTest extends WebTestCase
{
    /**
     * Test '/' route.
     */
    public function testListingIndexPage(): void
    {
        // given
        $client = static::createClient();
        // when
        $client->request('GET', '/');
        // then
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests if user is informed if there are no listings shown.
     */
    public function testListingInPageShowsEmptyMessageWhenNoListings(): void
    {
        // given
        $client = static::createClient();

        /* Simulation of empty pagination */
        $paginator = static::getContainer()->get('knp_paginator');
        $pagination = $paginator->paginate([], 1, 10);

        /* Service mock */
        $serviceMock = $this->createMock(ListingService::class);
        $serviceMock->method('getActivatedPaginatedListings')->willReturn($pagination);


        static::getContainer()->set(ListingService::class, $serviceMock);

        // when
        $client->request('GET', '/');
        // then
        $this->assertSelectorTextContains('p', 'message.empty_list');
    }

    /**
     * Tests if route `/listing/[id]` exists.
     */
    public function testListingViewPage(): void
    {
        // assuming Listing with id 1 exists
        // given
        $client = static::createClient();

        // when
        $client->request('GET', '/listing/1');

        // then
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /*
    * TODO: Check if user has access to inactive listings => jeżeli ID istnieje, ale np. listing jest nieaktywny albo użytkownik nie ma do niego dostępu
    */

    /**
     * Test if route '/listing/update[id]' exists.
     */
    public function testListingUpdate(): void
    {
        // assuming Listing with id 1 exists

        // given
        $client = $this->createAdminClient();

        // when
        $client->request('GET', '/listing/update/1');

        // then
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    /*
     * TODO: Check if user has access
     */

    /**
     * Test if route '/listing/delete[id]' exists.
     */
    public function testListingDelete(): void
    {
        // assuming Listing with id 1 exists

        // given
        $client = $this->createAdminClient();
        // when
        $client->request('GET', '/listing/delete/1');
        // then
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    /*
     * TODO: Check if user has access
     */

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
