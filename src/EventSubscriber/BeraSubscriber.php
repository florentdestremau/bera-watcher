<?php

namespace App\EventSubscriber;

use App\Event\BeraCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BeraSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeraCreatedEvent::class => 'onBeraCreatedEvent',
        ];
    }

    public function onBeraCreatedEvent(BeraCreatedEvent $event): void
    {
        $bera = $event->bera;

        if ($bera->getDate()->format('Y-m-d') === date('Y-m-d')) {
            // Todo: send notification
        }
    }
}
