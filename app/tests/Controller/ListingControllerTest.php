<?php

/**
 * Listing controller Tests.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Listing;
use App\Entity\Category;
use App\Service\ListingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @class ListingControllerTest
 */
class ListingControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;

    private Listing $testListing;
    private Category $testCategory;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        $user = new User();
        $user->setUsername('admin_test');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('password');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->client->loginUser($user);

        $data = $this->createTestData();
        $this->testListing = $data[0];
        $this->testCategory = $data[1];

    }

    /**
     * Test '/' route.
     */
    public function testListingIndexPage(): void
    {
        // when
        $this->client->request('GET', '/');
        // then
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Tests if user is informed if there are no listings shown.
     */
    public function testListingInPageShowsEmptyMessageWhenNoListings(): void
    {

        /* Simulation of empty pagination */
        $paginator = static::getContainer()->get('knp_paginator');
        $pagination = $paginator->paginate([], 1, 10);

        /* Service mock */
        $serviceMock = $this->createMock(ListingService::class);
        $serviceMock->method('getActivatedPaginatedListings')->willReturn($pagination);


        static::getContainer()->set(ListingService::class, $serviceMock);

        // when
        $this->client->request('GET', '/');
        // then
        $this->assertSelectorTextContains('p', 'message.empty_list');
    }

    /**
     * Tests if route `/listing/[id]` exists.
     */
    public function testListingViewPage(): void
    {
        // given
        $listing = $this->testListing;

        // when
        $this->client->request('GET', '/listing/'.$listing->getId());
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

    /**
     * Test if route '/listing/update[id]' exists.
     */
    public function testListingUpdate(): void
    {

        // given
        $listing = $this->testListing;

        // when
        $this->client->request('GET', '/listing/update/'.$listing->getId());
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

    /**
     * Test if route '/listing/delete[id]' exists.
     */
    public function testListingDelete(): void
    {
        // given
        $listing = $this->testListing;

        // when
        $this->client->request('GET', '/listing/delete/'.$listing->getId());
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

    /**
     * Creates test data.
     *
     * @return array Test data array
     */
    private function createTestData(): array
    {
        $category = new Category();
        $category->setTitle('Test Category');
        $this->entityManager->persist($category);

        $listing = new Listing();
        $listing->setTitle('Test Listing');
        $listing->setDescription('Test description');
        $listing->setCreatedAt(new \DateTimeImmutable());
        $listing->setActivatedAt(null);
        $listing->setCategory($category);

        $this->entityManager->persist($listing);
        $this->entityManager->flush();

        return [$listing, $category];
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->entityManager) {
            $connection = $this->entityManager->getConnection();
            $platform   = $connection->getDatabasePlatform();


            $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($this->entityManager->getMetadataFactory()->getAllMetadata() as $metadata) {
                $tableName = $metadata->getTableName();
                $connection->executeStatement(
                    $platform->getTruncateTableSQL($tableName, true)
                );
            }

            $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');

            // zamknij EntityManager
            $this->entityManager->close();
        }

    }
}
