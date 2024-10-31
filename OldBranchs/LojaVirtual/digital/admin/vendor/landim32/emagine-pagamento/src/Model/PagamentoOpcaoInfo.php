<?php
namespace Emagine\Pagamento\Model;

use stdClass;
use JsonSerializable;

class PagamentoOpcaoInfo implements JsonSerializable
{
    private $id_pagamento;
    private $chave;
    private $valor;

    /**
     * PagamentoOpcaoInfo constructor.
     * @param string $chave
     * @param string $valor
     */
    /*
    public function __construct($chave = null, $valor = null)
    {
        $this->chave = $chave;
        $this->valor = $valor;
    }
    */

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
    public function getChave() {
        return $this->chave;
    }

    /**
     * @param string $value
     * @return PagamentoOpcaoInfo
     */
    public function setChave($value) {
        $this->chave = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getValor() {
        return $this->valor;
    }

    /**
     * @param string $value
     * @return PagamentoOpcaoInfo
     */
    public function setValor($value) {
        $this->valor = $value;
        return $this;
    }

    /**
     * @param stdClass $value
     * @return PagamentoOpcaoInfo
     */
    public static function fromJson($value) {
        $opcao = new PagamentoOpcaoInfo();
        $opcao->setIdPagamento($value->id_pagamento);
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
        $opcao->id_pagamento = $this->getIdPagamento();
        $opcao->chave = $this->getChave();
        $opcao->valor = $this->getValor();
        return $opcao;
    }
}