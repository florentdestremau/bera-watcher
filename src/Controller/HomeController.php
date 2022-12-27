<?php

namespace App\Controller;

use App\Form\LookupType;
use App\Model\Mountain;
use App\Service\BeraFinderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('', name: 'app_home')]
    public function index(Request $request, BeraFinderService $beraFinderService): Response
    {
        $session = $request->getSession();
        $form = $this->createForm(LookupType::class, [
            'mountains' => $session->has('mountains') ? Mountain::from($session->get('mountains')) : null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $url = $beraFinderService->findPDfUrl($data['mountains'], $data['date']);
            $session->set('mountains', $data['mountains']->value);

            if ($url) {

                return $this->redirect($url);
            } else {
                $this->addFlash('danger', 'BERA introuvable');

                return $this->redirectToRoute('app_home');
            }
        }

        return $this->render('home/index.html.twig', ['form' => $form, 'message' => $message ?? null]);
    }
}
