<?php

/**
 * Listing Fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Listing;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

/**
 * @class ListingFixtures.
 */
class ListingFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data fixtures.
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        // 5 listings for category Test 1
        for ($i = 0; $i < 5; ++$i) {
            $listing = new Listing();
            $listing->setTitle('Listing Test 1 - '.($i + 1));
            $listing->setDescription($this->faker->text());
            $listing->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-12 days', '-1 days')));
            $listing->setActivatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-12 days', '-1 days')));
            $listing->setCategory($this->getReference('category_Test1', Category::class));

            $this->manager->persist($listing);
        }

        // 5 listings for category Test 2
        for ($i = 0; $i < 5; ++$i) {
            $listing = new Listing();
            $listing->setTitle('Listing Test 2 - '.($i + 1));
            $listing->setDescription($this->faker->text());
            $listing->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-12 days', '-1 days')));
            $listing->setActivatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-12 days', '-1 days')));
            $listing->setCategory($this->getReference('category_Test2', Category::class));

            $this->manager->persist($listing);
        }

        $this->createMany(50, 'listing_active', function (int $i) {
            $listing = new Listing();
            $listing->setTitle($this->faker->words(3, true));
            $listing->setDescription($this->faker->text());
            $listing->setCreatedAt(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-12 days', '-1 days'))
            );
            $listing->setActivatedAt(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-12 days', '-1 days'))
            );
            $category = $this->getRandomReference('category', Category::class);
            $listing->setCategory($category);

            return $listing;
        });

        $this->createMany(50, 'listing_inactive', function (int $i) {
            $listing = new Listing();
            $listing->setTitle($this->faker->words(3, true));
            $listing->setDescription($this->faker->text());
            $listing->setCreatedAt(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-12 days', '-1 days'))
            );
            $listing->setActivatedAt(null);
            $category = $this->getRandomReference('category', Category::class);
            $listing->setCategory($category);

            return $listing;
        });
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @phpstan-return array<class-string<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
