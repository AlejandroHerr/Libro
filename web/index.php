<?php

define('ROOT', dirname(dirname(__FILE__)));
date_default_timezone_set('Europe/Madrid');

$app = include ROOT.'/app/app.php';

$app->run();
