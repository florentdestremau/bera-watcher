<?php

namespace App\Notifier;

use App\Entity\Bera;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;

class OnBeraExtractNotification extends Notification implements EmailNotificationInterface
{
    public function __construct(private Bera $bera, array $channels = [])
    {
        parent::__construct("Votre BERA pour {$this->bera->getMountain()->value} est disponible", $channels);
    }

    public function asEmailMessage(EmailRecipientInterface $recipient, string $transport = null): ?EmailMessage
    {
        $email = (new NotificationEmail())
            ->markAsPublic()
            ->to(new Address($recipient->getEmail()))
            ->subject($this->getSubject())
            ->markdown(
                <<<EOT
Bonjour,

Vous êtes abonné à la publication des BERA (Bulletin d'Estimation de Risques d'Avalance) pour le massif **{$this->bera->getMountain()->value}**.

Voici le lien pour consulter le dernier rapport en date pour ce massif.
EOT
            )
            ->action("Consulter le BERA pour {$this->bera->getMountain()->value}", $this->bera->getLink());

        return new EmailMessage($email);
    }
}
