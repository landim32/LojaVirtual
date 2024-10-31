<?php

namespace Emagine\Base\Core;

use Psr\Container\ContainerInterface;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\PhpRenderer;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

$container = $this->getContainer();
if (defined("TEMA_TIPO") && strtolower(TEMA_TIPO) == "twig") {
    $container['view'] = function ($container) {
        /** @var ContainerInterface $container */

        $basePath = $this->getBasePath();
        $temaDir = dirname(__DIR__) . '/templates/';
        if (defined("TEMA_DIR")) {
            $temaDir = TEMA_DIR;
        }
        $view = new Twig($temaDir, [
            'cache' => $basePath . '/cache/'
        ]);
        $router = $container->get('router');
        $uri = Uri::createFromEnvironment(new Environment($_SERVER));
        $view->addExtension(new TwigExtension($router, $uri));

        return $view;
    };
}
else {
    $container['view'] = function ($container) {
        $basePath = $this->getBasePath();
        $temaDir = $basePath . '/templates/';
        if (defined("TEMA_DIR")) {
            $temaDir = TEMA_DIR;
        }
        return new PhpRenderer($temaDir);
    };
    $container['baseView'] = function ($container) {
        return new PhpRenderer(dirname(__DIR__) . '/templates/');
    };
}