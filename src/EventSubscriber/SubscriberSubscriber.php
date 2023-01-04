<?php

namespace App\EventSubscriber;

use App\Entity\Bera;
use App\Event\SubscriberCreatedEvent;
use App\Repository\BeraRepository;
use App\Service\SendBeraByEmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class SubscriberSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BeraRepository $beraRepository,
        private SendBeraByEmailService $sendBeraByEmailService,
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
                $this->sendBeraByEmailService->sendEmail($latestBera, $event->subscriber);
            }
        }

        $notification = new Notification("Nouvel abonné: {$event->subscriber}", ['email']);
        $notification->importance(Notification::IMPORTANCE_MEDIUM);
        $recipient = new Recipient($this->adminEmail);
        $this->notifier->send($notification, $recipient);
    }
}
