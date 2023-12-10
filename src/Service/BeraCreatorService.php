<?php

namespace App\Service;

use App\Entity\Bera;
use App\Model\Mountain;

class BeraCreatorService
{
    public function create(
        Mountain $mountain,
        \DateTimeInterface $date,
        string $hash,
    ): Bera {
        $pdfLink = "https://donneespubliques.meteofrance.fr/donnees_libres/Pdf/BRA/BRA.{$mountain->value}.{$hash}.pdf";
        $xmlLink = "https://donneespubliques.meteofrance.fr/donnees_libres/Pdf/BRA/BRA.{$mountain->value}.{$hash}.xml";

        return new Bera(
            $mountain,
            $date,
            $hash,
            $pdfLink,
            $xmlLink,
            'no_xml_data',
        );
    }
}
