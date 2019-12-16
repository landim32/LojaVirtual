<?php

namespace Emagine\Social;

use Emagine\Base\EmagineApp;
use Emagine\Social\BLL\UsuarioSocialBLL;
use Landim32\BtMenu\BtMenu;

$app = EmagineApp::getApp();
$app->addModal(dirname(__DIR__) . "/templates/mensagem-modal.php");

if (UsuarioSocialBLL::getSocialUsaRoute() == true) {

    $menuMain = $app->getMenu("main");
    if (!is_null($menuMain)) {
        $menuSocial = $menuMain->addMenu(new BtMenu("Social", "#", "fa fa-fw fa-share-alt"));
        $menuSocial->addSubMenu(new BtMenu("Buscar por parceiros", $app->getBaseUrl() . "#social/busca", "fa fa-fw fa-search"));
    }

    $menuRight = $app->getMenu("right");
    if (!is_null($menuRight)) {
        $menuMensagem = $menuRight->insertMenu(new BtMenu("", "#", "fa fa-fw fa-comments"));
        $menuMensagem->addSubMenu(new BtMenu("Ver todas as mensagens", $app->getBaseUrl() . "#social/mensagens", "fa fa-fw fa-comments"));
        $menuEnviar = new BtMenu("Enviar mensagem", "#mensagemModal", "fa fa-fw fa-comment");
        $menuEnviar->setModal(true);
        $menuMensagem->addSubMenu($menuEnviar);
    }
}