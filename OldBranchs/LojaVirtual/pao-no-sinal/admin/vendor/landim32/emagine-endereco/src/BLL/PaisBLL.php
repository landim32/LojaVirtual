<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:29
 * Tablename: pais
 */

namespace Emagine\Endereco\BLL;

use Exception;
use Emagine\Endereco\DAL\PaisDAL;
use Emagine\Endereco\Model\PaisInfo;
use Landim32\EasyDB\DB;

/**
 * Class PaisBLL
 * @package Emagine\Endereco\BLL
 * @tablename pais
 * @author EmagineCRUD
 */
class PaisBLL {

	/**
     * @throws Exception
	 * @return PaisInfo[]
	 */
	public function listar() {
		$dal = new PaisDAL();
		return $dal->listar();
	}


	/**
     * @throws Exception
	 * @param int $id_pais
	 * @return PaisInfo
	 */
	public function pegar($id_pais) {
		$dal = new PaisDAL();
		return $dal->pegar($id_pais);
	}

	/**
	 * @throws Exception
	 * @param PaisInfo $pais
	 */
	protected function validar(&$pais) {
		if (isNullOrEmpty($pais->getNome())) {
			throw new Exception('Preencha o nome do país.');
		}
	}

	/**
	 * @throws Exception
	 * @param PaisInfo $pais
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($pais, $transaction = true) {
		$id_pais = 0;
		$this->validar($pais);
		$dal = new PaisDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$id_pais = $dal->inserir($pais);
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
		return $id_pais;
	}

	/**
	 * @throws Exception
	 * @param PaisInfo $pais
	 * @param bool $transaction
	 */
	public function alterar($pais, $transaction = true) {
		$this->validar($pais);
		$dal = new PaisDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($pais);
			$id_pais = $pais->getId();
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
	 * @param bool $transaction
	 */
	public function excluir($id_pais, $transaction = true) {
		$dal = new PaisDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($id_pais);
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

}

