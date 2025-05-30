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

use Piwind\GeoIP\Api\GeoIP;
use Piwind\GeoIP\Model\IPInfo;
use Piwind\GeoIP\Repositories\GeoIPRepository;
use Psr\Log\LoggerInterface;

class FetchIPInfoHandler
{
    public function __construct(
        protected GeoIP $geoip,
        protected GeoIPRepository $repository,
        protected LoggerInterface $log
    ) {
    }

    public function handle(FetchIPInfo $command): IPInfo
    {
        if (!$this->repository->isValidIP($command->ip)) {
            $this->log->info('Invalid IP address: '.$command->ip);
        }

        $ipInfo = IPInfo::query()->firstOrNew(['address' => $command->ip]);

        if (!$ipInfo->exists || $command->refresh) {
            $response = $this->geoip->get($command->ip);

            if (!$response || $response->fake) {
                $this->log->error("Unable to fetch IP information for IP: {$command->ip}", $response->toJSON());
            }

            if ($response) {
                $ipInfo->address = $command->ip;
                $ipInfo->fill($response->toJSON());
                $ipInfo->save();
            }
        }

        return $ipInfo;
    }
}
