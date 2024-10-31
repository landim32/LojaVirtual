<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:23
 * Tablename: cidade
 */

namespace Emagine\Endereco\BLL;

use Exception;
use Emagine\Endereco\DAL\CidadeDAL;
use Emagine\Endereco\Model\CidadeInfo;
use Landim32\EasyDB\DB;

/**
 * Class CidadeBLL
 * @package Emagine\Endereco\BLL
 * @tablename cidade
 * @author EmagineCRUD
 */
class CidadeBLL {

	/**
     * @throws Exception
	 * @return CidadeInfo[]
	 */
	public function listar() {
		$dal = new CidadeDAL();
		return $dal->listar();
	}

    /**
     * @throws Exception
     * @param string $uf
     * @return CidadeInfo[]
     */
	public function listarPorUf($uf) {
	    $dal = new CidadeDAL();
	    return $dal->listarPorUf($uf);
    }

    /**
     * @param string $palavraChave
     * @param int $limite
     * @return CidadeInfo[]
     * @throws Exception
     */
    public function buscar($palavraChave, $limite = 15) {
	    $dal = new CidadeDAL();
        $palavraChave = remove_accents($palavraChave);
	    return $dal->buscar($palavraChave, $limite);
    }


	/**
     * @throws Exception
	 * @param int $id_cidade
	 * @return CidadeInfo
	 */
	public function pegar($id_cidade) {
		$dal = new CidadeDAL();
		return $dal->pegar($id_cidade);
	}

	/**
	 * @throws Exception
	 * @param CidadeInfo $cidade
	 */
	protected function validar(&$cidade) {
		if (isNullOrEmpty($cidade->getUf())) {
			throw new Exception('Preencha a Uf.');
		}
		if (isNullOrEmpty($cidade->getNome())) {
			throw new Exception('Preencha o nome.');
		}
	}

	/**
	 * @throws Exception
	 * @param CidadeInfo $cidade
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($cidade, $transaction = true) {
		$id_cidade = 0;
		$this->validar($cidade);
		$dal = new CidadeDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$id_cidade = $dal->inserir($cidade);
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
		return $id_cidade;
	}

	/**
	 * @throws Exception
	 * @param CidadeInfo $cidade
	 * @param bool $transaction
	 */
	public function alterar($cidade, $transaction = true) {
		$this->validar($cidade);
		$dal = new CidadeDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($cidade);
			$id_cidade = $cidade->getId();
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
	 * @param bool $transaction
	 */
	public function excluir($id_cidade, $transaction = true) {
		$dal = new CidadeDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($id_cidade);
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
     * @param string $name
     * @param string $className
     * @param string|null $id_cidade
     * @return string
     */
    public function dropdownList($name, $className = "form-control", $id_cidade = null) {
        $regraPais = new PaisBLL();
        $paises = $regraPais->listar();
        $str = "";
        $str .= "<select name='" . $name . "' class='" . $className . "'>\n";
        foreach ($paises as $pais) {
            $estados = $pais->listarEstado();
            if (count($estados) > 0) {
                foreach ($estados as $estado) {
                    $cidades = $estado->listarCidade();
                    $nome = $pais->getNome() . " / " . $estado->getNome();
                    $str .= "<optgroup label=\"" . $nome . "\">\n";
                    foreach ($cidades as $cidade) {
                        $str .= "<option value=\"" . $cidade->getId() . "\"";
                        if ($cidade->getId() == $id_cidade) {
                            $str .= " selected=\"selected\"";
                        }
                        $str .= ">" . $cidade->getNome() . "</option>\n";
                    }
                    $str .= "</optgroup>\n";
                }
            }
        }
        $str .= "</select>\n";
        return $str;
    }

}

