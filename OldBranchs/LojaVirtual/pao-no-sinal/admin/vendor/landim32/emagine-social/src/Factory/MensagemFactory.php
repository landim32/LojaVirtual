<?php
namespace Emagine\Social\Factory;

use Emagine\Social\BLL\MensagemBLL;

class MensagemFactory
{
    private static $instance = null;

    /**
     * @return MensagemBLL
     */
    public static function create() {
        if (is_null(MensagemFactory::$instance)) {
            if (defined("MENSAGEM_BLL")) {
                $dalClass = MENSAGEM_BLL;
                MensagemFactory::$instance = new $dalClass();
            }
            else {
                MensagemFactory::$instance = new MensagemBLL();
            }
        }
        return MensagemFactory::$instance;
    }
}