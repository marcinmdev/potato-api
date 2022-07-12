<?php

namespace App\Story;

use App\Factory\UserAccountFactory;
use Zenstruck\Foundry\Story;

final class UserAccountStory extends Story
{
    public const API_TOKEN = 'test';

    public function build(): void
    {
        UserAccountFactory::createOne(
            [
                'apiToken' => self::API_TOKEN,
            ]
        );
    }
}
