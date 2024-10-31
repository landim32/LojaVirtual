<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:21
 * Tablename: uf
 */

namespace Emagine\Endereco\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Endereco\Model\UfInfo;

/**
 * Class UfDAL
 * @package Emagine\Endereco\DAL
 * @tablename uf
 * @author EmagineCRUD
 */
class UfDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				uf.uf,
				uf.id_pais,
				uf.nome
			FROM uf
		";
	}

	/**
     * @throws Exception
	 * @return UfInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\Endereco\\Model\\UfInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_pais
	 * @return UfInfo[]
	 */
	public function listarPorPais($id_pais) {
		$query = $this->query() . "
			WHERE uf.id_pais = :id_pais
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_pais", $id_pais, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\Endereco\\Model\\UfInfo");
	}

	/**
     * @throws Exception
	 * @param string $uf
	 * @return UfInfo
	 */
	public function pegar($uf) {
		$query = $this->query() . "
			WHERE uf.uf = :uf
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":uf", $uf);
		return DB::getValueClass($db,"Emagine\Endereco\\Model\\UfInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param UfInfo $uf
	 */
	public function preencherCampo(PDOStatement $db, UfInfo $uf) {
		$db->bindValue(":uf", $uf->getUf());
		$db->bindValue(":id_pais", $uf->getIdPais());
		$db->bindValue(":nome", $uf->getNome());
	}

	/**
     * @throws Exception
	 * @param UfInfo $uf
	 */
	public function inserir($uf) {
		$query = "
			INSERT INTO uf (
				uf,
				id_pais,
				nome
			) VALUES (
				:uf,
				:id_pais,
				:nome
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $uf);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param UfInfo $uf
	 */
	public function alterar($uf) {
		$query = "
			UPDATE uf SET 
				id_pais = :id_pais,
				nome = :nome
			WHERE uf.uf = :uf
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $uf);
		$db->bindValue(":uf", $uf->getUf());
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param string $uf
	 */
	public function excluir($uf) {
		$query = "
			DELETE FROM uf
			WHERE uf = :uf
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":uf", $uf);
		$db->execute();
	}
	/**
     * @throws Exception
	 * @param int $id_pais
	 */
	public function limparPorIdPais($id_pais) {
		$query = "
			DELETE FROM uf
			WHERE id_pais = :id_pais
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_pais", $id_pais, PDO::PARAM_INT);
		$db->execute();
	}

}

