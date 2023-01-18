<?php

namespace App\Controller;

use App\Entity\Bera;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BeraController extends AbstractController
{
    #[Route('/bera/{id<\d+>}', name: 'app_bera')]
    public function show(Bera $bera): Response
    {

        $crawler = new Crawler($bera->getXml());
        $risqueNode = $crawler->filterXPath('//CARTOUCHERISQUE/RISQUE');
        $attributes = $risqueNode->extract(['RISQUE1', 'EVOLURISQUE1', 'LOC1', 'ALTITUDE', 'RISQUE2', 'EVOLURISQUE2', 'LOC2', 'RISQUEMAXI', 'COMMENTAIRE']);

        return $this->render('bera.html.twig', [
            'bera'    => $bera,
            'crawler' => $crawler,
        ]);
    }
}
