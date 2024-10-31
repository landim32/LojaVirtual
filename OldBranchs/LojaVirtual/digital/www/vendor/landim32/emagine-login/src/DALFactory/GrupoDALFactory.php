<?php
namespace Emagine\Login\DALFactory;

use Emagine\Login\DAL\GrupoDAL;
use Emagine\Login\IDAL\IGrupoDAL;

class GrupoDALFactory {

    private static $instance;

    /**
     * @return IGrupoDAL
     */
    public static function create() {
        if (is_null(GrupoDALFactory::$instance)) {
            if (defined("DAL_GRUPO")) {
                $dalClass = DAL_GRUPO;
                GrupoDALFactory::$instance = new $dalClass();
            }
            else {
                GrupoDALFactory::$instance = new GrupoDAL();
            }
        }
        return GrupoDALFactory::$instance;
    }
}