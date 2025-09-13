<?php

/**
 * User Service Test.
 */

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @class UserServiceTest
 */
class UserServiceTest extends KernelTestCase
{
    /**
     * Test if password changes.
     */
    public function testPasswordChange()
    {
        // given
        self::bootKernel();
        $container = static::getContainer();
        $userService = $container->get(UserServiceInterface::class);
        $entityManager = $container->get('doctrine')->getManager();

        // User
        $user = new User();
        $user->setUsername('Test User');
        $user->setPassword('password');
        $user->setRoles(['ROLE_USER']);
        $entityManager->persist($user);

        // when
        $userService->changePassword($user, 'testPassword');

        // then
        $this->assertNotSame('testPassword', $user->getPassword());
        $this->assertTrue(password_verify('testPassword', $user->getPassword()));
    }
}
