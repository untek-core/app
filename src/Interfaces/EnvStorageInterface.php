<?php

namespace Untek\Core\App\Interfaces;

use Untek\Core\Contract\Common\Exceptions\InvalidConfigException;

/**
 * Хранилище переменных окружения.
 * 
 */
interface EnvStorageInterface
{

    /**
     * Получить переменную по ее имени.
     *
     * @param string $name Имя переменной
     * @param null $default Значение по умолчанию
     * @return mixed
     * @throws InvalidConfigException Переменные не инициализированы
     */
    public function get(string $name): mixed;

    /**
     * Проверка переменной на существование.
     *
     * @param string $name Имя переменной
     * @return bool
     * @throws InvalidConfigException Переменные не инициализированы
     */
    public function has(string $name): bool;
}
