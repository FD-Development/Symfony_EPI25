<?php

/**
 * Category Service Test.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Service\CategoryServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class categoryServiceTest.
 */
class CategoryServiceTest extends KernelTestCase
{
    /**
     * Category Repository.
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Category Service.
     */
    private ?CategoryServiceInterface $categoryService;

    /**
     * This method is called before each test.
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine')->getManager();
        $this->categoryService = $container->get(CategoryServiceInterface::class);
    }

    /**
     * Tests if Category gets saved.
     */
    public function testSave(): void
    {
        // given
        $expectedCategory = new Category();
        $expectedCategory->setTitle('Test Category');

        // when
        $this->categoryService->save($expectedCategory);

        // then
        $expectedCategoryId = $expectedCategory->getId();
        $resultCategory = $this->entityManager->createQueryBuilder()
            ->select('category')
            ->from(Category::class, 'category')
            ->where('category.id = :id')
            ->setParameter(':id', $expectedCategoryId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedCategory, $resultCategory);
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
