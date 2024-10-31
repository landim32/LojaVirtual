<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 21/04/2018
 * Time: 14:07
 */

namespace Emagine\Produto\Model;

use stdClass;
use JsonSerializable;

class LojaFreteInfo implements JsonSerializable
{
    const GERENCIAR_FRETE = "gerenciar-frete";

    private $id_frete;
    private $id_loja;
    private $uf;
    private $cidade;
    private $bairro;
    private $logradouro;
    private $valor_frete;
    private $entrega;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_frete;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_frete = $value;
    }

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
     * @return string|null
     */
    public function getUf() {
        return $this->uf;
    }

    /**
     * @param string|null $value
     */
    public function setUf($value) {
        $this->uf = $value;
    }

    /**
     * @return string|null
     */
    public function getCidade() {
        return $this->cidade;
    }

    /**
     * @param string|null $value
     */
    public function setCidade($value) {
        $this->cidade = $value;
    }

    /**
     * @return string|null
     */
    public function getBairro() {
        return $this->bairro;
    }

    /**
     * @param string|null $value
     */
    public function setBairro($value) {
        $this->bairro = $value;
    }

    /**
     * @return string|null
     */
    public function getLogradouro() {
        return $this->logradouro;
    }

    /**
     * @param string|null $value
     */
    public function setLogradouro($value) {
        $this->logradouro = $value;
    }

    /**
     * @return double
     */
    public function getValorFrete() {
        return $this->valor_frete;
    }

    /**
     * @param double $value
     */
    public function setValorFrete($value) {
        $this->valor_frete = $value;
    }

    /**
     * @return string
     */
    public function getValorFreteStr() {
        return number_format($this->valor_frete, 2, ",", ".");
    }

    /**
     * @return bool
     */
    public function getEntrega() {
        return $this->entrega;
    }

    /**
     * @param bool $value
     */
    public function setEntrega($value) {
        $this->entrega = $value;
    }

    /**
     * @param stdClass $value
     * @return LojaFreteInfo
     */
    public static function fromJson($value) {
        $frete = new LojaFreteInfo();
        $frete->setId($value->id_frete);
        $frete->setIdLoja($value->id_loja);
        $frete->setUf($value->uf);
        $frete->setCidade($value->cidade);
        $frete->setBairro($value->bairro);
        $frete->setLogradouro($value->logradouro);
        $frete->setValorFrete($value->valor_frete);
        $frete->setEntrega($value->entrega);
        return $frete;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $frete = new stdClass();
        $frete->id_frete = $this->getId();
        $frete->id_loja = $this->getIdLoja();
        $frete->uf = $this->getUf();
        $frete->cidade = $this->getCidade();
        $frete->bairro = $this->getBairro();
        $frete->logradouro = $this->getLogradouro();
        $frete->valor_frete = $this->getValorFrete();
        $frete->entrega = $this->getEntrega();
        return $frete;
    }
}