<?php

namespace Untek\Core\App\Bootstrap;

use Forecast\Map\Generic\Mq\Infrastructure\DependencyInjection\MessageQueuePass;
use Symfony\Component\Console\DependencyInjection\AddConsoleCommandPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\Messenger\DependencyInjection\MessengerPass;
use Untek\Core\App\Bootstrap\AbstractAppKernel;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Framework\Console\Infrastructure\DependencyInjection\ConsoleCommandPass;
use Untek\Model\Cqrs\Infrastructure\DependencyInjection\CqrsPass;
use Untek\Model\EntityManager\DependencyInjection\EntityManagerPass;

DeprecateHelper::hardThrow();

class Kernel extends AbstractAppKernel
{

    /*protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CqrsPass());
        $container->addCompilerPass(new EntityManagerPass());
        $container->addCompilerPass(new RegisterListenersPass());
//        $container->addCompilerPass(new AddConsoleCommandPass());
        $container->addCompilerPass(new ConsoleCommandPass());
        $container->addCompilerPass(new MessageQueuePass());
//        $container->addCompilerPass(new MessengerPass());
    }*/
}
