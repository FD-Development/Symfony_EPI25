<?php

/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

/**
 * @class  UserService.
 */
class UserService implements UserServiceInterface
{
    /**
     * Constructor.
     *
     * @param UserRepository $userRepository User Repository
     */
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Change password.
     *
     * @param User   $user        User entity
     * @param string $newPassword New password string
     */
    public function changePassword(User $user, string $newPassword): void
    {
        $user->setPassword($newPassword);
    }
}
