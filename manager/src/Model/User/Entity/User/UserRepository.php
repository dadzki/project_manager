<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;


interface UserRepository
{
    public function findByConfirmToken(string $token): ?User;

    public function findByResetToken(string $token): ?User;

    public function hasByEmail(Email $email): bool;

    public function getByEmail(Email $email): User;

    public function get(Id $id): User;

    public function add(User $user): void;

    public function hasByNetworkIdentity(string $network, string $identity): bool;
}
