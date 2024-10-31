<?php
namespace Emagine\Login;

use Exception;
use Emagine\Base\Controls\SettingCategory;
use Emagine\Base\Controls\SettingLink;
use Emagine\Login\Model\UsuarioInfo;
use Landim32\BtMenu\BtMenu;
use Landim32\BtMenu\BtMenuSeparator;
use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;

$app = EmagineApp::getApp();

try{
    $baseUrl = $app->getBaseUrl();
    $menuRight = $app->getMenu("right");
    if (!is_null($menuRight)) {
        $usuario = UsuarioBLL::pegarUsuarioAtual();

        if (!is_null($usuario)) {
            $usuarioSetting = $app->setSetting(new SettingCategory("login", "Usuários", "fa fa-user-circle"));
            if ($usuario->temPermissao(UsuarioInfo::GERENCIAR_USUARIO)) {
                $usuarioSetting->addLink(new SettingLink("usuario-listar", "Usuários", $baseUrl . "/usuario/listar", "fa fa-fw fa-user"));
            }
            if ($usuario->temPermissao(UsuarioInfo::GERENCIAR_GRUPO)) {
                $usuarioSetting->addLink(new SettingLink("usuario-grupos", "Grupos", $baseUrl . "/grupo/listar", "fa fa-fw fa-users"));
            }
            if ($usuario->temPermissao(UsuarioInfo::VISUALIZAR_PERMISSAO)) {
                $usuarioSetting->addLink(new SettingLink("usuario-permissoes", "Permissões", $baseUrl . "/permissao/listar", "fa fa-fw fa-lock"));
            }

            if (UsuarioBLL::usaFoto()) {
                $imagem = sprintf("<img src=\"%s\" class=\"img-circle\" style=\"width: 22px; height: 22px;\" />",
                    $usuario->getThumbnailUrl(22, 22));
                $label = $imagem . " " . $usuario->getNome();
                $submenu = $menuRight->addMenu(new BtMenu($label, "#"));
            }
            else {
                $submenu = $menuRight->addMenu(new BtMenu($usuario->getNome(), "#", "fa fa-user-circle-o"));
            }
            $submenu->addSubMenu(new BtMenu("Meu Perfil", $baseUrl . "/usuario/" . $usuario->getSlug() . "/perfil", "fa fa-fw fa-user-circle"));
            $submenu->addSubMenu(new BtMenu("Alterar", $baseUrl . "/usuario/" . $usuario->getSlug() . "/alterar", "fa fa-fw fa-pencil"));
            $submenu->addSubMenu(new BtMenu("Trocar senha", $baseUrl . "/usuario/" . $usuario->getSlug() . "/trocar-senha", "fa fa-fw fa-lock"));

            $submenu->addSubMenu(new BtMenuSeparator());
            $submenu->addSubMenu(new BtMenu("Sair", $baseUrl . "/logoff", "fa fa-fw fa-ban"));
        }
        else {
            $menuRight->addMenu(new BtMenu("Entrar", $baseUrl . "/login", "fa fa-fw fa-user-circle"));
        }
    }
}
catch (Exception $e) {
    die($e->getMessage());
}
