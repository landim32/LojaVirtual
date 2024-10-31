<?php
namespace Emagine\Login\Factory;

use Emagine\Login\BLL\UsuarioPerfilBLL;

class UsuarioPerfilFactory {

    private static $instance;

    /**
     * @return UsuarioPerfilBLL
     */
    public static function create() {
        if (is_null(UsuarioPerfilFactory::$instance)) {
            if (defined("BLL_USUARIO_PERFIL")) {
                $bllClass = BLL_USUARIO_PERFIL;
                UsuarioPerfilFactory::$instance = new $bllClass();
            }
            else {
                UsuarioPerfilFactory::$instance = new UsuarioPerfilBLL();
            }
        }
        return UsuarioPerfilFactory::$instance;
    }
}