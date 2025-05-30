<?php

/*
 * This file is part of geoip.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piwind\GeoIP\Api;

use Flarum\Api\Serializer\CurrentUserSerializer;
use Flarum\User\User;

class CurrentUserAttributes
{
    public function __invoke(CurrentUserSerializer $serializer, User $user, array $attributes): array
    {
        if ($serializer->getActor()->can('piwind-geoip.canSeeCountry')) {
            $attributes['canSeeCountry'] = true;
        }

        return $attributes;
    }
}
