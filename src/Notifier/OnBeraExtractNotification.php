<?php

namespace App\Notifier;

use App\Entity\Bera;
use App\Entity\Subscriber;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Routing\RouterInterface;

class OnBeraExtractNotification extends Notification implements EmailNotificationInterface
{
    public function __construct(private Bera $bera, array $channels = [])
    {
        parent::__construct("", $channels);
    }

    public function asEmailMessage(EmailRecipientInterface|Subscriber $recipient, string $transport = null): ?EmailMessage
    {
        $mountain = $this->bera->getMountain();


        $email = (new NotificationEmail())
            ->markAsPublic()
            ->to(new Address($recipient->getEmail()))
            ->subject("Nouveau BERA $this->bera")
            ->markdown(
                <<<EOT
Bonjour,

Un nouveau BERA est disponible pour le massif **{$mountain->value}**.
---

*Vous pouvez éditez vos préférences [ici]({$recipient->getEditLink()}).*
EOT
            )
            ->attachFromPath($this->bera->getLink(), "{$this->bera}.pdf")
            ->action("Consulter le BERA {$this->bera}", $this->bera->getLink());

        return new EmailMessage($email);
    }
}
