<?php

namespace Untek\Core\App\Events;

use Symfony\Contracts\EventDispatcher\Event;
use Untek\Core\App\Interfaces\AppInterface;

class AppEvent extends Event
{

    public function __construct(protected AppInterface $app)
    {
    }

    public function getApp(): AppInterface
    {
        return $this->app;
    }
}
