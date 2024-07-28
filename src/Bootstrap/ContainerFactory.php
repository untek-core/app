<?php

namespace Untek\Core\App\Bootstrap;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainerFactory
{

    private static ?ContainerInterface $container;

    public static function remove(): void
    {
        self::$container = null;
    }

    public static function set(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function create(): ContainerInterface
    {
        if (!isset(self::$container)) {
            self::$container = new ContainerBuilder();
        }
        return self::$container;
    }
}
