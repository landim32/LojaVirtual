<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 08/02/18
 * Time: 23:35
 */

namespace Emagine\Pagamento\Model;

use stdClass;
use JsonSerializable;

class PagamentoRetornoInfo implements JsonSerializable
{
    private $id_pagamento;
    private $cod_situacao;
    private $mensagem;
    private $boleto_url;
    private $autenticacao_url;

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
     * @return int
     */
    public function getCodSituacao() {
        return $this->cod_situacao;
    }

    /**
     * @param int $value
     */
    public function setCodSituacao($value) {
        $this->cod_situacao = $value;
    }

    /**
     * @return string
     */
    public function getMensagem() {
        return $this->mensagem;
    }

    /**
     * @param string $value
     */
    public function setMensagem($value) {
        $this->mensagem = $value;
    }

    /**
     * @return string
     */
    public function getBoletoUrl() {
        return $this->boleto_url;
    }

    /**
     * @param string $value
     */
    public function setBoletoUrl($value) {
        $this->boleto_url = $value;
    }

    /**
     * @return string
     */
    public function getAutenticacaoUrl() {
        return $this->autenticacao_url;
    }

    /**
     * @param string $value
     */
    public function setAutenticacaoUrl($value) {
        $this->autenticacao_url = $value;
    }

    /**
     * @param stdClass $value
     * @return PagamentoRetornoInfo
     */
    public static function fromJson($value) {
        $retorno = new PagamentoRetornoInfo();
        $retorno->setIdPagamento($value->id_pagamento);
        $retorno->setCodSituacao($value->cod_situacao);
        $retorno->setMensagem($value->mensagem);
        $retorno->setBoletoUrl($value->url_boleto);
        $retorno->setAutenticacaoUrl($value->url_debito);
        return $retorno;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $retorno = new stdClass();
        $retorno->id_pagamento = $this->getIdPagamento();
        $retorno->cod_situacao = $this->getCodSituacao();
        $retorno->mensagem = $this->getMensagem();
        if (!isNullOrEmpty($this->getBoletoUrl())) {
            $retorno->url_boleto = $this->getBoletoUrl();
        }
        if (!isNullOrEmpty($this->getAutenticacaoUrl())) {
            $retorno->url_autenticacao = $this->getAutenticacaoUrl();
        }
        return $retorno;
    }
}