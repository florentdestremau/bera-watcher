<?php

namespace App\Service;

use App\Entity\Bera;
use App\Entity\Subscriber;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Mailer\MailerInterface;

readonly class SendBeraByEmailService
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function sendEmail(Bera $bera, Subscriber $subscriber): void
    {
        $email = new NotificationEmail();
        $email->markAsPublic();
        $email->to($subscriber->getEmail());
        $email->subject("Votre BERA pour {$bera->getMountain()->value} est disponible");
        $email->markdown(
            <<<EOT
Bonjour,

Vous vous êtes abonné à la publication des BERA (Bulletin d'Estimation de Risques d'Avalance) pour le massif **{$bera->getMountain()->value}**.

Voici le lien pour consulter le dernier rapport en date pour ce massif.
EOT
        );
        $email->action('Consulter le BERA', $bera->getLink());
        $this->mailer->send($email);
    }
}
