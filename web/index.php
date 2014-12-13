<?php
//Set right timezone
define('ROOT',dirname(dirname(__FILE__)));
date_default_timezone_set('Europe/Madrid');

$app = require ROOT.'/src/app.php';

$app->run();
