<?php

namespace App\Tests\Service;

use App\Service\ListingServiceInterface;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @class ListingServiceTest.
 */class ListingServiceTest extends WebTestCase
{
    /**
     * Tests the filtering listings by category.     */
    public function testListingFilteredByCategory(): void
    {
        $container = static::getContainer();

        $listingService = $container->get(ListingServiceInterface::class);
        $categoryRepository = $container->get(CategoryRepository::class);

        $category = $categoryRepository->findOneBy(['title' => 'Test 1']);
        $this->assertNotNull($category, 'Kategoria Test 1 musi istnieć. Czy Fixtures zostały załadowane do testowej bazy danych?');

        // Act
        $pagination = $listingService->getPaginatedListings(1, $category->getId());

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
}
