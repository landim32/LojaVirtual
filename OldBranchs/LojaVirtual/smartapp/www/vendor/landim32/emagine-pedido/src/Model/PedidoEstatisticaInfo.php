<?php
namespace Emagine\Pedido\Model;

class PedidoEstatisticaInfo
{
    private $legenda;
    private $valor;

    /**
     * @return string
     */
    public function getLegenda() {
        return $this->legenda;
    }

    /**
     * @param string $value
     * @return PedidoEstatisticaInfo
     */
    public function setLegenda($value) {
        $this->legenda = $value;
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
     * @return PedidoEstatisticaInfo
     */
    public function setValor($value) {
        $this->valor = $value;
        return $this;
    }
}