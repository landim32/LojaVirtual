<?php
namespace Emagine\Produto\Model;

use stdClass;
use JsonSerializable;

class ProdutoRetornoInfo implements JsonSerializable
{
    private $produtos;
    private $pagina = 0;
    private $tamanho_pagina = 0;
    private $total = 0;

    /**
     * @return ProdutoInfo[]
     */
    public function getProdutos() {
        return $this->produtos;
    }

    /**
     * @param ProdutoInfo[] $value
     * @return ProdutoRetornoInfo
     */
    public function setProdutos($value) {
        $this->produtos = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getPagina() {
        return $this->pagina;
    }

    /**
     * @param int $value
     * @return ProdutoRetornoInfo
     */
    public function setPagina($value) {
        $this->pagina = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getTamanhoPagina() {
        return $this->tamanho_pagina;
    }

    /**
     * @param int $value
     * @return ProdutoRetornoInfo
     */
    public function setTamanhoPagina($value) {
        $this->tamanho_pagina = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * @param int $value
     * @return ProdutoRetornoInfo
     */
    public function setTotal($value) {
        $this->total = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantidadePagina() {
        return intval(ceil($this->getTotal() / $this->getTamanhoPagina()));
    }

    /**
     * @param stdClass $value
     * @return ProdutoRetornoInfo
     */
    public static function fromJson($value) {
        $retorno = new ProdutoRetornoInfo();
        $retorno->setPagina($value->pagina);
        $retorno->setTamanhoPagina($value->tamanho_pagina);
        $retorno->setTotal($value->total);
        $retorno->setProdutos($value->produtos);
        return $retorno;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $retorno = new stdClass();
        $retorno->pagina = $this->getPagina();
        $retorno->tamanho_pagina = $this->getTamanhoPagina();
        $retorno->total = $this->getTotal();
        $retorno->produtos = $this->getProdutos();
        return $retorno;
    }
}