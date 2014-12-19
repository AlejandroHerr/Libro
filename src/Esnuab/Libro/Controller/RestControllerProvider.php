<?php
namespace Esnuab\Libro\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;

class RestControllerProvider implements ControllerProviderInterface, ServiceProviderInterface
{
    public function boot(Application $app)
    {
    }

    public function register(Application $app)
    {
        $app['rest.socio_controller'] = $app->share(function () use ($app) {
            return new SocioRestController();
        });
    }

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->post('/socio', 'rest.socio_controller:createAction')
            ->before('rest.socio_controller:parsePayload');
        $controllers->get('/socio/{id}', 'rest.socio_controller:readAction')
            ->assert('id', '\d+');
        $controllers->delete('/socio/{id}', 'rest.socio_controller:deleteAction')
            ->assert('id', '\d+');
        $controllers->match('/socio/{id}', 'rest.socio_controller:updateAction')
            ->assert('id', '\d+')
            ->before('rest.socio_controller:parsePayload')
            ->method('PUT|PATCH');
        $controllers->get('/socios', 'rest.socio_controller:queryAction');
        $controllers->get('/socios/{page}', 'rest.socio_controller:queryAction')
            ->assert('page', '\d+');
        $controllers->get('/socios/{page}/{maxPerPage}', 'rest.socio_controller:queryAction')
            ->assert('page', '\d+')
            ->assert('maxPerPage', '\d+');

        return $controllers;
    }
}
