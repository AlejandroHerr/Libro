<?php
namespace Esnuab\Libro\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;

class ControllerProvider implements ControllerProviderInterface, ServiceProviderInterface
{
    public function boot(Application $app)
    {
    }

    public function register(Application $app)
    {
        $app['socio_controller'] = $app->share(function () use ($app) {
            return new SocioController();
        });
    }

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        //CREATE ROUTES
        $controllers->get('/socio/nuevo', 'socio_controller:newAction')
            ->bind('new_socio');
        $controllers->post('/socio/nuevo', 'socio_controller:createAction')
            ->bind('create_socio');
        //READ ROUTES
        $controllers->get('/socio/{id}', 'socio_controller:readAction')
            ->assert('id', '\d+')
            ->bind('read_socio');
        $controllers->get('/socio/{id}/v{version}', 'socio_controller:readAction')
            ->assert('id', '\d+')
            ->assert('version', '\d+')
            ->bind('read_socio_version');
        //UPDATE ROUTES
        $controllers->get('/socio/{id}/editar', 'socio_controller:editAction')
            ->assert('id', '\d+')
            ->bind('edit_socio');
        $controllers->post('/socio/{id}/editar', 'socio_controller:updateAction')
            ->assert('id', '\d+')
            ->bind('update_socio');
        //DELETE ROUTES
        $controllers->get('/socio/{id}/delete', 'socio_controller:deleteAction')
            ->assert('id', '\d+')
            ->bind('delete_socio');
        //QUERY ROUTES
        $controllers->get('/socios', 'socio_controller:queryAction')
            ->bind('query_socio');
        $controllers->get('/socios/{page}', 'socio_controller:queryAction')
            ->assert('page', '\d+')
            ->bind('paging_socio');
        $controllers->get('/socios/{page}/{maxPerPage}', 'socio_controller:queryAction')
            ->assert('page', '\d+')
            ->assert('maxPerPage', '\d+')
            ->bind('total_paging_socio');

        return $controllers;
    }
}
