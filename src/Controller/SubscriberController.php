<?php

namespace App\Controller;

use App\Entity\Subscriber;
use App\Form\SubscriberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriberController extends AbstractController
{
    #[Route('/subscribe', name: 'app_subscribe')]
    public function subscribe(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subscriber);
            $entityManager->flush();
            $this->addFlash('success', sprintf("Vous avez été abonné aux publications des massifs %s", $subscriber->getMountainsAsString()));

            return $this->redirectToRoute('app_home');
        }

        return $this->render('subscriber/subscribe.html.twig', ['form' => $form]);
    }
}
