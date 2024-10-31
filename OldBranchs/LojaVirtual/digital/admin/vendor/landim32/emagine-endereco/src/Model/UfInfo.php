<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:21
 * Tablename: uf
 */

namespace Emagine\Endereco\Model;

use Emagine\Endereco\BLL\CidadeBLL;
use stdClass;
use JsonSerializable;
use Emagine\Endereco\BLL\PaisBLL;
use Emagine\Endereco\Model\PaisInfo;

/**
 * Class UfInfo
 * @package Emagine\Endereco\Model
 * @tablename uf
 * @author EmagineCRUD
 */
class UfInfo implements JsonSerializable {

	private $uf;
	private $id_pais;
	private $nome;
	private $pais = null;
	private $cidades = null;

	/**
	 * @return string
	 */
	public function getUf() {
		return $this->uf;
	}

	/**
	 * @param string $value
	 */
	public function setUf($value) {
		$this->uf = $value;
	}

	/**
	 * @return int
	 */
	public function getIdPais() {
		return $this->id_pais;
	}

	/**
	 * @param int $value
	 */
	public function setIdPais($value) {
		$this->id_pais = $value;
	}

	/**
	 * @return PaisInfo
	 */
	public function getPais() {
		if (is_null($this->pais) && $this->getIdPais() > 0) {
			$bll = new PaisBLL();
			$this->pais = $bll->pegar($this->getIdPais());
		}
		return $this->pais;
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
     * @return CidadeInfo[]
     */
    public function listarCidade() {
        if (is_null($this->cidades) && !isNullOrEmpty($this->getUf())) {
            $regraCidade = new CidadeBLL();
            $this->cidades = $regraCidade->listarPorUf($this->getUf());
        }
        return $this->cidades;
    }

	/**
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->uf = $this->getUf();
		$value->id_pais = $this->getIdPais();
		$value->pais = $this->getPais();
		$value->nome = $this->getNome();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return UfInfo
	 */
	public static function fromJson($value) {
		$uf = new UfInfo();
		$uf->setUf($value->uf);
		$uf->setIdPais($value->id_pais);
		$uf->setNome($value->nome);
		return $uf;
	}

}

