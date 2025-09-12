<?php

/**
 *  User Service Interface.
 */

namespace App\Service;

use App\Entity\User;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void;

    /**
     * Change password.
     *
     * @param User   $user        User entity
     * @param string $newPassword New password string
     */
    public function changePassword(User $user, string $newPassword): void;
}
