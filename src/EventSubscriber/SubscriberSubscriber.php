<?php

namespace App\EventSubscriber;

use App\Entity\Bera;
use App\Event\SubscriberCreatedEvent;
use App\Notifier\SendBeraAfterSubscribedNotification;
use App\Repository\BeraRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class SubscriberSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BeraRepository $beraRepository,
        private NotifierInterface $notifier,
        private string $adminEmail,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SubscriberCreatedEvent::class => 'onSubscriberCreatedEvent',
        ];
    }

    public function onSubscriberCreatedEvent(SubscriberCreatedEvent $event): void
    {
        foreach ($event->subscriber->getMountains() as $mountain) {
            $latestBera = $this->beraRepository->findOneBy(['mountain' => $mountain], ['date' => 'DESC']);

            if ($latestBera instanceof Bera) {
                $this->notifier->send(
                    new SendBeraAfterSubscribedNotification($latestBera, ['email']),
                    new Recipient($event->subscriber->getEmail())
                );
            }
        }

        $this->notifier->send(
            (new Notification("Nouvel abonné: {$event->subscriber}", ['email'])),
            new Recipient($this->adminEmail)
        );
    }
}
