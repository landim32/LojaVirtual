<?php
namespace Emagine\Log\Test;

use Emagine\Log\BLL\LogBLL;
use Emagine\Log\Factory\LogFactory;
use Emagine\Log\Model\LogInfo;
use Emagine\Login\Model\UsuarioInfo;
use joshtronic\LoremIpsum;

class LogUtils {
    /**
     * @param UsuarioInfo|null $usuario
     * @return LogInfo
     */
    public static function gerarLog(UsuarioInfo $usuario = null) {
        $lipsum = new LoremIpsum();

        $regraLog = LogFactory::create();

        $log = new LogInfo();

        $tipos = array_keys($regraLog->listarTipo());
        shuffle($tipos);
        $cod_tipo = array_values($tipos)[0];

        $log->setCodTipo($cod_tipo);
        $titulo = $lipsum->words(5, false, true);
        shuffle($titulo);
        $log->setTitulo(implode(" ", $titulo));
        $log->setDescricao($lipsum->paragraphs(3, true));
        if (!is_null($usuario)) {
            $log->setNome($usuario->getNome());
        }
        return $log;
    }

}