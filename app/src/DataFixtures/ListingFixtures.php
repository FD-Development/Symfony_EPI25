<?php

/**
 * Listing Fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Listing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * @class ListingFixtures.
 */
class ListingFixtures extends Fixture
{
    /**
     * Faker.
     */
    protected Generator $faker;
    /**
     * Persistence object manager.
     */
    protected ObjectManager $manager;

    /**
     * Load data fixtures with the passed Entity Manager.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();

        for ($i = 0; $i < 30; ++$i) {
            $listing = new Listing();
            $listing->setTitle($this->faker->words(3, true));
            $listing->setDescription($this->faker->text());
            $listing->setCreatedAt(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-12 days', '-1 days'))
            );
            $listing->setActivatedAt(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-12 days', '-1 days'))
            );
            $manager->persist($listing);
        }

        $manager->flush();
    }
}
