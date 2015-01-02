<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Esnuab\Twig;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Twig integration for Silex.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class EsnuabExtensionProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['twig'] = $app->extend("twig", function (\Twig_Environment $twig, Application $app) {
            $twig->addExtension(new EsnuabExtension('es'));

            $path = dirname(__FILE__).'/Views';
            $app['twig.loader']->addLoader(new \Twig_Loader_Filesystem($path));

            return $twig;
        });
    }
    public function boot(Application $app)
    {
    }
}
