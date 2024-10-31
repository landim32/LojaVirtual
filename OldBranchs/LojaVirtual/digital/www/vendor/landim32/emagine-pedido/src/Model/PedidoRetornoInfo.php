<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 11/04/2018
 * Time: 08:57
 */

namespace Emagine\Pedido\Model;

use stdClass;
use JsonSerializable;

class PedidoRetornoInfo implements JsonSerializable
{
    private $id_pedido;
    private $cod_situacao;
    private $latitude;
    private $longitude;
    private $distancia;
    private $distancia_str;
    private $tempo;
    private $tempo_str;
    private $polyline;
    private $mensagem;

    /**
     * @return int
     */
    public function getIdPedido() {
        return $this->id_pedido;
    }

    /**
     * @param int $value
     */
    public function setIdPedido($value) {
        $this->id_pedido = $value;
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
     * @return float
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * @param float $value
     */
    public function setLatitude($value) {
        $this->latitude = $value;
    }

    /**
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * @param float $value
     */
    public function setLongitude($value) {
        $this->longitude = $value;
    }

    /**
     * @return int
     */
    public function getDistancia() {
        return $this->distancia;
    }

    /**
     * @param int $value
     */
    public function setDistancia($value) {
        $this->distancia = $value;
    }

    /**
     * @return string
     */
    public function getDistanciaStr() {
        return $this->distancia_str;
    }

    /**
     * @param string $value
     */
    public function setDistanciaStr($value) {
        $this->distancia_str = $value;
    }

    /**
     * @return int
     */
    public function getTempo() {
        return $this->tempo;
    }

    /**
     * @param int $value
     */
    public function setTempo($value) {
        $this->tempo = $value;
    }

    /**
     * @return string
     */
    public function getTempoStr() {
        return $this->tempo_str;
    }

    /**
     * @param string $value
     */
    public function setTempoStr($value) {
        $this->tempo_str = $value;
    }

    /**
     * @return string
     */
    public function getPolyline() {
        return $this->polyline;
    }

    /**
     * @param string $value
     */
    public function setPolyline($value) {
        $this->polyline = $value;
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
    public function getPosicaoStr() {
        $str = "";
        $str .= number_format($this->getLatitude(), 5, ".", "");
        $str .= ",";
        $str .= number_format($this->getLongitude(), 5, ".", "");
        return $str;
    }

    /**
     * @param stdClass $value
     * @return PedidoRetornoInfo
     */
    public static function fromJson($value) {
        $retorno = new PedidoRetornoInfo();
        $retorno->setIdPedido($value->id_pedido);
        $retorno->setCodSituacao($value->cod_situacao);
        $retorno->setLatitude($value->latitude);
        $retorno->setLongitude($value->longitude);
        $retorno->setDistancia($value->distancia);
        $retorno->setDistanciaStr($value->distancia_str);
        $retorno->setTempo($value->tempo);
        $retorno->setTempoStr($value->tempo_str);
        $retorno->setPolyline($value->polyline);
        $retorno->setMensagem($value->mensagem);
        return $retorno;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $retorno = new stdClass();
        $retorno->id_pedido = $this->getIdPedido();
        $retorno->cod_situacao = $this->getCodSituacao();
        $retorno->latitude = $this->getLatitude();
        $retorno->longitude = $this->getLongitude();
        $retorno->distancia = $this->getDistancia();
        $retorno->distancia_str = $this->getDistanciaStr();
        $retorno->tempo = $this->getTempo();
        $retorno->tempo_str = $this->getTempoStr();
        $retorno->polyline = $this->getPolyline();
        $retorno->mensagem = $this->getMensagem();
        return $retorno;
    }
}