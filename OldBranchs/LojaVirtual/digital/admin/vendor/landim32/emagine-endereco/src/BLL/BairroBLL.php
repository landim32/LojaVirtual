<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:27
 * Tablename: bairro
 */

namespace Emagine\Endereco\BLL;

use Exception;
use Emagine\Endereco\DAL\BairroDAL;
use Emagine\Endereco\Model\BairroInfo;
use Landim32\EasyDB\DB;

/**
 * Class BairroBLL
 * @package Emagine\Endereco\BLL
 * @tablename bairro
 * @author EmagineCRUD
 */
class BairroBLL {

	/**
     * @throws Exception
	 * @return BairroInfo[]
	 */
	public function listar() {
		$dal = new BairroDAL();
		return $dal->listar();
	}

	/**
     * @throws Exception
	 * @param int $id_cidade
	 * @return BairroInfo[]
	 */
	public function listarPorCidade($id_cidade) {
		$dal = new BairroDAL();
		return $dal->listarPorCidade($id_cidade);
	}


	/**
     * @throws Exception
	 * @param int $id_bairro
	 * @return BairroInfo
	 */
	public function pegar($id_bairro) {
		$dal = new BairroDAL();
		return $dal->pegar($id_bairro);
	}

    /**
     * @throws Exception
     * @param string $uf
     * @param string $cidade
     * @param string $bairro
     * @return BairroInfo
     */
	public function pegarPorNome($uf, $cidade, $bairro) {
	    $dal = new BairroDAL();
	    return $dal->pegarPorNome($uf, $cidade, $bairro);
    }

    /**
     * @param array<string,string> $postData
     * @param BairroInfo|null $bairro
     * @return BairroInfo
     */
	public function pegarDoPost($postData, $bairro = null) {
        if (is_null($bairro)) {
            $bairro = new BairroInfo();
        }
        if (array_key_exists("id_cidade", $postData)) {
            $bairro->setIdCidade($postData["id_cidade"]);
        }
        if (array_key_exists("nome", $postData)) {
            $bairro->setNome($postData["nome"]);
        }
        if (array_key_exists("valor_frete", $postData)) {
            $valorFrete = floatvalx($postData["valor_frete"]);
            $bairro->setValorFrete( $valorFrete );
        }
        return $bairro;
    }

	/**
	 * @throws Exception
	 * @param BairroInfo $bairro
	 */
	protected function validar(&$bairro) {
		if ($bairro->getIdCidade() == 0) {
			throw new Exception('Selecione a cidade.');
		}
		if (isNullOrEmpty($bairro->getNome())) {
			throw new Exception('Preencha o nome.');
		}
	}

	/**
	 * @throws Exception
	 * @param BairroInfo $bairro
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($bairro, $transaction = true) {
		$id_bairro = 0;
		$this->validar($bairro);
		$dal = new BairroDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$id_bairro = $dal->inserir($bairro);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
		return $id_bairro;
	}

	/**
	 * @throws Exception
	 * @param BairroInfo $bairro
	 * @param bool $transaction
	 */
	public function alterar($bairro, $transaction = true) {
		$this->validar($bairro);
		$dal = new BairroDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($bairro);
			$id_bairro = $bairro->getId();
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
	}

	/**
	 * @throws Exception
	 * @param int $id_bairro
	 * @param bool $transaction
	 */
	public function excluir($id_bairro, $transaction = true) {
		$dal = new BairroDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($id_bairro);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
	}
	/**
     * @throws Exception
	 * @param int $id_cidade
	 */
	public function limparPorIdCidade($id_cidade) {
		$dal = new BairroDAL();
		$dal->limparPorIdCidade($id_cidade);
	}

    /**
     * @param string $name
     * @param string $className
     * @param string|null $id_bairro
     * @return string
     */
    public function dropdownList($name, $className = "form-control", $id_bairro = null) {
        $regraPais = new PaisBLL();
        $paises = $regraPais->listar();
        $str = "";
        $str .= "<select name='" . $name . "' class='" . $className . "'>\n";
        foreach ($paises as $pais) {
            $estados = $pais->listarEstado();
            if (count($estados) > 0) {
                foreach ($estados as $estado) {
                    $cidades = $estado->listarCidade();
                    foreach ($cidades as $cidade) {
                        $nome = $pais->getNome() . " / " . $cidade->getNome() . " / " . $estado->getNome();
                        $str .= "<optgroup label=\"" . $nome . "\">\n";
                        $bairros = $cidade->listarBairro();
                        foreach ($bairros as $bairro) {
                            $str .= "<option value=\"" . $bairro->getId() . "\"";
                            if ($bairro->getId() == $id_bairro) {
                                $str .= " selected=\"selected\"";
                            }
                            $str .= ">" . $bairro->getNome() . "</option>\n";
                        }
                        $str .= "</optgroup>";
                    }
                }
            }
        }
        $str .= "</select>";
        return $str;
    }

}

