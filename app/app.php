<?php
$loader = require ROOT."/vendor/autoload.php";

use Stack as S;
use Stack\Builder;
use Symfony\Component\HttpFoundation\Request;

$app = S\lazy(function () {
    return require 'Libro.php';
});
$api = S\lazy(function () {
    return require 'RestLibro.php';
});

$map = [
    '/api' => $api,
];

$app = (new Builder())
    ->push('Stack\UrlMap', $map)
    ->resolve($app);

$request = Request::createFromGlobals();

$response = $app->handle($request);
$response->send();

$app->terminate($request, $response);
