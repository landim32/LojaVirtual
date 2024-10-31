<?php
namespace Emagine\Social\DALFactory;

use Emagine\Social\DAL\MensagemDAL;

class MensagemDALFactory
{
    private static $instance = null;

    /**
     * @return MensagemDAL
     */
    public static function create() {
        if (is_null(MensagemDALFactory::$instance)) {
            if (defined("MENSAGEM_DAL")) {
                $dalClass = MENSAGEM_DAL;
                MensagemDALFactory::$instance = new $dalClass();
            }
            else {
                MensagemDALFactory::$instance = new MensagemDAL();
            }
        }
        return MensagemDALFactory::$instance;
    }
}