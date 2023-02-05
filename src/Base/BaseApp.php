<?php

namespace Untek\Core\App\Base;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Untek\Core\App\Enums\AppEventEnum;
use Untek\Core\App\Events\AppEvent;
use Untek\Core\App\Interfaces\AppInterface;
use Untek\Core\App\Interfaces\EnvironmentInterface;
use Untek\Core\App\Libs\ZnCore;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Bundle\Libs\BundleLoader;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\Container\Traits\ContainerAttributeTrait;
use Untek\Core\EventDispatcher\Interfaces\EventDispatcherConfiguratorInterface;
use Untek\Core\EventDispatcher\Traits\EventDispatcherTrait;

/**
 * Абстрактный класс инициализатора приложения.
 *
 * Шаги инициализации:
 *
 *  - Инициализация окружения
 *  - Инициализация DI-контейнера
 *  - Загрузка бандлов
 *  - Инициализация диспетчера событий
 *
 */
abstract class BaseApp implements AppInterface
{

    use ContainerAttributeTrait;
    use EventDispatcherTrait;

    private $containerConfigurator;
    private $znCore;
    protected $bundles = [];
    private $import = [];
    private $bundleLoader;
    private ?Request $request = null;

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    abstract public function appName(): string;

    public function setBundles(array $bundles)
    {
        $this->bundles = $bundles;
    }

    public function addBundles(array $bundles): void
    {
        $this->bundles = ArrayHelper::merge($this->bundles, $bundles);
    }

    public function import(): array
    {
        return $this->import;
    }

    protected function bundles(): array
    {
        return $this->bundles;
    }

    public function __construct(
        ContainerInterface $container,
        EventDispatcherInterface $dispatcher,
        ZnCore $znCore,
        ContainerConfiguratorInterface $containerConfigurator
    ) {
        $this->setContainer($container);
        $this->setEventDispatcher($dispatcher);
        $this->containerConfigurator = $containerConfigurator;
        $this->znCore = $znCore;
    }

    /**
     * Инициализация приложения
     */
    public function init(): void
    {
        $this->dispatchEvent(AppEventEnum::BEFORE_INIT_CONTAINER);
        $this->initContainer();
        $this->dispatchEvent(AppEventEnum::AFTER_INIT_CONTAINER);

        $this->dispatchEvent(AppEventEnum::BEFORE_INIT_ENV);
        $this->initEnv();
        $this->dispatchEvent(AppEventEnum::AFTER_INIT_ENV);

        $this->dispatchEvent(AppEventEnum::BEFORE_INIT_BUNDLES);
        $this->initBundles();
        $this->dispatchEvent(AppEventEnum::AFTER_INIT_BUNDLES);

        $this->dispatchEvent(AppEventEnum::BEFORE_INIT_DISPATCHER);
        $this->initDispatcher();
        $this->dispatchEvent(AppEventEnum::AFTER_INIT_DISPATCHER);
    }

    private $mode = null;

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    protected function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Инициализация окружения
     */
    protected function initEnv(): void
    {
        /** @var EnvironmentInterface $environment */
        $environment = $this->getContainer()->get(EnvironmentInterface::class);
        $rootDirectory = realpath(__DIR__ . '/../../../../../../gate.vea');
        $environment->init($this->getMode(), $rootDirectory);
    }

    /**
     * Инициализация DI-контейнера
     *
     * Объявляет только самые необходимые зависимости для запуска приложения.
     */
    protected function initContainer(): void
    {
        $this->configContainer($this->containerConfigurator);
    }

    /**
     * Загрузка подключенных бандлов.
     */
    protected function initBundles(): void
    {
        $bundleLoader = $this->getBundleLoader();
        $bundleLoader->loadMainConfig($this->bundles(), $this->import());
    }

    /**
     * Инициализация диспетчера событий.
     *
     *
     */
    protected function initDispatcher(): void
    {
        $eventDispatcherConfigurator = $this->getContainer()->get(EventDispatcherConfiguratorInterface::class);
        $this->configDispatcher($eventDispatcherConfigurator);
    }

    /**
     * Получить конфиг загрузчиков бандла
     * @return array
     */
    protected function bundleLoaders(): array
    {
        return include __DIR__ . '/../../../../ntk-sandbox/packages/untek-lib/components/src/DefaultApp/config/bundleLoaders.php';
    }

    /**
     * Создать загрузчик бандла
     * @return BundleLoader
     */
    protected function createBundleLoaderInstance(): BundleLoader
    {
        return $this->getContainer()->get(BundleLoader::class);
    }

    /**
     * Конфигурироывть загрузчик бандла
     * @param BundleLoader $bundleLoader
     */
    protected function configureBundleLoader(BundleLoader $bundleLoader): void
    {
        $loaders = $this->bundleLoaders();
        if ($loaders) {
            foreach ($loaders as $loaderName => $loaderDefinition) {
                $bundleLoader->registerLoader($loaderName, $loaderDefinition);
            }
        }
    }

    /**
     * Получить объект загрузчика бандла
     * @return BundleLoader
     */
    protected function getBundleLoader(): BundleLoader
    {
        if ($this->bundleLoader == null) {
            $this->bundleLoader = $this->createBundleLoaderInstance();
            $this->configureBundleLoader($this->bundleLoader);
        }
        return $this->bundleLoader;
    }

    /**
     * Конфигурация диспетчера событий
     *
     * @param EventDispatcherConfiguratorInterface $configurator
     */
    protected function configDispatcher(EventDispatcherConfiguratorInterface $configurator): void
    {
    }

    /**
     * Опубликовать событие
     *
     * @param string $eventName
     */
    protected function dispatchEvent(string $eventName): void
    {
        $event = new AppEvent($this);
        $this->getEventDispatcher()->dispatch($event, $eventName);
    }
}
