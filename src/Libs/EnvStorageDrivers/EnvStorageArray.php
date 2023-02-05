<?php

namespace Untek\Core\App\Libs\EnvStorageDrivers;

use Untek\Core\App\Interfaces\EnvStorageInterface;
use Untek\Core\Contract\Common\Exceptions\InvalidConfigException;
use Untek\Domain\Entity\Exceptions\AlreadyExistsException;

/**
 * Хранилище переменных окружения.
 *
 * Хранит переменные в ОЗУ.
 */
class EnvStorageArray implements EnvStorageInterface
{

    protected array $env = [];

    public function get(string $name, $default = null): mixed
    {
        $this->checkInit();
        return $this->env[$name] ?? $default;
    }

    public function has(string $name): bool
    {
        $this->checkInit();
        return array_key_exists($name, $this->env);
    }

    /**
     * Назначить массив переменных окружения.
     *
     * При повторной попытке выдает исключение, что переменные уже были назначены ранее.
     * Это дает гарантию, что переменные буду неизменны после их назначения.
     *
     * @param array $env
     * @throws AlreadyExistsException Переменные уже были назначены ранее
     */
    public function init(array $env) {
        if(!empty($this->env)) {
            throw new AlreadyExistsException('Env config already inited!');
        }
        $this->env = $env;
    }

    /**
     * Проверка инициализации переменных окружения.
     *
     * @throws InvalidConfigException Переменные не инициализированы
     */
    protected function checkInit(): void {
        if(empty($this->env)) {
            throw new InvalidConfigException('Empty env config!');
        }
    }
}
