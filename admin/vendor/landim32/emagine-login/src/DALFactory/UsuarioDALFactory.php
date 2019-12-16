<?php
namespace Emagine\Login\DALFactory;

use Emagine\Login\DAL\UsuarioDAL;
use Emagine\Login\IDAL\IUsuarioDAL;

class UsuarioDALFactory {

    private static $instance;

    /**
     * @return IUsuarioDAL
     */
    public static function create() {
        if (is_null(UsuarioDALFactory::$instance)) {
            if (defined("DAL_USUARIO")) {
                $dalClass = DAL_USUARIO;
                UsuarioDALFactory::$instance = new $dalClass();
            }
            else {
                UsuarioDALFactory::$instance = new UsuarioDAL();
            }
        }
        return UsuarioDALFactory::$instance;
    }
}