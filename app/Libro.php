<?php

use AlejandroHerr\Application\TraitApplication as Application;
use Esnuab\Libro\Controller\ControllerProvider;
use Esnuab\Twig\EsnuabExtensionProvider;
use Herrera\Wise\WiseServiceProvider;
use Propel\Silex\PropelServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;

$app = new Application();

$app['debug'] = true;

if ($app['debug']) {
    Symfony\Component\Debug\ErrorHandler::register();
    Symfony\Component\Debug\ExceptionHandler::register();
}
// SERVICES
$app->register(new WiseServiceProvider(), ['wise.path' => ROOT.'/config']);
$app->register(new PropelServiceProvider(), ['propel.config_file' => ROOT.'/config/config.php']);
$app->register(new ServiceControllerServiceProvider());
$app->register(new UrlGeneratorServiceProvider());

$app->register(new FormServiceProvider(), ['form.secret' => md5('random')]);
$app->register(new SessionServiceProvider());
$app->register(new TranslationServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.options' => [
        'debug' => true,
    ],
    'twig.form.templates' => ['form_div_layout.html.twig', 'bs_form_div_layout.twig'],
    'twig.path' => [ROOT.'/views/libro/templates', ROOT.'/views/common'],
));

$app->register(new EsnuabExtensionProvider());
$app['assets_path'] = ROOT.'/views/libro/assets';
$app['assets_path'] = $app->share(function () use ($app) {
    $request = $app['request'];

    return $request->getScheme().'://'.$request->getHost().'/views/libro/assets';
});

//CONTROLLERS

$controllerProvider = new ControllerProvider();
$app->register($controllerProvider);
$app->mount('/', $controllerProvider);

//ERROR HANDLING
//$app->error(function (\Exception $e, $code) use ($app) {
//    if ($code === 404) {
//        return $app->render('404.twig');
//    }
//});

return $app;
