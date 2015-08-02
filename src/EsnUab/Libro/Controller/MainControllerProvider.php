<?php

namespace EsnUab\Libro\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;

class MainControllerProvider implements ControllerProviderInterface, ServiceProviderInterface
{
    public function boot(Application $app)
    {
    }

    public function register(Application $app)
    {
        $app['libro.socio.controller'] = $app->share(function () use ($app) {
            return new SocioController();
        });
        $app['libro.socio.middleware'] = $app->share(function () use ($app) {
            return new SocioMiddleware();
        });
    }

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/list/{page}/{limit}/{sort}', 'socio/query')
            ->value('page', 1)
            ->value('limit', 25)
            ->value('sort', '-id')
            ->before('libro.socio.middleware:parseSort')
            ->before('libro.socio.middleware:paginate')
            ->bind('socio.list')
            ->template('socio/query.html.twig');
        $controllers->get('/{socio}', 'socio/read')
            ->assert('socio', '\d+')
            ->convert('socio', 'libro.socio.middleware:findSocio')
            ->bind('socio.read')
            ->template('socio/read.html.twig');
        $controllers->get('/{socio}/v{version}', 'socio/readVersion')
            ->assert('socio', '\d+')
            ->assert('version', '\d+')
            ->convert('socio', 'libro.socio.middleware:findSocio')
            ->convert('version', 'libro.socio.middleware:findSocioVersion')
            ->bind('socio.read_version')
            ->template('socio/read.html.twig');

        $controllers->get('/nuevo', 'socio/new')
            ->template('socio/create.html.twig')
            ->bind('socio.create');
        $controllers->post('/nuevo', 'socio/create')
            ->template('socio/create.html.twig');

        $controllers->match('/{socio}/editar', 'libro.controller.socio:updateAction')
            ->assert('socio', '\d+')
            ->convert('socio', 'libro.controller.socio:findSocio')
            ->template('editar.html.twig')
            ->method('GET|POST')
            ->bind('socio.update');

        $controllers->match('/{socio}/baja', 'socio/modify')
            ->assert('socio', '\d+')
            ->convert('socio', 'libro.socio.middleware:findAndDismissSocio')
            ->method('GET|POST')
            ->bind('socio.dismiss');
        $controllers->get('/{socio}/borrar', 'socio/modify')
            ->assert('socio', '\d+')
            ->convert('socio', 'libro.socio.middleware:findAndRemoveSocio')
            ->bind('socio.remove');
        $controllers->get('/{socio}/restaurar', 'socio/modify')
            ->assert('socio', '\d+')
            ->convert('socio', 'libro.socio.middleware:findAndRestoreSocio')
            ->bind('socio.restore');

        return $controllers;
    }
}
