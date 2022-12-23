<?php

namespace App\Controller;

use App\Model\Mountain;
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
    public function index(Request $request): Response
    {
        $form = $this->createFormBuilder(null, ['attr' => ['target' => '_blank']])
            ->add('mountains', EnumType::class, ['class' => Mountain::class, 'choice_label' => 'value'])
            ->add('date', DateType::class, ['data' => new \DateTime()])
            ->add('lookup', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $mountains = $data['mountains']->value;
            $file = file_get_contents("https://raw.githubusercontent.com/qloridant/meteofrance_bra_hist/master/data/{$mountains}/urls_list.txt");
            $dates = explode("\n", $file);
            $searchdates = array_filter($dates, fn ($item) => str_starts_with($item, $data['date']->format('Ymd')));

            if (count($searchdates) > 0) {
                $hash = array_pop($searchdates);
                $url = "https://donneespubliques.meteofrance.fr/donnees_libres/Pdf/BRA/BRA.{$mountains}.{$hash}.pdf";
            }

            if ($url) {
                return $this->redirect($url);
            }
        }

        return $this->render('home/index.html.twig', ['form' => $form]);
    }
}
