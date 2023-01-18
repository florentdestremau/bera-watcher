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
        $crawler = new Crawler($this->bera->getXml());
        $risk = $crawler->filterXPath('//RISQUE')->extract(['RISQUEMAXI'])[0];
        $comment = $crawler->filterXPath('//RISQUE')->extract(['COMMENTAIRE'])[0];
        $summary = $crawler->filterXPath('//RESUME')->text('', false);
        $stability = $crawler->filterXPath('//STABILITE/TEXTE')->text('', false);
        $quality = $crawler->filterXPath('//QUALITE/TEXTE')->text('', false);

        $email = (new NotificationEmail())
            ->markAsPublic()
            ->to(new Address($recipient->getEmail()))
            ->subject("BERA: risque $risk pour {$this->bera->getMountain()->value} le {$this->bera->getDate()->format('d/m/Y')}")
            ->addPart((new DataPart(base64_decode($crawler->filterXPath('//ImageCartoucheRisque/Content')->text()), 'risque.png'))->asInline())
            ->markdown(
                <<<EOT
Bonjour,

Un nouveau BERA est disponible pour le massif **{$this->bera->getMountain()->value}**. 

Risque maximal: {$risk}.

![$comment](cid:risque.png)

**Résumé:**

{$summary}

**Stabilité:**

{$stability}

**Neige:**

{$quality}


*Vous pouvez éditez vos préférences [ici]({$recipient->getEditLink()}).*
EOT
            )
            ->attachFromPath($this->bera->getLink(), "{$this->bera}.pdf")
            ->action("Consulter le BERA {$this->bera}", $this->bera->getLink());

        return new EmailMessage($email);
    }
}
