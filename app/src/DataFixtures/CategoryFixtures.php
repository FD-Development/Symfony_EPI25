<?php

/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

/**
 * Class CategoryFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (!$this->manager instanceof ObjectManager || !$this->faker instanceof Generator) {
            return;
        }

        // Test Categories
        $test1 = new Category();
        $test1->setTitle('Test 1');
        $this->manager->persist($test1);
        $this->addReference('category_Test1', $test1);

        $test2 = new Category();
        $test2->setTitle('Test 2');
        $this->manager->persist($test2);
        $this->addReference('category_Test2', $test2);

        $this->manager->flush();

        $this->createMany(20, 'category', function (int $i) {
            $category = new Category();
            $category->setTitle($this->faker->unique()->word);

            return $category;
        });

    }
}
