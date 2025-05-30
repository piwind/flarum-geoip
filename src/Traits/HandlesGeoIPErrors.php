<?php

/*
 * This file is part of geoip.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piwind\GeoIP\Traits;

use Piwind\GeoIP\Api\ServiceResponse;

trait HandlesGeoIPErrors
{
    protected function handleGeoIPError($service, $error): ServiceResponse
    {
        return (new ServiceResponse($service, true))
            ->setError($error);
    }
}
