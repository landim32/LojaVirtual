<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 11/04/2018
 * Time: 09:29
 */

namespace Emagine\Pedido\Model;

use stdClass;
use JsonSerializable;

class PedidoEnvioInfo implements JsonSerializable
{
    private $id_pedido;
    private $latitude;
    private $longitude;

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
     * @return PedidoEnvioInfo
     */
    public static function fromJson($value) {
        $envio = new PedidoEnvioInfo();
        $envio->setIdPedido($value->id_pedido);
        $envio->setLatitude($value->latitude);
        $envio->setLongitude($value->longitude);
        return $envio;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $envio = new stdClass();
        $envio->id_pedido = $this->getIdPedido();
        $envio->latitude = $this->getLatitude();
        $envio->longitude = $this->getLongitude();
        return $envio;
    }
}