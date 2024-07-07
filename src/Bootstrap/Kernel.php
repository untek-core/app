<?php

namespace Untek\Core\App\Bootstrap;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Untek\Framework\Console\Infrastructure\DependencyInjection\ConsoleCommandPass;
use Untek\Model\Cqrs\Infrastructure\DependencyInjection\CqrsPass;

class Kernel extends AbstractAppKernel
{

    protected function build(ContainerBuilder $container): void
    {
//        $container->addCompilerPass(new CqrsPass());
        $container->addCompilerPass(new RegisterListenersPass());
        $container->addCompilerPass(new ConsoleCommandPass());
    }
}
