<?php

/*
 * This file is part of geoip.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Piwind\GeoIP\Listeners;

use Flarum\Settings\Event\Saving;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Support\Str;

class RemoveErrorsOnSettingsUpdate
{
    public function __construct(protected SettingsRepositoryInterface $settings)
    {
    }

    public function handle(Saving $event)
    {
        foreach ($event->settings as $key => $value) {
            if (!Str::startsWith($key, 'piwind-geoip.service')) {
                continue;
            }

            $service = $value;

            if ($key !== 'piwind-geoip.service') {
                $matches = null;
                preg_match('/piwind-geoip\.services\.(.+?)\./m', $key, $matches);

                $service = $matches[1] ?? $value;
            }

            $this->settings->delete("piwind-geoip.services.$service.error");
            $this->settings->delete("piwind-geoip.services.$service.last_error_time");
        }
    }
}
