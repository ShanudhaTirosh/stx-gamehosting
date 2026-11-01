<?php

namespace Boy132\RoboAvatars;

use App\Extensions\Avatar\AvatarSchemaInterface;
use App\Models\User;

class RoboAvatarsSchema implements AvatarSchemaInterface
{
    public function getId(): string
    {
        return 'robohash';
    }

    public function getName(): string
    {
        return 'Robohash';
    }

    public function get(User $user): string
    {
        return 'https://robohash.org/' . md5($user->email);
    }
}
