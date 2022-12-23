<?php

namespace App\Controller;

use App\Model\Mountain;
use App\Service\BeraFinderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('', name: 'app_home')]
    public function index(Request $request, BeraFinderService $beraFinderService): Response
    {
        $defaultDate = new \DateTime();

        if ($defaultDate->format('H') < 15) {
            $defaultDate->modify('-1 day');
        }

        $form = $this->createFormBuilder(null, ['attr' => ['target' => '_blank'], 'csrf_protection' => true])
            ->add('mountains', EnumType::class, ['class' => Mountain::class, 'choice_label' => 'value'])
            ->add('date', DateType::class, ['data' => $defaultDate])
            ->add('lookup', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $url = $beraFinderService->findPDfUrl($data['mountains'], $data['date']);

            if ($url) {
                return $this->redirect($url);
            }
        }

        return $this->render('home/index.html.twig', ['form' => $form]);
    }
}
