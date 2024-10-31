<?php
namespace Emagine\Login\Factory;

use Emagine\Login\BLL\GrupoBLL;

class GrupoFactory {

    private static $instance;

    /**
     * @return GrupoBLL
     */
    public static function create() {
        if (is_null(GrupoFactory::$instance)) {
            if (defined("BLL_GRUPO")) {
                $dalClass = BLL_GRUPO;
                GrupoFactory::$instance = new $dalClass();
            }
            else {
                GrupoFactory::$instance = new GrupoBLL();
            }
        }
        return GrupoFactory::$instance;
    }
}