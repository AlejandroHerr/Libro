<?php

namespace EsnUab\Cron\EventListener;

use EsnUab\Libro\EventListener\Event\SocioEvent;
use EsnUab\Libro\EventListener\SocioEvents;
use EsnUab\Cron\Model\SocioTask;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TaskListener implements EventSubscriberInterface
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function onSocioCreated(SocioEvent $event)
    {
        $socio = $event->getSocio();

        $socioTask = new SocioTask();
        $socioTask->setTargetId($socio->getId())
            ->save();
    }

    public static function getSubscribedEvents()
    {
        return [
            SocioEvents::SOCIO_CREATED => array('onSocioCreated', 0),
        ];
    }
}
