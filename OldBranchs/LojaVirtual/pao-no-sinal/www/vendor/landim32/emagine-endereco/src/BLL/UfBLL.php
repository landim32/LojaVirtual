<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:21
 * Tablename: uf
 */

namespace Emagine\Endereco\BLL;

use Exception;
use Emagine\Endereco\DAL\UfDAL;
use Emagine\Endereco\Model\UfInfo;
use Landim32\EasyDB\DB;

/**
 * Class UfBLL
 * @package Emagine\Endereco\BLL
 * @tablename uf
 * @author EmagineCRUD
 */
class UfBLL {

	/**
     * @throws Exception
	 * @return UfInfo[]
	 */
	public function listar() {
		$dal = new UfDAL();
		return $dal->listar();
	}

	/**
     * @throws Exception
	 * @param int $id_pais
	 * @return UfInfo[]
	 */
	public function listarPorPais($id_pais) {
		$dal = new UfDAL();
		return $dal->listarPorPais($id_pais);
	}


	/**
     * @throws Exception
	 * @param string $uf
	 * @return UfInfo
	 */
	public function pegar($uf) {
		$dal = new UfDAL();
		return $dal->pegar($uf);
	}

    /**
     * @param array<string,string> $postData
     * @param UfInfo $uf
     * @return UfInfo
     */
	public function pegarDoPost($postData, $uf = null) {
	    if (is_null($uf)) {
	        $uf = new UfInfo();
        }
        if (array_key_exists('uf', $postData)) {
	        $uf->setUf($postData['uf']);
        }
        if (array_key_exists('id_pais', $postData)) {
            $uf->setIdPais(intval($postData['id_pais']));
        }
        if (array_key_exists('nome', $postData)) {
            $uf->setNome($postData['nome']);
        }
        return $uf;
    }

	/**
	 * @throws Exception
	 * @param UfInfo $uf
	 */
	protected function validar(&$uf) {
		if (isNullOrEmpty($uf->getUf())) {
			throw new Exception('Preencha a Uf.');
		}
		if (!($uf->getIdPais() > 0)) {
			throw new Exception('Selecione um país.');
		}
		if (isNullOrEmpty($uf->getNome())) {
			throw new Exception('Preencha o nome.');
		}
        $uf->setUf(strtoupper($uf->getUf()));
	}

	/**
	 * @throws Exception
	 * @param UfInfo $uf
	 * @param bool $transaction
	 */
	public function inserir($uf, $transaction = true) {
		$this->validar($uf);
		$dal = new UfDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->inserir($uf);
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
	 * @param UfInfo $uf
	 * @param bool $transaction
	 */
	public function alterar($uf, $transaction = true) {
		$this->validar($uf);
		$dal = new UfDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($uf);
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
	 * @param string $uf
	 * @param bool $transaction
	 */
	public function excluir($uf, $transaction = true) {
		$dal = new UfDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($uf);
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
	 * @param int $id_pais
	 */
	public function limparPorIdPais($id_pais) {
		$dal = new UfDAL();
		$dal->limparPorIdPais($id_pais);
	}

    /**
     * @throws Exception
     * @param string $name
     * @param string $className
     * @param string|null $uf
     * @return string
     */
    public function dropdownList($name, $className = "form-control", $uf = null) {
        $regraPais = new PaisBLL();
        $paises = $regraPais->listar();
        $str = "";
        $str .= "<select name='" . $name . "' class='" . $className . "'>\n";
        foreach ($paises as $pais) {
            $estados = $this->listarPorPais($pais->getId());
            if (count($estados) > 0) {
                $str .= "<optgroup label=\"" . $pais->getNome() . "\">\n";
                foreach ($estados as $estado) {
                    $str .= "<option value=\"" . $estado->getUf() . "\"";
                    if (strtoupper($estado->getUf()) == strtoupper($uf)) {
                        $str .= " selected=\"selected\"";
                    }
                    $str .= ">" . $estado->getNome() . "</option>\n";
                }
                $str .= "</optgroup>";
            }
        }
        $str .= "</select>";
        return $str;
    }

}

