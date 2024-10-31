<?php
namespace Emagine\Login\Factory;

use Emagine\Login\BLL\UsuarioBLL;

class UsuarioFactory {

    private static $instance;

    /**
     * @return UsuarioBLL
     */
    public static function create() {
        if (is_null(UsuarioFactory::$instance)) {
            if (defined("BLL_USUARIO")) {
                $dalClass = BLL_USUARIO;
                UsuarioFactory::$instance = new $dalClass();
            }
            else {
                UsuarioFactory::$instance = new UsuarioBLL();
            }
        }
        return UsuarioFactory::$instance;
    }
}