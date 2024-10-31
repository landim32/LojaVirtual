<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 14/11/2018
 * Time: 11:04
 * Tablename: pedido_horario
 */

namespace Emagine\Pedido\Model;

use stdClass;
use JsonSerializable;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\Model\LojaInfo;

/**
 * Class PedidoHorarioInfo
 * @package Emagine\Pedido\Model
 * @tablename pedido_horario
 * @author EmagineCRUD
 */
class PedidoHorarioInfo implements JsonSerializable {

    const GERENCIAR_HORARIO = "gerenciar-horario";

	private $id_horario;
	private $id_loja;
	private $inicio;
	private $fim;
	private $horario;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_horario;
	}

	/**
	 * @param int $value
	 */
	public function setId($value) {
		$this->id_horario = $value;
	}

	/**
	 * @return int
	 */
	public function getIdLoja() {
		return $this->id_loja;
	}

	/**
	 * @param int $value
	 */
	public function setIdLoja($value) {
		$this->id_loja = $value;
	}

	/**
	 * @return int
	 */
	public function getInicio() {
		return $this->inicio;
	}

	/**
	 * @param int $value
	 */
	public function setInicio($value) {
		$this->inicio = $value;
	}

	/**
	 * @return int
	 */
	public function getFim() {
		return $this->fim;
	}

	/**
	 * @param int $value
	 */
	public function setFim($value) {
		$this->fim = $value;
	}

    /**
     * @return string
     */
	public function getInicioStr() {
	    $hora = floor($this->getInicio() / (60 * 60));
	    $minuto = floor(($this->getInicio() - ($hora * (60 * 60))) / 60);
	    return sprintf("%02d:%02d", $hora, $minuto);
    }

    /**
     * @return string
     */
    public function getFimStr() {
        $hora = floor($this->getFim() / (60 * 60));
        $minuto = floor(($this->getFim() - ($hora * (60 * 60))) / 60);
        return sprintf("%02d:%02d", $hora, $minuto);
    }

    /**
     * @return string
     */
	public function getHorario() {
        return sprintf("Entre %s e %s", $this->getInicioStr(), $this->getFimStr());
    }

	/**
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_horario = intval($this->getId());
		$value->id_loja = intval($this->getIdLoja());
		$value->inicio = intval($this->getInicio());
        $value->fim = intval($this->getFim());
		$value->inicio_str = $this->getInicioStr();
        $value->fim_str = $this->getFimStr();
        $value->horario = $this->getHorario();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return PedidoHorarioInfo
	 */
	public static function fromJson($value) {
		$pedido_horario = new PedidoHorarioInfo();
		$pedido_horario->setId($value->id_horario);
		$pedido_horario->setIdLoja($value->id_loja);
		$pedido_horario->setInicio($value->inicio);
		$pedido_horario->setFim($value->fim);
		return $pedido_horario;
	}

}

