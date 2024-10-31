<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 28/01/2017
 * Time: 01:35
 */

namespace Emagine\Social\Model;


class RelacionamentoInfo
{
    const AMIGO = 1;
    const PARCEIRO = 2;
    const BLOQUEADO = 3;

    private $id_origem;
    private $id_destino;
    private $tipo;
    private $seguir;
    private $data_inclusao;

    /**
     * @return int
     */
    public function getIdOrigem() {
        return $this->id_origem;
    }

    /**
     * @param int $value
     */
    public function setIdOrigem($value) {
        $this->id_origem = $value;
    }

    /**
     * @return int
     */
    public function getIdDestino() {
        return $this->id_destino;
    }

    /**
     * @param int $value
     */
    public function setIdDestino($value) {
        $this->id_destino = $value;
    }

    /**
     * @return int
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * @param int $value
     */
    public function setTipo($value) {
        $this->tipo = $value;
    }

    /**
     * @return bool
     */
    public function getSeguir() {
        return $this->seguir;
    }

    /**
     * @param bool $value
     */
    public function setSeguir($value) {
        $this->seguir = $value;
    }

    /**
     * @return string
     */
    public function getDataInclusao() {
        return $this->data_inclusao;
    }

    /**
     * @param string $value
     */
    public function setDataInclusao($value) {
        $this->data_inclusao = $value;
    }
}