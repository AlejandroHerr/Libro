<?php

//GLOBALS
define('ROOT', dirname(dirname(__FILE__)));
date_default_timezone_set('Europe/Madrid');

//AUTOLOAD
$loader = require ROOT.'/vendor/autoload.php';

//ERROR HANDLING
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

ErrorHandler::register();
ExceptionHandler::register();

//APP
include ROOT.'/app/main.php';
