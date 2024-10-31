<?php
namespace Emagine\Log\Model;

use stdClass;
use JsonSerializable;

class LogFiltroInfo implements JsonSerializable
{
    private $id_usuario = null;
    private $id_loja = null;
    private $cod_tipo = null;
    private $data_inicio = null;
    private $data_fim = null;
    private $pagina = 0;
    private $tamanho_pagina = 0;

    /**
     * @return int|null
     */
    public function getIdUsuario() {
        return $this->id_usuario;
    }

    /**
     * @param int|null $value
     */
    public function setIdUsuario($value) {
        $this->id_usuario = $value;
    }

    /**
     * @return int|null
     */
    public function getIdLoja() {
        return $this->id_loja;
    }

    /**
     * @param int|null $value
     */
    public function setIdLoja($value) {
        $this->id_loja = $value;
    }

    /**
     * @return int|null
     */
    public function getCodTipo() {
        return $this->cod_tipo;
    }

    /**
     * @param int|null $value
     */
    public function setCodTipo($value) {
        $this->cod_tipo = $value;
    }

    /**
     * @return string|null
     */
    public function getDataInicio() {
        return $this->data_inicio;
    }

    /**
     * @param string|null $value
     */
    public function setDataInicio($value) {
        $this->data_inicio = $value;
    }

    /**
     * @return string
     */
    public function getDataInicioStr() {
        $data = strtotime($this->getDataInicio());
        if ($data > 0) {
            return date("d/m/Y", $data);
        }
        return "";
    }

    /**
     * @return string|null
     */
    public function getDataFim() {
        return $this->data_fim;
    }

    /**
     * @param string|null $value
     */
    public function setDataFim($value) {
        $this->data_fim = $value;
    }

    /**
     * @return string
     */
    public function getDataFimStr() {
        $data = strtotime($this->getDataFim());
        if ($data > 0) {
            return date("d/m/Y", $data);
        }
        return "";
    }

    /**
     * @return int
     */
    public function getPagina() {
        return $this->pagina;
    }

    /**
     * @param int $value
     * @return $this
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
     * @return $this
     */
    public function setTamanhoPagina($value) {
        $this->tamanho_pagina = $value;
        return $this;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $log = new stdClass();
        $log->id_usuario = $this->getIdUsuario();
        $log->id_loja = $this->getIdLoja();
        $log->cod_tipo = $this->getCodTipo();
        $log->data_inicio = $this->getDataInicio();
        $log->data_fim = $this->getDataFim();
        $log->pagina = $this->getPagina();
        $log->tamanho_pagina = $this->getTamanhoPagina();
        return $log;
    }

    /**
     * @param stdClass $value
     * @return LogFiltroInfo
     */
    public static function fromJson($value) {
        $log = new LogFiltroInfo();
        $log->setIdUsuario($value->id_usuario);
        $log->setIdLoja($value->id_loja);
        $log->setCodTipo($value->cod_tipo);
        $log->setDataInicio($value->data_inicio);
        $log->setDataFim($value->data_fim);
        $log->setPagina($value->pagina);
        $log->setTamanhoPagina($value->tamanho_pagina);
        return $log;
    }
}