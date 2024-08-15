<?php

namespace Untek\Core\App\DependencyInjection\Factories;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class CacheFactory
{

    public static function create()
    {
        return new FilesystemAdapter('app_cache', 3600);
    }
}