
<?php
require_once ROOT.'/vendor/autoload.php';

use Herrera\Wise\WiseServiceProvider;
use Propel\Silex\PropelServiceProvider;
use Silex\Application;

$app = new Application();

$app['debug'] = true;

$app->register(new WiseServiceProvider(), ['wise.path' => ROOT.'/config']);
//$app['propel.config_file'] = $app['wise']->load('propel.yml');
$app->register(new PropelServiceProvider(), ['propel.config_file' => ROOT.'/config/config.php']);

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

use Esnuab\Libro\Controller\RestControllerProvider;

$RestControllerProvider = new RestControllerProvider();

$app->register($RestControllerProvider);
$app->mount('/', $RestControllerProvider);

use Symfony\Component\HttpFoundation\JsonResponse;
use AlejandroHerr\Exception\Arr\HttpException;
use AlejandroHerr\Exception\Arr\ExceptionInterface;

$app->error(function (\Exception $e, $code) {
    if ($e instanceof HttpException) {
        $message = [];

        $message[1] = $e->getArrayMessage();
        while ($e->getPrevious() instanceof ExceptionInterface) {
            $e = $e->getPrevious();
            $message[] = $e->getArrayMessage();
        }

        return new JsonResponse($message, $code);
    }
});

return $app;
