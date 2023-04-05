<?php

namespace Untek\Core\App\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Untek\Core\App\Enums\AppEventEnum;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Container\Traits\ContainerAwareTrait;
use Untek\Core\Env\Helpers\PhpErrorHelper;

DeprecateHelper::softThrow();

class PhpErrorSubscriber implements EventSubscriberInterface
{

    use ContainerAwareTrait;

    public static function getSubscribedEvents(): array
    {
        return [
            AppEventEnum::AFTER_INIT_ENV => 'onAfterInit',
        ];
    }

    public function onAfterInit(Event $event, string $eventName)
    {
        PhpErrorHelper::setErrorVisible(boolval(getenv('APP_DEBUG')));
    }
}
