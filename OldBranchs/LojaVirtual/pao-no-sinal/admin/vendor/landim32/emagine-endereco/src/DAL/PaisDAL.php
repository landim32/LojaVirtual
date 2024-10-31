<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:29
 * Tablename: pais
 */

namespace Emagine\Endereco\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Endereco\Model\PaisInfo;

/**
 * Class PaisDAL
 * @package Emagine\Endereco\DAL
 * @tablename pais
 * @author EmagineCRUD
 */
class PaisDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				pais.id_pais,
				pais.nome
			FROM pais
		";
	}

	/**
     * @throws Exception
	 * @return PaisInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\Endereco\\Model\\PaisInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_pais
	 * @return PaisInfo
	 */
	public function pegar($id_pais) {
		$query = $this->query() . "
			WHERE pais.id_pais = :id_pais
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_pais", $id_pais, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\Endereco\\Model\\PaisInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param PaisInfo $pais
	 */
	public function preencherCampo(PDOStatement $db, PaisInfo $pais) {
		$db->bindValue(":nome", $pais->getNome());
	}

	/**
     * @throws Exception
	 * @param PaisInfo $pais
	 * @return int
	 */
	public function inserir($pais) {
		$query = "
			INSERT INTO pais (
				nome
			) VALUES (
				:nome
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $pais);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param PaisInfo $pais
	 */
	public function alterar($pais) {
		$query = "
			UPDATE pais SET 
				nome = :nome
			WHERE pais.id_pais = :id_pais
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $pais);
		$db->bindValue(":id_pais", $pais->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_pais
	 */
	public function excluir($id_pais) {
		$query = ";
			DELETE FROM pais
			WHERE id_pais = :id_pais
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_pais", $id_pais, PDO::PARAM_INT);
		$db->execute();
	}
}

