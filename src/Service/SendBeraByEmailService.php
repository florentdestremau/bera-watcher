<?php

namespace App\Service;

use App\Entity\Bera;
use App\Entity\Subscriber;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class SendBeraByEmailService
{
    public function __construct(private MailerInterface $mailer, private ContainerBagInterface $params)
    {
    }

    public function sendEmail(Bera $bera, Subscriber $subscriber): void
    {
        $message = new Email();
        $senderAddress = $this->params->get('email_sender_address');
        $message->from(new Address($senderAddress, 'BERA Watch'));
        $message->to($subscriber->getEmail());
        $message->subject("Votre BERA pour {$bera->getMountain()->value} est disponible");
        $message->html(
            <<<EOT
<p>Bonjour,</p>

<p>Vous vous êtes abonné à la publication des BERA (Bulletin d'Estimation de Risques d'Avalance) pour le massif <strong>{$bera->getMountain()->value}</strong>.</p>

<p>Vous pouvez le consulter sur le lien suivant:</p>

<a href="{$bera->getLink()}">{$bera->getLink()}</a>
EOT
        );

        $this->mailer->send($message);

    }
}
