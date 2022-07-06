<?php

namespace App\Story;

use App\Entity\UserAccount;
use App\Factory\UserAccountFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Story;

final class UserAccountStory extends Story
{
    public const USER_NAME = 'test@test.test';
    public const USER_PASSWORD = 'test';

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function build(): void
    {
        UserAccountFactory::createOne(
            [
                'email' => self::USER_NAME,
                'password' => $this->passwordHasher->hashPassword(new UserAccount(), self::USER_PASSWORD),
            ]
        );
    }
}
