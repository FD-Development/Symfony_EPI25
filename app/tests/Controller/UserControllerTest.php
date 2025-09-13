<?php

/**
 * User Controller Test.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @class UserControllerTest
 */
class UserControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;
    private User $user;

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

        $this->user = $user;

        $this->client->loginUser($user);
    }

    /**
     * Test '/user' route.
     */
    public function testUserViewPage(): void
    {
        // when
        $this->client->request('GET', '/user');
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

    /**
     * Test '/user/[id]/update' route.
     */
    public function testUserUpdatePage(): void
    {
        // when
        $this->client->request('GET', '/user/'.$this->user->getId().'/update');
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

    /**
     * Test '/user/[id]/password-update' route.
     */
    public function testPasswordUpdatePage(): void
    {
        // when
        $this->client->request('GET', '/user/'.$this->user->getId().'/password-update');
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
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
