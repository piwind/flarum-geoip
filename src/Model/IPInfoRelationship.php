<?php

/*
 * This file is part of geoip.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piwind\GeoIP\Model;

use Flarum\Post\Post;
use Piwind\GeoIP\Repositories\GeoIPRepository;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IPInfoRelationship
{
    public function __construct(protected GeoIPRepository $geoIP)
    {
    }

    public function __invoke(Post $post): HasOne
    {
        return $post->hasOne(IPInfo::class, 'address', 'ip_address')
            ->withDefault(function (IPInfo $instance, Post $submodel) {
                return $this->geoIP->retrieveForPost($submodel);
            });
    }
}
