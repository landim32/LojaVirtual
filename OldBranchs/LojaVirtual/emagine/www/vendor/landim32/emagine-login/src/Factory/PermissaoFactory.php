<?php
namespace Emagine\Login\Factory;

use Emagine\Login\BLL\PermissaoBLL;

class PermissaoFactory {

    private static $instance;

    /**
     * @return PermissaoBLL
     */
    public static function create() {
        if (is_null(PermissaoFactory::$instance)) {
            if (defined("BLL_PERMISSAO")) {
                $dalClass = BLL_PERMISSAO;
                PermissaoFactory::$instance = new $dalClass();
            }
            else {
                PermissaoFactory::$instance = new PermissaoBLL();
            }
        }
        return PermissaoFactory::$instance;
    }
}