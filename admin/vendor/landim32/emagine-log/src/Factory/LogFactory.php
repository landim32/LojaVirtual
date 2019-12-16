<?php
namespace Emagine\Log\Factory;

use Emagine\Log\BLL\LogBLL;

class LogFactory {

    private static $instance;

    /**
     * @return LogBLL
     */
    public static function create() {
        if (is_null(LogFactory::$instance)) {
            if (defined("BLL_LOG")) {
                $dalClass = BLL_LOG;
                LogFactory::$instance = new $dalClass();
            }
            else {
                LogFactory::$instance = new LogBLL();
            }
        }
        return LogFactory::$instance;
    }
}