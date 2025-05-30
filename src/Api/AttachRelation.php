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

use Flarum\Api\Serializer\PostSerializer;
use Flarum\Post\Post;
use Flarum\Settings\SettingsRepositoryInterface;
use Piwind\GeoIP\Api\Serializer\BasicIPInfoSerializer;
use Piwind\GeoIP\Api\Serializer\IPInfoSerializer;
use Tobscure\JsonApi\Relationship;

class AttachRelation
{
    public function __construct(
        protected SettingsRepositoryInterface $settings
    ) {
    }

    public function __invoke(PostSerializer $serializer, Post $post): ?Relationship
    {
        $viewIPs = $serializer->getActor()->can('viewIps', $post);

        if ($viewIPs) {
            return $serializer->hasOne($post, IPInfoSerializer::class, 'ip_info');
        }

        $viewCountry = $serializer->getActor()->can('piwind-geoip.canSeeCountry');
        $showFlagsFeatureEnabled = $this->settings->get('piwind-geoip.showFlag');
        $userPreference = $post->user?->getPreference('showIPCountry');

        if ($viewCountry || ($showFlagsFeatureEnabled && $userPreference)) {
            return $serializer->hasOne($post, BasicIPInfoSerializer::class, 'ip_info');
        }

        return null;
    }
}
