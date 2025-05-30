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

use Flarum\Post\Event\Saving as PostSaving;
use Piwind\GeoIP\Jobs;
use Piwind\GeoIP\Repositories\GeoIPRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\Queue;

class RetrieveIP
{
    /**
     * @var Queue
     */
    protected $queue;

    /**
     * @var GeoIPRepository
     */
    protected $geo;

    public function __construct(Queue $queue, GeoIPRepository $geo)
    {
        $this->queue = $queue;
        $this->geo = $geo;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(PostSaving::class, [$this, 'handlePost']);
    }

    public function retrieveIP(?string $ip): void
    {
        if ($ip !== null && $this->geo->isValidIP($ip) && !$this->geo->recordExistsForIP($ip)) {
            $this->queue->push(new Jobs\RetrieveIP($ip));
        }
    }

    public function handlePost(PostSaving $event)
    {
        $this->retrieveIP($event->post->ip_address);
    }
}
