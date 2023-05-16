<?php

namespace Untek\Core\App\Libs\EnvStorageDrivers;

use Untek\Core\App\Interfaces\EnvStorageInterface;

/**
 * Хранилище переменных окружения.
 *
 * Получает переменные с помощью функции getenv.
 */
class EnvStorageGetenv implements EnvStorageInterface
{

    public function get(string $name, $default = null): mixed
    {
        return getenv($name) ?: $default;
    }

    public function has(string $name): bool
    {
        return getenv($name) !== null;
    }

    public function init(array $env)
    {
    }
}
