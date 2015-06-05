<?php

namespace EsnUab\Libro\EventListener\Event;

use EsnUab\Libro\Model\Socio;
use Symfony\Component\EventDispatcher\Event;

class SocioEvent extends Event
{
    protected $socio;

    public function __construct(Socio $socio)
    {
        $this->socio = $socio;
    }

    public function getSocio()
    {
        return $this->socio;
    }
}
