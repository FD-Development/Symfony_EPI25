<?php

/**
 * Listing controller Tests.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Enum\UserRole;
use App\Entity\Listing;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ListingService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @class ListingControllerTest
 */
class ListingControllerTest extends WebTestCase
{
    /**
     * Listing | Category Repository.
     */
    private ?EntityManagerInterface $entityManager;
    /**
     * Test Client.
     */
    private KernelBrowser $client;
    /**
     * Listing Entity.
     */
    private ?Listing $testListing;

    /**
     * Category Entity.
     */
    private ?Category $testCategory;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        $data = $this->createTestData();
        $this->testListing = $data[0];
        $this->testCategory = $data[1];
    }

    /**
     * Test '/' route.
     */
    public function testIndexRouteAnonymousUser(): void
    {
        // given

        // when
        $this->client->request('GET', '/');
        // then
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Tests if user is informed if there are no listings shown.
     */
    public function testListingPageShowsEmptyMessageWhenNoListings(): void
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
     * Tests if route `/listing/create` exists.
     */
    public function testCreateRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 200;
        // when
        $this->client->request('GET', '/listing/create');
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Tests if route `/listing/[id]` exists.
     */
    public function testViewRouteAdminUser(): void
    {
        // given
        $listing = $this->testListing;
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->client->loginUser($adminUser);
        $expectedStatusCode = 200;

        // when
        $this->client->request('GET', '/listing/'.$listing->getId());
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test if route '/listing/update[id]' exists.
     */
    public function testUpdateRouteAdminUser(): void
    {
        // given
        $listing = $this->testListing;
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->client->loginUser($adminUser);
        $expectedStatusCode = 200;

        // when
        $this->client->request('GET', '/listing/update/'.$listing->getId());
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test if Non authorised user has access route '/listing/update[id]' exists.
     */
    public function testUpdateRouteNonAuthorizedUser(): void
    {
        // given
        $listing = $this->testListing;
        $user = $this->createUser([UserRole::ROLE_USER->value]);
        $this->client->loginUser($user);
        $expectedStatusCode = 403;

        // when
        $this->client->request('GET', '/listing/update/'.$listing->getId());
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test if route '/listing/delete[id]' exists.
     */
    public function testDeleteRouteAdminUser(): void
    {
        // given
        $listing = $this->testListing;
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->client->loginUser($adminUser);
        $expectedStatusCode = 200;

        // when
        $this->client->request('GET', '/listing/delete/'.$listing->getId());
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test if route '/listing/activate' exists.
     */
    public function testActivateIndexRouteAdminUser(): void
    {
        // given
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->client->loginUser($adminUser);
        $expectedStatusCode = 200;

        // when
        $this->client->request('GET', '/activate');
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test if route '/listing/activate/[id]' exists.
     */
    public function testActivateListingRouteAdminUser(): void
    {
        // given
        $listing = $this->testListing;
        $adminUser = $this->createUser([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
        $this->client->loginUser($adminUser);
        $expectedStatusCode = 200;

        // when
        $this->client->request('GET', '/listing/activate/'.$listing->getId());
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
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
     * Create user.
     *
     * @param array $roles User roles
     *
     * @return User User entity
     *
     * @throws ContainerExceptionInterface|NotFoundExceptionInterface|ORMException|OptimisticLockException
     */
    private function createUser(array $roles): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setusername('test_user');
        $user->setRoles($roles);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                'p@55w0rd'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->entityManager) {
            $connection = $this->entityManager->getConnection();
            $platform = $connection->getDatabasePlatform();

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
