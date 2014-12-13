<?php
require_once ROOT . '/vendor/autoload.php';

use Silex\Application;

$app = new Application();

$app['debug'] = true;

$app->match('/',function(){return 'hola mundo';});

return $app;
