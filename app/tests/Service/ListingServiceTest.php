<?php

namespace App\Tests\Service;

use App\Entity\Listing;
use App\Entity\Category;
use App\Service\ListingServiceInterface;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @class ListingServiceTest.
 */
class ListingServiceTest extends WebTestCase
{
    /**
     * Tests the filtering by category of listings.
     */
    public function testListingsFilteredByCategory(): void
    {
        $container = static::getContainer();

        $listingService = $container->get(ListingServiceInterface::class);
        $categoryRepository = $container->get(CategoryRepository::class);

        $category = $categoryRepository->findOneBy(['title' => 'Test 1']);
        $this->assertNotNull($category, 'Kategoria Test 1 musi istnieć. Czy Fixtures zostały załadowane do testowej bazy danych?');

        // Act
        $pagination = $listingService->getActivatedPaginatedListings(1, $category->getId());

        // Assert
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
    }

    /**
     * Tests if Listing gets activated.
     */
    public function testIfListingGetsActivated(): void
    {
        // given
        self::bootKernel();
        $container = static::getContainer();
        $listingService = $container->get(ListingServiceInterface::class);

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
}
