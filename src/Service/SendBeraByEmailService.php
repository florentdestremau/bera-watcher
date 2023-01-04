<?php

namespace App\Service;

use App\Entity\Bera;
use App\Entity\Subscriber;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

readonly class SendBeraByEmailService
{
    public function __construct(private NotifierInterface $notifier)
    {
    }

    public function sendEmail(Bera $bera, Subscriber $subscriber): void
    {
        $notification = new Notification("Votre BERA pour {$bera->getMountain()->value} est disponible", ['email']);
        $notification->content(
            <<<EOT
Bonjour,

Vous vous êtes abonné à la publication des BERA (Bulletin d'Estimation de Risques d'Avalance) pour le massif {$bera->getMountain()->value}.

Vous pouvez le consulter sur le lien suivant:

{$bera->getLink()}
EOT
        );
        $recipient = new Recipient($subscriber->getEmail());
        $this->notifier->send($notification, $recipient);
    }
}
