<?php

namespace App\Controller;

use App\Entity\Subscriber;
use App\Event\SubscriberCreatedEvent;
use App\Form\SubscriberCreateType;
use App\Form\SubscriberEditCreateType;
use App\Form\SubscriberEmailType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
        $form = $this->createForm(SubscriberCreateType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subscriber);
            $subscriber->setEditLink($this->generateUrl('app_editsubscription', ['token' => $subscriber->getToken()], UrlGeneratorInterface::ABSOLUTE_URL));
            $entityManager->flush();
            $dispatcher->dispatch(new SubscriberCreatedEvent($subscriber));
            $this->addFlash(
                'success', <<<EOM
Vous avez été abonné aux publications des massifs {$subscriber->getMountainsAsString()}. Le dernier BERA vous a été envoyé. Vérifiez dans vos spams si vous ne le voyez pas apparaître.
EOM
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('subscribe.html.twig', ['form' => $form]);
    }

    #[Route('/edit-subscription/{token}', name: 'app_editsubscription')]
    public function editSubscription(
        Request $request,
        Subscriber $subscriber,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(SubscriberEditCreateType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Votre abonnement a été mis à jour.');

            return $this->redirectToRoute('app_editsubscription', ['token' => $subscriber->getToken()]);
        }

        return $this->render('edit_subscription.html.twig', ['form' => $form, 'subscriber' => $subscriber]);
    }
}
