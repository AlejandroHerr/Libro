<?php

namespace EsnUab\Twig\TwBsExtension;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Twitter Bootstrap components integration for Silex.
 *
 * @author AlejandroHerr <alejandrohnc88@gmail.com>
 */
class TwBsExtensionProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['twig'] = $app->extend('twig', function (\Twig_Environment $twig, Application $app) {
            $twig->addExtension(new TwBsExtension());

            $path = dirname(__FILE__).'/templates';
            $app['twig.loader']->addLoader(new \Twig_Loader_Filesystem($path));

            return $twig;
        });
    }
    public function boot(Application $app)
    {
    }
}
