<?php

namespace Emagine\Login\Controls;

use EmagineBase\Controls\SlimMainMenu;
use EmagineBase\Controls\SlimMenu;
use Emagine\Login\BLL\UsuarioBLL;
use EmagineBase\Controls\SlimMenuSeparator;
use EmagineBase\Core\EmagineApp;

class UsuarioMenu extends SlimMainMenu
{

    /**
     * @param SlimMainMenu $menu
     */
    public static function gerar($menu) {
        $app = EmagineApp::getApp();
        $baseUrl = $app->getBaseUrl();

        $usuario = UsuarioBLL::pegarUsuarioAtual();

        if (!is_null($usuario)) {
            $imagem = sprintf("<img src=\"%s\" class=\"img-circle\" style=\"width: 22px; height: 22px;\" />", $usuario->getThumbnailUrl(22, 22) );
            $label = $imagem . " " . $usuario->getNome();
            $submenu = $menu->addMenu(new SlimMenu($label, "#"));
            $submenu->addSubMenu(new SlimMenu("Meu Perfil", $baseUrl . "/#usuario/" . $usuario->getSlug() . "/perfil", "fa fa-fw fa-user-circle"));
            $submenu->addSubMenu(new SlimMenu("Alterar", $baseUrl . "/#usuario/" . $usuario->getSlug() . "/alterar", "fa fa-fw fa-pencil"));
            $submenu->addSubMenu(new SlimMenu("Trocar senha", $baseUrl . "/#usuario/" . $usuario->getSlug() . "/trocar-senha", "fa fa-fw fa-lock"));
            $submenu->addSubMenu(new SlimMenuSeparator());
            $submenu->addSubMenu(new SlimMenu("Listar usuários", $baseUrl . "/#usuario/listar", "fa fa-fw fa-user"));
            $submenu->addSubMenu(new SlimMenu("Listar Grupos", $baseUrl . "/#grupo/listar", "fa fa-fw fa-users"));
            $submenu->addSubMenu(new SlimMenu("Listar permissões", $baseUrl . "/#permissao/listar", "fa fa-fw fa-lock"));
            $submenu->addSubMenu(new SlimMenuSeparator());
            $submenu->addSubMenu(new SlimMenu("Sair", $baseUrl . "/logoff", "fa fa-fw fa-ban"));
        }
        else {
            $menu->addMenu(new SlimMenu("Entrar", $baseUrl . "/login", "fa fa-fw fa-user-circle"));
        }
    }

    /**
     * @return UsuarioMenu
     */
    public static function create() {
        $menu = new bak(SlimMenu::NAVBAR, "navbar-right");
        bak::gerar($menu);
        return $menu;
    }

}