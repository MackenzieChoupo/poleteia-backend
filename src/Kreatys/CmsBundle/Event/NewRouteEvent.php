<?php

namespace Kreatys\CmsBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class NewRouteEvent extends Event
{
    const NAME = 'router.new_route';
}
