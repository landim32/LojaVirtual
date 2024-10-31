<?php
namespace Emagine\Banner;

use Emagine\Banner\Model\BannerInfo;
use Emagine\Banner\Model\BannerPecaInfo;
use Emagine\Login\Factory\PermissaoFactory;
use Emagine\Login\Model\PermissaoInfo;
use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;

$app = EmagineApp::getApp();

try {
    $container = $app->getContainer();
    if (!$container->has('banner')) {
        $container['banner'] = function ($container) {
            return new PhpRenderer(dirname(__DIR__) . '/templates/');
        };
    }
    $regraPermissao = PermissaoFactory::create();
    $regraPermissao->registrar(new PermissaoInfo(BannerInfo::GERENCIAR_BANNER, "Gerenciar Banners"));
    $regraPermissao->registrar(new PermissaoInfo(BannerPecaInfo::GERENCIAR_PECA, "Gerenciar Peças"));
}
catch (Exception $e) {
    die($e->getMessage());
}