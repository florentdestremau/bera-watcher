<?php

namespace App\Controller;

use App\Entity\Bera;
use App\Event\BeraCreatedEvent;
use App\Form\LookupType;
use App\Model\Mountain;
use App\Repository\BeraRepository;
use App\Service\BeraFinderService;
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
        private BeraFinderService $beraFinderService,
        private EntityManagerInterface $entityManager,
        private BeraRepository $beraRepository,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    #[Route('', name: 'app_home')]
    public function index(
        Request $request,
        BeraFinderService $beraFinderService,
    ): Response {
        $session = $request->getSession();
        $form = $this->createForm(LookupType::class, [
            'mountain' => $session->has('mountain') ? Mountain::from($session->get('mountain')) : null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $bera = $this->findBera($data['mountain'], $data['date']);

            if ($bera instanceof Bera) {
                $session->set('mountain', $bera->getMountain()->value);

                return $this->redirect($bera->getLink());
            }

            $form->addError(new FormError("BERA introuvable"));
        }

        return $this->render('home/index.html.twig', [
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
            $link = $this->beraFinderService->findPDfUrl($mountain, $date);

            if ($link) {
                $bera = new Bera($mountain, $date, $link);
                $this->entityManager->persist($bera);
                $this->entityManager->flush();
                $this->dispatcher->dispatch(new BeraCreatedEvent($bera));
            }
        }


        return $bera;
    }
}
