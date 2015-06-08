<?php

namespace EsnUab\Libro\View;

use Silex\Application;
use Silex\ServiceProviderInterface;

class LibroTemplatesServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['twig'] = $app->extend('twig', function (\Twig_Environment $twig, Application $app) {
            $path = dirname(__FILE__).'/Templates';
            $app['twig.loader']->addLoader(new \Twig_Loader_Filesystem($path));

            return $twig;
        });
    }
    public function boot(Application $app)
    {
    }
}
