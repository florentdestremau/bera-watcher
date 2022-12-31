<?php

namespace App\EventSubscriber;

use App\Entity\Bera;
use App\Event\SubscriberCreatedEvent;
use App\Repository\BeraRepository;
use App\Service\SendBeraByEmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriberSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BeraRepository $beraRepository,
        private SendBeraByEmailService $sendBeraByEmailService,
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
    }
}
