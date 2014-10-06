<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
$app['debug'] = true;

// Docker
$app->register(new \N98\Docker\UI\Provider\DockerServiceProvider());

// Twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
));

// Controllers
$app->mount('/', new \N98\Docker\UI\ContainerControllerProvider());

$app->run();