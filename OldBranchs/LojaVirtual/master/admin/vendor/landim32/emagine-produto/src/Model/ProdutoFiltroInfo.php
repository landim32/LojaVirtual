<?php
namespace Emagine\Produto\Model;

use stdClass;
use JsonSerializable;

/**
 * Class ProdutoFiltroInfo
 * @package Emagine\Produto\Model
 */
class ProdutoFiltroInfo implements JsonSerializable
{
    private $id_loja = null;
    private $id_usuario = null;
    private $id_categoria = null;
    private $destaque = null;
    private $cod_situacao = null;
    private $apenas_estoque = null;
    private $apenas_promocao = null;
    private $palavra_chave = null;
    private $exibe_origem = null;
    private $pagina = 0;
    private $tamanho_pagina = 0;
    private $condicao = true;

    /**
     * @return int|null
     */
    public function getIdLoja() {
        return $this->id_loja;
    }

    /**
     * @param int|null $value
     * @return ProdutoFiltroInfo
     */
    public function setIdLoja($value) {
        $this->id_loja = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdUsuario() {
        return $this->id_usuario;
    }

    /**
     * @param int|null $value
     * @return ProdutoFiltroInfo
     */
    public function setIdUsuario($value) {
        $this->id_usuario = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdCategoria() {
        return $this->id_categoria;
    }

    /**
     * @param int|null $value
     * @return ProdutoFiltroInfo
     */
    public function setIdCategoria($value) {
        $this->id_categoria = $value;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getDestaque() {
        return $this->destaque;
    }

    /**
     * @param bool|null $value
     * @return ProdutoFiltroInfo
     */
    public function setDestaque($value) {
        $this->destaque = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCodSituacao() {
        return $this->cod_situacao;
    }

    /**
     * @param int|null $value
     * @return ProdutoFiltroInfo
     */
    public function setCodSituacao($value) {
        $this->cod_situacao = $value;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getApenasEstoque() {
        return $this->apenas_estoque;
    }

    /**
     * @param bool|null $value
     * @return ProdutoFiltroInfo
     */
    public function setApenasEstoque($value) {
        $this->apenas_estoque = $value;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getApenasPromocao() {
        return $this->apenas_promocao;
    }

    /**
     * @param bool|null $value
     * @return ProdutoFiltroInfo
     */
    public function setApenasPromocao($value) {
        $this->apenas_promocao = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPalavraChave() {
        return $this->palavra_chave;
    }

    /**
     * @param string|null $value
     * @return ProdutoFiltroInfo
     */
    public function setPalavraChave($value) {
        $this->palavra_chave = $value;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getExibeOrigem() {
        return $this->exibe_origem;
    }

    /**
     * @param bool|null $value
     * @return ProdutoFiltroInfo
     */
    public function setExibeOrigem($value) {
        $this->exibe_origem = $value;
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
     * @return ProdutoFiltroInfo
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
     * @return ProdutoFiltroInfo
     */
    public function setTamanhoPagina($value) {
        $this->tamanho_pagina = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCondicao() {
        return $this->condicao;
    }

    /**
     * @param bool $value
     * @return ProdutoFiltroInfo
     */
    public function setCondicao($value) {
        $this->condicao = $value;
        return $this;
    }

    /**
     * @param stdClass $value
     * @return ProdutoFiltroInfo
     */
    public static function fromJson($value) {
        $filtro = new ProdutoFiltroInfo();
        $filtro->setIdLoja($value->id_loja);
        $filtro->setIdUsuario($value->id_usuario);
        $filtro->setIdCategoria($value->id_categoria);
        $filtro->setDestaque($value->destaque);
        $filtro->setCodSituacao($value->cod_situacao);
        $filtro->setApenasEstoque($value->apenas_estoque);
        $filtro->setApenasPromocao($value->apenas_promocao);
        $filtro->setPalavraChave($value->palavra_chave);
        $filtro->setExibeOrigem($value->exibe_origem);
        $filtro->setPagina($value->pagina);
        $filtro->setTamanhoPagina($value->tamanho_pagina);
        $filtro->setCondicao($value->condicao);
        return $filtro;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $filtro = new stdClass();
        $filtro->id_loja = $this->getIdLoja();
        $filtro->id_usuario = $this->getIdUsuario();
        $filtro->id_categoria = $this->getIdCategoria();
        $filtro->destaque = $this->getDestaque();
        $filtro->cod_situacao = $this->getCodSituacao();
        $filtro->apenas_estoque = $this->getApenasEstoque();
        $filtro->apenas_promocao = $this->getApenasPromocao();
        $filtro->palavra_chave = $this->getPalavraChave();
        $filtro->exibe_origem = $this->getExibeOrigem();
        $filtro->pagina = $this->getPagina();
        $filtro->tamanho_pagina = $this->getTamanhoPagina();
        $filtro->condicao = $this->getCondicao();
        return $filtro;
    }
}