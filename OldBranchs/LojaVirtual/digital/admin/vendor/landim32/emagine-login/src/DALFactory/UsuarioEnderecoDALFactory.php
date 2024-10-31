<?php
namespace Emagine\Login\DALFactory;

use Emagine\Login\DAL\UsuarioEnderecoDAL;
use Emagine\Login\IDAL\IUsuarioEnderecoDAL;

class UsuarioEnderecoDALFactory {

    private static $instance;

    /**
     * @return IUsuarioEnderecoDAL
     */
    public static function create() {
        if (is_null(UsuarioEnderecoDALFactory::$instance)) {
            if (defined("DAL_USUARIO_ENDERECO")) {
                $dalClass = DAL_USUARIO_ENDERECO;
                UsuarioEnderecoDALFactory::$instance = new $dalClass();
            }
            else {
                UsuarioEnderecoDALFactory::$instance = new UsuarioEnderecoDAL();
            }
        }
        return UsuarioEnderecoDALFactory::$instance;
    }
}