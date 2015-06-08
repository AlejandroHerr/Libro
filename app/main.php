<?php

use AlejandroHerr\Silex\Application;
use AlejandroHerr\Silex\SlashControllerResolver;
use EsnUab\Libro\Controller\MainControllerProvider;
use Herrera\Wise\WiseServiceProvider;
use Propel\Silex\PropelServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TwigServiceProvider;
use AlejandroHerr\Silex\EventListener\TemplateRenderingListener;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use EsnUab\Twig\UtilsExtensionProvider;
use EsnUab\AdminTheme\View\AdminThemeTemplatesServiceProvider as ThemeTemplate;
use EsnUab\Libro\View\LibroTemplatesServiceProvider as LibroTemplates;
use EsnUab\Libro\EventListener\SocioListener;
use EsnUab\Twig\TwBsExtension\TwBsExtensionProvider;

$app = new Application();

$app['debug'] = true;
$app['locale'] = 'es';
$app['route_class'] = 'AlejandroHerr\\Silex\\Route';
$app['dispatcher']->addSubscriber(new TemplateRenderingListener($app));
$app['dispatcher']->addSubscriber(new SocioListener($app));
$app['resolver'] = $app->share($app->extend('resolver', function ($resolver, $app) {
    return new SlashControllerResolver('libro', $app, $app['logger']);
}));
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => ROOT.'/var/development.'.date('Ymd').'.log',
));
$app->register(new WiseServiceProvider(), ['wise.path' => ROOT.'/config']);
$app->register(new PropelServiceProvider(), ['propel.config_file' => ROOT.'/config/config.php']);
$app->register(new UrlGeneratorServiceProvider());

$app->register(new FormServiceProvider(), ['form.secret' => md5('random')]);
$app->register(new SessionServiceProvider());
$app->register(new TranslationServiceProvider(), ['locale' => ['es'], 'locale_fallbacks' => ['es']]);

$app->register(new TwigServiceProvider(), [
    'twig.options' => ['debug' => $app['debug']],
    'twig.form.templates' => ['bootstrap_3_horizontal_layout.html.twig'],
]);
$app->register(new ThemeTemplate());
$app->register(new LibroTemplates());
$app->register(new UtilsExtensionProvider());
$app->register(new TwBsExtensionProvider());
$app['assets_path'] = $app->share(function () use ($app) {
    $request = $app['request'];

    return $request->getScheme().'://'.$request->getHost().'/public/assets';
});

$mainControllerProvider = new MainControllerProvider();
$app->register($mainControllerProvider);
$app->mount('/socios', $mainControllerProvider);

$app->run();
