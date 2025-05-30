<?php

/*
 * This file is part of geoip.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piwind\GeoIP\Command;

use Flarum\User\User;

class FetchIPInfoBatch
{
    public function __construct(
        public array $ips,
        public ?User $actor = null,
        public bool $refresh = false
    ) {
    }
}
