<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 05/02/18
 * Time: 21:09
 */

namespace Emagine\Pagamento\Model;

use stdClass;
use JsonSerializable;

class PagamentoItemInfo implements JsonSerializable
{
    private $id_item;
    private $id_pagamento;
    private $descricao;
    private $valor;
    private $quantidade;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_item;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_item = $value;
    }

    /**
     * @return int
     */
    public function getIdPagamento() {
        return $this->id_pagamento;
    }

    /**
     * @param int $value
     */
    public function setIdPagamento($value) {
        $this->id_pagamento = $value;
    }

    /**
     * @return string
     */
    public function getDescricao() {
        return $this->descricao;
    }

    /**
     * @param string $value
     */
    public function setDescricao($value) {
        $this->descricao = $value;
    }

    /**
     * @return double
     */
    public function getValor() {
        return $this->valor;
    }

    /**
     * @param double $value
     */
    public function setValor($value) {
        $this->valor = $value;
    }

    /**
     * @return int
     */
    public function getQuantidade() {
        return $this->quantidade;
    }

    /**
     * @param int $value
     */
    public function setQuantidade($value) {
        $this->quantidade = $value;
    }

    /**
     * @param stdClass $value
     * @return PagamentoItemInfo
     */
    public static function fromJson($value) {
        $item = new PagamentoItemInfo();
        $item->setId($value->id_item);
        $item->setIdPagamento($value->id_pagamento);
        $item->setDescricao($value->descricao);
        $item->setValor($value->valor);
        $item->setQuantidade($value->quantidade);
        return $item;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $item = new stdClass();
        $item->id_item = $this->getId();
        $item->id_pagamento = $this->getIdPagamento();
        $item->descricao = $this->getDescricao();
        $item->valor = $this->getValor();
        $item->quantidade = $this->getQuantidade();
        return $item;
    }
}