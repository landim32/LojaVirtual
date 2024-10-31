<?php
namespace Emagine\Login\DALFactory;

use Emagine\Login\DAL\UsuarioPreferenciaDAL;
use Emagine\Login\IDAL\IUsuarioPreferenciaDAL;

class UsuarioPreferenciaDALFactory {

    private static $instance;

    /**
     * @return IUsuarioPreferenciaDAL
     */
    public static function create() {
        if (is_null(UsuarioPreferenciaDALFactory::$instance)) {
            if (defined("DAL_USUARIO_PREFERENCIA")) {
                $dalClass = DAL_USUARIO_PREFERENCIA;
                UsuarioPreferenciaDALFactory::$instance = new $dalClass();
            }
            else {
                UsuarioPreferenciaDALFactory::$instance = new UsuarioPreferenciaDAL();
            }
        }
        return UsuarioPreferenciaDALFactory::$instance;
    }
}