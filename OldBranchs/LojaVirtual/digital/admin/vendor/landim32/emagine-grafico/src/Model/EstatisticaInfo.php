<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 17/05/2018
 * Time: 10:12
 */

namespace Emagine\Grafico\Model;


class EstatisticaInfo
{
    private $legenda;
    private $valor;

    /**
     * EstatisticaInfo constructor.
     * @param string $legenda
     * @param string $valor
     */
    public function __construct($legenda = "", $valor = "")
    {
        $this->legenda = $legenda;
        $this->valor = $valor;
    }

    /**
     * @return string
     */
    public function getLegenda() {
        return $this->legenda;
    }

    /**
     * @param string $value
     */
    public function setLegenda($value) {
        $this->legenda = $value;
    }

    /**
     * @return string
     */
    public function getValor() {
        return $this->valor;
    }

    /**
     * @param string $valor
     */
    public function setValor($valor) {
        $this->valor = $valor;
    }
}