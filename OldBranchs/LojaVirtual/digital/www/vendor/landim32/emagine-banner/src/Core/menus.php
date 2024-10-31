<?php
namespace Emagine\Banner;

use Emagine\Banner\Model\BannerInfo;
use Emagine\Banner\Model\BannerPecaInfo;
use Exception;
use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Landim32\BtMenu\BtMenu;

$app = EmagineApp::getApp();

try {
    $usuario = UsuarioBLL::pegarUsuarioAtual();
    if (!is_null($usuario)) {
        $menuMain = $app->getMenu("main");
        if (!is_null($menuMain)) {
            $menuBanner = $menuMain->addMenu(new BtMenu("Banners", "#", "fa fa-picture-o"));
            if (
                $usuario->temPermissao(BannerInfo::GERENCIAR_BANNER) ||
                $usuario->temPermissao(BannerPecaInfo::GERENCIAR_PECA)
            ) {
                $menuBanner->addSubMenu(new BtMenu("Banners", $app->getBaseUrl() . "/banner/listar", "fa fa-picture-o"));
            }
        }
    }
}
catch (Exception $e) {
    die($e->getMessage());
}