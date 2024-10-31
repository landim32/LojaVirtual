<?php

namespace Emagine\Produto;

use Emagine\Login\Factory\PermissaoFactory;
use Emagine\Login\Model\PermissaoInfo;
use Emagine\Produto\Model\CategoriaInfo;
use Emagine\Produto\Model\LojaFreteInfo;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;
use Emagine\Produto\Model\SeguimentoInfo;
use Emagine\Produto\Model\UnidadeInfo;
use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;

$app = EmagineApp::getApp();

try {
    $container = $app->getContainer();
    $container['lojaView'] = function ($container) {
        return new PhpRenderer(dirname(__DIR__) . '/templates/');
    };

    $regraPermissao = PermissaoFactory::create();
    $regraPermissao->registrar(new PermissaoInfo(CategoriaInfo::GERENCIAR_CATEGORIA, "Gerenciar Categoria"));
    $regraPermissao->registrar(new PermissaoInfo(LojaFreteInfo::GERENCIAR_FRETE, "Gerenciar Fretes"));
    $regraPermissao->registrar(new PermissaoInfo(LojaInfo::GERENCIAR_LOJA, "Gerenciar Loja"));
    $regraPermissao->registrar(new PermissaoInfo(LojaInfo::CONFIGURA_LOJA, "Configurar Loja"));
    $regraPermissao->registrar(new PermissaoInfo(ProdutoInfo::GERENCIAR_PRODUTO, "Gerenciar Produtos"));
    $regraPermissao->registrar(new PermissaoInfo(SeguimentoInfo::GERENCIAR_SEGUIMENTO, "Gerenciar Seguimentos"));
    $regraPermissao->registrar(new PermissaoInfo(UnidadeInfo::GERENCIAR_UNIDADE, "Gerenciar Unidades"));
}
catch (Exception $e) {
    die($e->getMessage());
}