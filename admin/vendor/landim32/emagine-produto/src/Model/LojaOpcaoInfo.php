<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 03/04/2018
 * Time: 11:35
 */

namespace Emagine\Produto\Model;

use stdClass;
use JsonSerializable;

class LojaOpcaoInfo implements JsonSerializable
{
    private $id_loja;
    private $chave;
    private $valor;

    /**
     * @return int
     */
    public function getIdLoja() {
        return $this->id_loja;
    }

    /**
     * @param int $value
     */
    public function setIdLoja($value) {
        $this->id_loja = $value;
    }

    /**
     * @return string
     */
    public function getChave() {
        return $this->chave;
    }

    /**
     * @param string $value
     */
    public function setChave($value) {
        $this->chave = $value;
    }

    /**
     * @return string
     */
    public function getValor() {
        return $this->valor;
    }

    /**
     * @param string $value
     */
    public function setValor($value) {
        $this->valor = $value;
    }

    /**
     * @param stdClass $value
     * @return LojaOpcaoInfo
     */
    public static function fromJson($value) {
        $opcao = new LojaOpcaoInfo();
        $opcao->setIdLoja($value->id_loja);
        $opcao->setChave($value->chave);
        $opcao->setValor($value->valor);
        return $opcao;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $opcao = new stdClass();
        $opcao->id_loja = $this->getIdLoja();
        $opcao->chave = $this->getChave();
        $opcao->valor = $this->getValor();
        return $opcao;
    }
}