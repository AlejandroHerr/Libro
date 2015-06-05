<?php

namespace EsnUab\Libro\EventListener;

use EsnUab\Libro\EventListener\Event\SocioEvent;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SocioListener implements EventSubscriberInterface
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function onSocioCreated(SocioEvent $event)
    {
        $socio = $event->getSocio();
        $this->app->addFlashBag(
            'success',
            '¡Socio creado correctamente!'.
            sprintf('Puedes verlo <a href="%s">aqu&iacute;</a>.', $this->app->url('socio.read', ['socio' => $socio->getId()]))
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            SocioEvents::SOCIO_CREATED => array('onSocioCreated', 0),
        ];
    }
}
