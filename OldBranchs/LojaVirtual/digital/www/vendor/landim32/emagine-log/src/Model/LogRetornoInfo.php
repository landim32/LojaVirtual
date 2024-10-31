<?php
namespace Emagine\Log\Model;

use stdClass;
use JsonSerializable;

class LogRetornoInfo implements JsonSerializable
{
    private $logs;
    private $pagina = 0;
    private $tamanho_pagina = 0;
    private $total = 0;

    /**
     * @return LogInfo[]
     */
    public function getLogs() {
        return $this->logs;
    }

    /**
     * @param LogInfo[] $value
     * @return $this
     */
    public function setLogs($value) {
        $this->logs = $value;
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
     * @return int
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * @param int $value
     * @return $this
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
     * @return $this
     */
    public static function fromJson($value) {
        $retorno = new LogRetornoInfo();
        $retorno->setPagina($value->pagina);
        $retorno->setTamanhoPagina($value->tamanho_pagina);
        $retorno->setTotal($value->total);
        $retorno->setLogs($value->logs);
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
        $retorno->logs = $this->getlogs();
        return $retorno;
    }
}