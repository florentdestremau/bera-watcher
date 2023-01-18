<?php

namespace App\Service;

use App\Model\Mountain;

class BeraGithubExtractorService
{

    public function findBeraHash(Mountain $mountains, \DateTime $date): ?string
    {
        $file = file_get_contents("https://raw.githubusercontent.com/qloridant/meteofrance_bra_hist/master/data/{$mountains->value}/urls_list.txt");
        $dates = explode("\n", $file);
        $searchdates = array_filter($dates, fn ($item) => str_starts_with($item, $date->format('Ymd')));
        $hash = null;

        if (count($searchdates) > 0) {
            $hash = array_pop($searchdates);
        }

        return $hash;
    }
}
