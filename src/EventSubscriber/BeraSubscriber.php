<?php

namespace App\EventSubscriber;

use App\Event\BeraCreatedEvent;
use App\Notifier\OnBeraExtractNotification;
use App\Repository\SubscriberRepository;
use App\Service\SendBeraByEmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\NotifierInterface;

class BeraSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SubscriberRepository $subscriberRepository,
        private NotifierInterface $notifier
    ) {
    }

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
            $subscribers = $this->subscriberRepository->findByMountain($bera->getMountain());

            foreach ($subscribers as $subscriber) {
                $day = date('N');
                if ($subscriber->hasDay($day)) {
                    $this->notifier->send(
                        new OnBeraExtractNotification($bera, ['email']),
                        $subscriber,
                    );
                }
            }
        }
    }
}
