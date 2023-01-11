<?php

namespace App\Notifier;

use App\Entity\Bera;
use App\Entity\Subscriber;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;

class OnSubscribeNotification extends Notification implements EmailNotificationInterface
{
    public function __construct(private Bera $bera)
    {
        parent::__construct("Votre BERA pour {$this->bera->getMountain()->value} est disponible", ['email']);
    }

    public function asEmailMessage(EmailRecipientInterface|Subscriber $recipient, string $transport = null): ?EmailMessage
    {
        $email = (new NotificationEmail())
            ->markAsPublic()
            ->to(new Address($recipient->getEmail()))
            ->subject($this->getSubject())
            ->markdown(
                <<<EOT
Bonjour,

Vous vous êtes abonné à la publication des BERA (Bulletin d'Estimation de Risques d'Avalance) pour le massif **{$this->bera->getMountain()->value}**.

À tout moment vous pouvez éditer vos préférences sur le lien suivant:

[{$recipient->getEditLink()}]({$recipient->getEditLink()})
EOT
            )
            ->attachFromPath($this->bera->getLink(), "{$this->bera}.pdf")
            ->action("Consulter le BERA {$this->bera}", $this->bera->getLink());

        return new EmailMessage($email);
    }
}
