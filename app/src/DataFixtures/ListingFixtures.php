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

        $this->createMany(100, 'listing', function (int $i) {
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
