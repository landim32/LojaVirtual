<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:29
 * Tablename: pais
 */

namespace Emagine\Endereco\Model;

use Emagine\Endereco\BLL\UfBLL;
use stdClass;
use JsonSerializable;

/**
 * Class PaisInfo
 * @package Emagine\Endereco\Model
 * @tablename pais
 * @author EmagineCRUD
 */
class PaisInfo implements JsonSerializable {

	private $id_pais;
	private $nome;
	private $estados = null;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_pais;
	}

	/**
	 * @param int $value
	 */
	public function setId($value) {
		$this->id_pais = $value;
	}

	/**
	 * @return string
	 */
	public function getNome() {
		return $this->nome;
	}

	/**
	 * @param string $value
	 */
	public function setNome($value) {
		$this->nome = $value;
	}

    /**
     * @return UfInfo[]
     */
	public function listarEstado() {
	    if (is_null($this->estados) && $this->getId() > 0) {
            $regraEstado = new UfBLL();
            $this->estados = $regraEstado->listarPorPais($this->getId());
        }
        return $this->estados;
    }

	/**
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_pais = $this->getId();
		$value->nome = $this->getNome();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return PaisInfo
	 */
	public static function fromJson($value) {
		$pais = new PaisInfo();
		$pais->setId($value->id_pais);
		$pais->setNome($value->nome);
		return $pais;
	}

}

