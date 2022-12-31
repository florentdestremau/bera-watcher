<?php

namespace App\Event;

use App\Entity\Subscriber;
use Symfony\Contracts\EventDispatcher\Event;

class SubscriberCreatedEvent extends Event
{
    public function __construct(public Subscriber $subscriber)
    {
    }
}
