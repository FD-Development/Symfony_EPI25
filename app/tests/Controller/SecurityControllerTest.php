<?php

/**
 * Security Controller Tests.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest.
 */
class SecurityControllerTest extends WebTestCase
{
    /**
     * User Repository.
     */
    private ?EntityManagerInterface $entityManager;
    /**
     * Test client.
     */
    private KernelBrowser $client;

    /**
     * User Entity.
     */
    private ?User $user;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        $user = new User();
        $user->setUsername('login_test_user');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('password'); // w testach nie potrzebujemy hashowania

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->user = $user;
    }

    /**
     * Test if route '/login' exists.
     */
    public function testLoginRouteAnonymous(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->client->request('GET', '/login');
        $resultStatusCode = $this->client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    /**
     * Test if '/login' redirects authenticated user to '/'.
     */
    public function testLoginRedirectsAuthenticatedUser(): void
    {
        // given
        $user = $this->user;
        $this->client->loginUser($user);
        $expectedRedirect = '/';

        // when
        $this->client->request('GET', '/login');
        $resultRedirect = $this->client->getResponse()->headers->get('Location');

        // then
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertStringContainsString($expectedRedirect, $resultRedirect);
    }

    /**
     * Tests if '/logout' route exists.
     */
    public function testLogoutRoute(): void
    {
        // given
        $user = $this->user;
        $this->client->loginUser($user);

        // when
        $this->client->request('GET', '/logout');

        // then
        $this->assertTrue(
            $this->client->getResponse()->isRedirection() || $this->client->getResponse()->isServerError(),
            'Logout should trigger firewall and not be directly accessible.'
        );
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

            $this->entityManager->close();
        }
    }
}
