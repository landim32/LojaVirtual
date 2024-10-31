<?php

namespace Emagine\Pedido;

use Emagine\Pedido\Model\PedidoHorarioInfo;
use Slim\Views\PhpRenderer;
use Emagine\Base\EmagineApp;
use Emagine\Login\Factory\PermissaoFactory;
use Emagine\Login\Model\PermissaoInfo;
use Emagine\Pedido\Model\PedidoInfo;


$app = EmagineApp::getApp();

$container = $app->getContainer();
$container['pedidoView'] = function ($container) {
    return new PhpRenderer(dirname(__DIR__) . '/templates/');
};

$regraPermissao = PermissaoFactory::create();
$regraPermissao->registrar(new PermissaoInfo(PedidoInfo::GERENCIAR_PEDIDO, "Gerenciar Pedidos"));
$regraPermissao->registrar(new PermissaoInfo(PedidoHorarioInfo::GERENCIAR_HORARIO, "Gerenciar Horários"));