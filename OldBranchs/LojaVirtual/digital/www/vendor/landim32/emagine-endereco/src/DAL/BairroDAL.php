<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:27
 * Tablename: bairro
 */

namespace Emagine\Endereco\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Endereco\Model\BairroInfo;

/**
 * Class BairroDAL
 * @package Emagine\Endereco\DAL
 * @tablename bairro
 * @author EmagineCRUD
 */
class BairroDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				bairro.id_bairro,
				bairro.id_cidade,
				bairro.nome,
				bairro.valor_frete
			FROM bairro
		";
	}

	/**
     * @throws Exception
	 * @return BairroInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\Endereco\\Model\\BairroInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_cidade
	 * @return BairroInfo[]
	 */
	public function listarPorCidade($id_cidade) {
		$query = $this->query() . "
			WHERE bairro.id_cidade = :id_cidade
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_cidade", $id_cidade, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\Endereco\\Model\\BairroInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_bairro
	 * @return BairroInfo
	 */
	public function pegar($id_bairro) {
		$query = $this->query() . "
			WHERE bairro.id_bairro = :id_bairro
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_bairro", $id_bairro, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\Endereco\\Model\\BairroInfo");
	}

    /**
     * @throws Exception
     * @param string $uf
     * @param string $cidade
     * @param string $bairro
     * @return BairroInfo
     */
	public function pegarPorNome($uf, $cidade, $bairro) {
        $query = $this->query() . "
            INNER JOIN cidade ON cidade.id_cidade = bairro.id_cidade
			WHERE LOWER(bairro.nome) = LOWER(:bairro)
			AND LOWER(cidade.nome) = LOWER(:cidade)
			AND UPPER(cidade.uf) = UPPER(:uf)
			LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":uf", $uf);
        $db->bindValue(":cidade", $cidade);
        $db->bindValue(":bairro", $bairro);
        return DB::getValueClass($db,"Emagine\Endereco\\Model\\BairroInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param BairroInfo $bairro
	 */
	public function preencherCampo(PDOStatement $db, BairroInfo $bairro) {
		$db->bindValue(":id_cidade", $bairro->getIdCidade(), PDO::PARAM_INT);
		$db->bindValue(":nome", $bairro->getNome());
		$db->bindValue(":valor_frete", $bairro->getValorFrete());
	}

	/**
     * @throws Exception
	 * @param BairroInfo $bairro
	 * @return int
	 */
	public function inserir($bairro) {
		$query = "
			INSERT INTO bairro (
				id_cidade,
				nome,
				valor_frete
			) VALUES (
				:id_cidade,
				:nome,
				:valor_frete
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $bairro);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param BairroInfo $bairro
	 */
	public function alterar($bairro) {
		$query = "
			UPDATE bairro SET 
				id_cidade = :id_cidade,
				nome = :nome,
				valor_frete = :valor_frete
			WHERE bairro.id_bairro = :id_bairro
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $bairro);
		$db->bindValue(":id_bairro", $bairro->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_bairro
	 */
	public function excluir($id_bairro) {
		$query = ";
			DELETE FROM bairro
			WHERE id_bairro = :id_bairro
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_bairro", $id_bairro, PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_cidade
	 */
	public function limparPorIdCidade($id_cidade) {
		$query = "
			DELETE FROM bairro
			WHERE id_cidade = :id_cidade
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_cidade", $id_cidade, PDO::PARAM_INT);
		$db->execute();
	}

}

