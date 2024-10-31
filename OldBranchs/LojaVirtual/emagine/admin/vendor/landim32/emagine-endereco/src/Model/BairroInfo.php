<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:27
 * Tablename: bairro
 */

namespace Emagine\Endereco\Model;

use Emagine\Endereco\BLL\CepBLL;
use stdClass;
use JsonSerializable;
use Exception;
use Emagine\Endereco\BLL\CidadeBLL;
use Emagine\Endereco\Model\CidadeInfo;

/**
 * Class BairroInfo
 * @package Emagine\Endereco\Model
 * @tablename bairro
 * @author EmagineCRUD
 */
class BairroInfo implements JsonSerializable {

	private $id_bairro;
	private $id_cidade;
	private $nome;
	private $valor_frete;
	private $cidade = null;
	private $origem_cep = false;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_bairro;
	}

	/**
	 * @param int $value
	 */
	public function setId($value) {
		$this->id_bairro = $value;
	}

	/**
	 * @return int
	 */
	public function getIdCidade() {
		return $this->id_cidade;
	}

	/**
	 * @param int $value
	 */
	public function setIdCidade($value) {
		$this->id_cidade = $value;
	}

	/**
     * @throws Exception
	 * @return CidadeInfo
	 */
	public function getCidade() {
		if (is_null($this->cidade) && $this->getIdCidade() > 0) {
		    if ($this->getOrigemCep()) {
                $bll = new CepBLL();
                $this->cidade = $bll->pegarCidade($this->getIdCidade());
            } else {
                $bll = new CidadeBLL();
                $this->cidade = $bll->pegar($this->getIdCidade());
            }
		}
		return $this->cidade;
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
	 * @return double
	 */
	public function getValorFrete() {
		return $this->valor_frete;
	}

    /**
     * @return string
     */
	public function getValorFreteStr() {
	    return number_format($this->getValorFrete(), 2, ",", ".");
    }

	/**
	 * @param double $value
	 */
	public function setValorFrete($value) {
		$this->valor_frete = $value;
	}

    /**
     * @return bool
     */
	public function getOrigemCep() {
	    return $this->origem_cep;
    }

    /**
     * @param bool $value
     */
    public function setOrigemCep($value) {
	    $this->origem_cep = $value;
    }

	/**
     * @throws Exception
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_bairro = $this->getId();
		$value->id_cidade = $this->getIdCidade();
		//$value->cidade = $this->getCidade();
		$value->nome = $this->getNome();
		$value->valor_frete = $this->getValorFrete();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return BairroInfo
	 */
	public static function fromJson($value) {
		$bairro = new BairroInfo();
		$bairro->setId($value->id_bairro);
		$bairro->setIdCidade($value->id_cidade);
		$bairro->setNome($value->nome);
		$bairro->setValorFrete($value->valor_frete);
		return $bairro;
	}

}

