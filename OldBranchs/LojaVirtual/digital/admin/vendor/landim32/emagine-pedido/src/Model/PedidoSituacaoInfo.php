<?php
namespace Emagine\Pedido\Model;

use stdClass;
use JsonSerializable;

class PedidoSituacaoInfo implements JsonSerializable
{
    private $id_situacao;
    private $id_pedido;
    private $id_usuario;
    private $cod_situacao;
    private $data;
    private $mensagem;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_situacao;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setId($value) {
        $this->id_situacao = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdPedido() {
        return $this->id_pedido;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setIdPedido($value) {
        $this->id_pedido = $value;
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
     * @return $this
     */
    public function setIdUsuario($value) {
        $this->id_usuario = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodSituacao() {
        return $this->cod_situacao;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setCodSituacao($value) {
        $this->cod_situacao = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setData($value) {
        $this->data = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataStr() {
        return date("d/m/Y H:i", strtotime($this->getData()));
    }

    /**
     * @return string
     */
    public function getMensagem() {
        return $this->mensagem;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMensagem($value) {
        $this->mensagem = $value;
        return $this;
    }

    /**
     * @param stdClass $value
     * @return PedidoSituacaoInfo
     */
    public static function fromJson($value) {
        $retorno = new PedidoSituacaoInfo();
        $retorno->setId($value->id_situacao);
        $retorno->setIdPedido($value->id_pedido);
        $retorno->setIdUsuario($value->id_usuario);
        $retorno->setCodSituacao($value->cod_situacao);
        $retorno->setData($value->data);
        $retorno->setMensagem($value->mensagem);
        return $retorno;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $retorno = new stdClass();
        $retorno->id_situacao = $this->getId();
        $retorno->id_pedido = $this->getIdPedido();
        $retorno->id_usuario = $this->getIdUsuario();
        $retorno->cod_situacao = $this->getCodSituacao();
        $retorno->data = $this->getData();
        $retorno->mensagem = $this->getMensagem();
        return $retorno;
    }
}