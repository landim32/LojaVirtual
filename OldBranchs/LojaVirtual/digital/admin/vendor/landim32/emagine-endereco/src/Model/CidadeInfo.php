<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:23
 * Tablename: cidade
 */

namespace Emagine\Endereco\Model;

use stdClass;
use JsonSerializable;
use Exception;
use Emagine\Endereco\BLL\BairroBLL;
use Emagine\Endereco\BLL\UfBLL;

/**
 * Class CidadeInfo
 * @package Emagine\Endereco\Model
 * @tablename cidade
 * @author EmagineCRUD
 */
class CidadeInfo implements JsonSerializable {

	private $id_cidade;
	private $uf;
	private $nome;
	private $latitude;
	private $longitude;
	private $estado = null;
	private $bairros = null;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_cidade;
	}

	/**
	 * @param int $value
	 */
	public function setId($value) {
		$this->id_cidade = $value;
	}

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
     * @throws Exception
     * return UfInfo
     */
	public function getEstado() {
	    if (is_null($this->estado) && !isNullOrEmpty($this->uf)) {
            $regraUf = new UfBLL();
            $this->estado = $regraUf->pegar($this->uf);
        }
        return $this->estado;
    }

    /**
     * @throws Exception
     * @return PaisInfo
     */
    public function getPais() {
	    return $this->getEstado()->getPais();
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getPaisNome() {
        return $this->getPais()->getNome();
    }

    /**
     * @throws Exception
     * @return BairroInfo[]
     */
    public function listarBairro() {
        if (is_null($this->bairros) && $this->getId() > 0) {
            $regraBairro = new BairroBLL();
            $this->bairros = $regraBairro->listarPorCidade($this->getId());
        }
        return $this->bairros;
    }

	/**
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_cidade = $this->getId();
		$value->uf = $this->getUf();
		$value->nome = $this->getNome();
		$value->latitude = $this->getLatitude();
		$value->longitude = $this->getLongitude();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return CidadeInfo
	 */
	public static function fromJson($value) {
		$cidade = new CidadeInfo();
		$cidade->setId($value->id_cidade);
		$cidade->setUf($value->uf);
		$cidade->setNome($value->nome);
		$cidade->setLatitude($value->latitude);
		$cidade->setLongitude($value->longitude);
		return $cidade;
	}

}

