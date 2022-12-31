<?php

namespace App\Controller;

use App\Entity\Subscriber;
use App\Event\SubscriberCreatedEvent;
use App\Form\SubscriberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SubscriberController extends AbstractController
{
    #[Route('/subscribe', name: 'app_subscribe')]
    public function subscribe(
        Request $request,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher
    ): Response {
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subscriber);
            $entityManager->flush();
            $dispatcher->dispatch(new SubscriberCreatedEvent($subscriber));
            $this->addFlash(
                'success', <<<EOM
Vous avez été abonné aux publications des massifs {$subscriber->getMountainsAsString()}. Le dernier BERA vous a été envoyé.
EOM
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('subscriber/subscribe.html.twig', ['form' => $form]);
    }
}
