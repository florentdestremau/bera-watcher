<?php

namespace App\Controller;

use App\Entity\Bera;
use App\Event\BeraCreatedEvent;
use App\Form\LookupType;
use App\Model\Mountain;
use App\Repository\BeraRepository;
use App\Service\BeraCreatorService;
use App\Service\BeraGithubExtractorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class HomeController extends AbstractController
{
    public function __construct(
        private BeraGithubExtractorService $githubExtractorService,
        private EntityManagerInterface $entityManager,
        private BeraRepository $beraRepository,
        private EventDispatcherInterface $dispatcher,
        private BeraCreatorService $beraCreatorService,
    ) {
    }

    #[Route('', name: 'app_home')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(LookupType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $bera = $this->findBera($data['mountain'], $data['date']);

            if ($bera instanceof Bera) {
                return $this->redirect($bera->getLink());
            }

            $form->addError(new FormError("BERA introuvable"));
        }

        return $this->render('index.html.twig', [
            'form'            => $form,
            'message'         => $message ?? null,
            'beras'           => $this->beraRepository->findBy([], ['date' => 'DESC'], 35),
            'totalBerasCount' => $this->beraRepository->count([]),
        ]);
    }

    private function findBera(Mountain $mountain, \DateTime $date): ?Bera
    {
        $bera = $this->beraRepository->findOneBy(['mountain' => $mountain, 'date' => $date]);

        if (!$bera instanceof Bera) {
            $hash = $this->githubExtractorService->findBeraHash($mountain, $date);

            if ($hash) {
                $bera = $this->beraCreatorService->create($mountain, $date, $hash);
                $this->entityManager->persist($bera);
                $this->entityManager->flush();
                $this->dispatcher->dispatch(new BeraCreatedEvent($bera));
            }
        }


        return $bera;
    }
}
