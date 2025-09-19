<?php

/**
 * Listing Service Test.
 */

namespace App\Tests\Service;

use App\Entity\Listing;
use App\Entity\Category;
use App\Service\ListingServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ListingServiceTest.
 */
class ListingServiceTest extends KernelTestCase
{
    /**
     * Listing repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Listing service.
     */
    private ?ListingServiceInterface $listingService;

    /**
     * This method is called before each test.
     */
    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine')->getManager();
        $this->listingService = $container->get(ListingServiceInterface::class);
    }

    /**
     * Tests the filtering by category of listings.
     */
    public function testListingsFilteredByCategory(): void
    {
        // given
        self::bootKernel();
        $container = static::getContainer();
        $listingService = $this->listingService;
        $entityManager = $this->entityManager;

        // Category and Listing 1
        $category1 = new Category();
        $category1->setTitle('Test Category 1');
        $entityManager->persist($category1);

        for ($i = 0; $i < 5; ++$i) {
            $listingCategory1 = new Listing();
            $listingCategory1->setTitle('Listing Test 1 - '.($i + 1));
            $listingCategory1->setDescription('Test description');
            $listingCategory1->setCreatedAt(new \DateTimeImmutable());
            $listingCategory1->setActivatedAt(null);
            $listingCategory1->setCategory($category1);

            $entityManager->persist($listingCategory1);
        }

        // Category and Listing 2
        $category2 = new Category();
        $category2->setTitle('Test Category 2');
        $entityManager->persist($category2);

        $listingCategory2 = new Listing();
        $listingCategory2->setTitle('Listing different category');
        $listingCategory2->setDescription('Test description');
        $listingCategory2->setCreatedAt(new \DateTimeImmutable());
        $listingCategory2->setActivatedAt(null);
        $listingCategory2->setCategory($category2);

        $entityManager->persist($listingCategory2);

        $entityManager->flush();

        // when
        $pagination = $listingService->getNonActivatedPaginatedListings(1, $category1->getId());

        // then
        $titles = [];
        foreach ($pagination as $listing) {
            $titles[] = $listing->getTitle();
        }

        $expected = [
            'Listing Test 1 - 1',
            'Listing Test 1 - 2',
            'Listing Test 1 - 3',
            'Listing Test 1 - 4',
            'Listing Test 1 - 5',
        ];

        $this->assertSame($expected, $titles, 'Powinny zwrócić się tylko ogłoszenia z kategorii Test 1.');

        // cleanup
        $entityManager->remove($listingCategory2);
        $entityManager->remove($category1);
        $entityManager->remove($category2);
        $entityManager->flush();
    }

    /**
     * Tests the filtering by category of listings.
     */
    public function testActiveListingsFilteredByCategory(): void
    {
        // given
        self::bootKernel();
        $container = static::getContainer();
        $listingService = $this->listingService;
        $entityManager = $this->entityManager;

        // Category and Listing 1
        $category1 = new Category();
        $category1->setTitle('Test Category 1');
        $entityManager->persist($category1);

        for ($i = 0; $i < 5; ++$i) {
            $listingCategory1 = new Listing();
            $listingCategory1->setTitle('Listing Test 1 - '.($i + 1));
            $listingCategory1->setDescription('Test description');
            $listingCategory1->setCreatedAt(new \DateTimeImmutable());
            $listingCategory1->setActivatedAt(new \DateTimeImmutable());
            $listingCategory1->setCategory($category1);

            $entityManager->persist($listingCategory1);
        }

        // Category and Listing 2
        $category2 = new Category();
        $category2->setTitle('Test Category 2');
        $entityManager->persist($category2);

        $listingCategory2 = new Listing();
        $listingCategory2->setTitle('Listing different category');
        $listingCategory2->setDescription('Test description');
        $listingCategory2->setCreatedAt(new \DateTimeImmutable());
        $listingCategory2->setActivatedAt(null);
        $listingCategory2->setCategory($category2);

        $entityManager->persist($listingCategory2);

        $entityManager->flush();

        // when
        $pagination = $listingService->getActivatedPaginatedListings(1, $category1->getId());

        // then
        $titles = [];
        foreach ($pagination as $listing) {
            $titles[] = $listing->getTitle();
        }

        $expected = [
            'Listing Test 1 - 1',
            'Listing Test 1 - 2',
            'Listing Test 1 - 3',
            'Listing Test 1 - 4',
            'Listing Test 1 - 5',
        ];

        $this->assertSame($expected, $titles, 'Powinny zwrócić się tylko ogłoszenia z kategorii Test 1.');

        // cleanup
        $entityManager->remove($listingCategory2);
        $entityManager->remove($category1);
        $entityManager->remove($category2);
        $entityManager->flush();
    }

    /**
     * Tests if Listing gets activated.
     */
    public function testIfListingGetsActivated(): void
    {
        // given
        $listingService = $this->listingService;
        $category = new Category();
        $category->setTitle('Dummy Category');

        $listing = new Listing();
        $listing->setTitle('Test activation');
        $listing->setDescription('Test description');
        $listing->setCreatedAt(new \DateTimeImmutable());
        $listing->setActivatedAt(null);
        $listing->setCategory($category);


        // when
        $listingService->activate($listing);

        // then
        $this->assertNotNull($listing->getActivatedAt(), 'Listing powinien być aktywowany');
        $this->assertInstanceOf(\DateTimeImmutable::class, $listing->getActivatedAt());
    }

    /**
     * Tests if Listing gets saved.
     */
    public function testIfListingGetsSaved(): void
    {
        // given
        $listingService = $this->listingService;
        $category = new Category();
        $category->setTitle('Dummy Category');

        $expectedListing = new Listing();
        $expectedListing->setTitle('Test activation');
        $expectedListing->setDescription('Test description');
        $expectedListing->setCreatedAt(new \DateTimeImmutable());
        $expectedListing->setActivatedAt(null);
        $expectedListing->setCategory($category);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        // when
        $listingService->save($expectedListing);

        // then
        $expectedListingId = $expectedListing->getId();
        $resultListing = $this->entityManager->createQueryBuilder()
            ->select('listing')
            ->from(Listing::class, 'listing')
            ->where('listing.id = :id')
            ->setParameter(':id', $expectedListingId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertEquals($expectedListing, $resultListing);
    }

    /**
     * Tests if Listing gets deleted.
     */
    public function testIfListingGetsDeleted(): void
    {
        // given
        $listingService = $this->listingService;
        $category = new Category();
        $category->setTitle('Dummy Category');

        $givenListing = new Listing();
        $givenListing->setTitle('Test activation');
        $givenListing->setDescription('Test description');
        $givenListing->setCreatedAt(new \DateTimeImmutable());
        $givenListing->setActivatedAt(null);
        $givenListing->setCategory($category);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $listingService->save($givenListing);
        $expectedListing = null;
        // when

        $listingService->delete($givenListing);

        // then
        $givenListingId = $givenListing->getId();
        $resultListing = $this->entityManager->createQueryBuilder()
            ->select('listing')
            ->from(Listing::class, 'listing')
            ->where('listing.id = :id')
            ->setParameter(':id', $givenListingId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertEquals($expectedListing, $resultListing);
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
