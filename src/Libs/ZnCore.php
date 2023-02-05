<?php

namespace Untek\Core\App\Libs;

use Psr\Container\ContainerInterface;
use Untek\Core\App\Interfaces\EnvStorageInterface;
use Untek\Core\App\Libs\EnvStorageDrivers\EnvStorageGetenv;
use Untek\Core\Bundle\Libs\BundleLoader;
use Untek\Core\ConfigManager\Interfaces\ConfigManagerInterface;
use Untek\Core\ConfigManager\Libs\ConfigManager;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\Container\Libs\ContainerConfigurator;
use Untek\Core\Container\Traits\ContainerAwareTrait;
use Untek\Core\Contract\Common\Exceptions\ReadOnlyException;

/**
 * Инициализатор окружения и предварительных конфигов
 */
class ZnCore
{

    use ContainerAwareTrait;

    /**
     * Инициализация и конфигурация DI-контейнера.
     */
    public function init(): void
    {
        $this->initContainer();
        $container = $this->getContainer();
        $this->configureContainer($container);
    }

    /**
     * Конфигурация DI-контейнера
     *
     * @param ContainerInterface $container
     */
    public function configureContainer(ContainerInterface $container)
    {
        $containerConfigurator = new ContainerConfigurator($container);
        $containerConfigurator->singleton(ContainerInterface::class, function () use ($container) {
            return $container;
        });
        $this->configContainer($containerConfigurator);
    }

    private function initContainer()
    {
        $container = $this->getContainer();
        try {
            ContainerHelper::setContainer($container);
        } catch (ReadOnlyException $exception) {
        }
    }

    /**
     * Подготовка системных компонентов
     *
     * - Конфигуратор DI-контейнера
     * - Менеджер сущностей
     * - Диспетчер событий
     * - Менеджер конфигов бандлов
     *
     * @param ContainerConfiguratorInterface $containerConfigurator
     */
    protected function configContainer(ContainerConfiguratorInterface $containerConfigurator): void
    {
        $containerConfigurator->singleton(ContainerConfiguratorInterface::class, function () use ($containerConfigurator) {
            return $containerConfigurator;
        });

        $entityManagerConfigCallback = require __DIR__ . '/../../../../ntk-sandbox/packages/untek-domain/entity-manager/src/config/container.php';
        call_user_func($entityManagerConfigCallback, $containerConfigurator);

        $eventDispatcherConfigCallback = require __DIR__ . '/../../../event-dispatcher/src/config/container.php';
        call_user_func($eventDispatcherConfigCallback, $containerConfigurator);

        $containerConfigurator->singleton(ConfigManagerInterface::class, ConfigManager::class);
        $containerConfigurator->singleton(EnvStorageInterface::class, EnvStorageGetenv::class);
        $containerConfigurator->singleton(BundleLoader::class, BundleLoader::class);
    }
}
