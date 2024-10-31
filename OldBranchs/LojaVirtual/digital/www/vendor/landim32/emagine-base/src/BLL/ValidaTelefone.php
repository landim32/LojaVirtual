<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 13/04/2018
 * Time: 20:14
 */

namespace Emagine\Base\BLL;

/**
 * Class ValidaTelefone
 * @package Emagine\Base\BLL
 */
class ValidaTelefone
{
    private $telefone;
    private $formato;
    private $formatoSemDDD;

    /**
     * ValidaTelefone constructor.
     * @param string $telefone
     * @param string $formato
     * @param string $formatoSemDDD
     */
    public function __construct($telefone, $formato = '(%s)%s-%s', $formatoSemDDD = '%s-%s')
    {
        $this->telefone = $telefone;
        $this->formato = $formato;
        $this->formatoSemDDD = $formatoSemDDD;
    }

    /**
     * @return string
     */
    public function formatar() {
        $str = preg_replace('#[^0-9]#','',strip_tags($this->telefone));
        $dados = array();
        switch (strlen($str)) {
            case 8:
                $dados['ddd'] = null;
                $dados['prefixo'] = substr($str, 0, 4);
                $dados['sufixo'] = substr($str, 4, 4);
                break;
            case 9:
                $dados['ddd'] = null;
                $dados['prefixo'] = substr($str, 0, 4);
                $dados['sufixo'] = substr($str, 4, 5);
                break;
            case 10:
                $dados['ddd'] = substr($str, 0, 2);
                $dados['prefixo'] = substr($str, 2, 4);
                $dados['sufixo'] = substr($str, 6, 4);
                break;
            case 11:
                $dados['ddd'] = substr($str, 0, 2);
                $dados['prefixo'] = substr($str, 2, 5);
                $dados['sufixo'] = substr($str, 7, 4);
                break;
            default:
                $dados['ddd'] = substr($str, 0, 2);
                $dados['prefixo'] = substr($str, 2, 5);
                $dados['sufixo'] = substr($str, 7);
                break;
        }
        if (!is_null($dados['ddd'])) {
            $str = sprintf($this->formato, $dados['ddd'], $dados['prefixo'], $dados['sufixo']);
        }
        else {
            $str = sprintf($this->formatoSemDDD, $dados['prefixo'], $dados['sufixo']);
        }
        return $str;
    }
}