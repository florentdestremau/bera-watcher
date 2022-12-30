<?php

namespace App\Event;

use App\Entity\Bera;
use Symfony\Contracts\EventDispatcher\Event;

class BeraCreatedEvent extends Event
{
    public function __construct(public Bera $bera)
    {
    }
}
