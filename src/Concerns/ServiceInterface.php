<?php

/*
 * This file is part of geoip.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piwind\GeoIP\Concerns;

interface ServiceInterface
{
    public function get(string $ip);

    public function getBatch(array $ips);

    public function batchSupported(): bool;
}
