<?php

/**
 * Listing controller Tests.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @class CategoryControllerTest
 */
class CategoryControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;

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

        $category = $this->createTestCategory();
        $this->testCategory = $category;
    }

    /**
     * Tests route if '/category'  exists.
     */
    public function testCategoryPage(): void
    {
        // when
        $this->client->request('GET', '/category');
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

    /**
     * Tests if route '/category/create'  exists.
     */
    public function testCategoryCreate(): void
    {
        // when
        $this->client->request('GET', '/category/create');
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

    /**
     * Tests if route `/category/update/[id]` exists.
     */
    public function testCategoryUpdate(): void
    {
        // given
        $category = $this->testCategory;

        // when
        $this->client->request('GET', '/category/update/'.$category->getId());
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

    /**
     * Test if route '/category/delete[id]' exists.
     */
    public function testCategoryDelete(): void
    {
        // given
        $category = $this->testCategory;

        // when
        $this->client->request('GET', '/category/delete/'.$category->getId());
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

    /**
     * Creates test Category.
     *
     * @return Category Category Entity
     */
    private function createTestCategory(): Category
    {
        $category = new Category();
        $category->setTitle('Test Category');
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
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
