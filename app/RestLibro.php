<?php

use AlejandroHerr\Exception\Arr\HttpException;
use AlejandroHerr\Exception\Arr\ExceptionInterface;
use Esnuab\Libro\Controller\RestControllerProvider;
use Herrera\Wise\WiseServiceProvider;
use Propel\Silex\PropelServiceProvider;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

$app = new Application();

$app['debug'] = true;

// SERVICES
$app->register(new WiseServiceProvider(), ['wise.path' => ROOT.'/config']);
$app->register(new PropelServiceProvider(), ['propel.config_file' => ROOT.'/config/config.php']);
$app->register(new ServiceControllerServiceProvider());

//CONTROLLERS
$restControllerProvider = new RestControllerProvider();
$app->register($restControllerProvider);
$app->mount('/', $restControllerProvider);

//ERROR HANDLING
$app->error(function (\Exception $e, $code) use ($app) {
    if (!$e instanceof HttpException) {
        return;
    }
    $message = [];
    $message[] = $e->getArrayMessage();
    if (!$app['debug']) {
        while ($e->getPrevious() instanceof ExceptionInterface) {
            $e = $e->getPrevious();
            $message[] = $e->getArrayMessage();
        }
    }

    return new JsonResponse($message, $code);
});

return $app;
