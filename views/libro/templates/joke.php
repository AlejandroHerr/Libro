<?php

$camera = $bag->getCamera();
$roll = new Roll('Kodak T-Max 400');
$camera->loadFilm($roll)->setIso(800);
$camera->setMode('A');
while ($camera->wind()) {
    $roll[] = $camera->setAperture(5.6)->focus($subject)->shoot();
}

$roll->develope();
