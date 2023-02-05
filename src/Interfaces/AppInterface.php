<?php

namespace Untek\Core\App\Interfaces;

/**
 * Интерфейс приложения.
 */
interface AppInterface
{

    /**
     * Имя приложения.
     * 
     * Например: console, web, admin
     * 
     * @return string
     */
    public function appName(): string;

    /**
     * Инициализация приложения
     */
    public function init(): void;

    public function setMode(string $mode): void;
}
