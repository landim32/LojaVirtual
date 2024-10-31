<?php

namespace Emagine\Log;

use Exception;
use Emagine\Log\Model\LogInfo;
use Emagine\Login\Factory\PermissaoFactory;
use Emagine\Login\Model\PermissaoInfo;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;

$app = EmagineApp::getApp();

try {

    $container = $app->getContainer();
    $container['log'] = function ($container) {
        return new PhpRenderer(dirname(__DIR__) . '/templates/');
    };

    $regraPermissao = PermissaoFactory::create();
    $regraPermissao->registrar(new PermissaoInfo(LogInfo::VISUALIZAR_LOG, "Visualizar Log"));
}
catch (Exception $erro) {
    die($erro->getMessage());
}