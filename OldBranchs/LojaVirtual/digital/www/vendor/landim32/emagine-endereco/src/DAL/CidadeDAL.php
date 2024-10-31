<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:23
 * Tablename: cidade
 */

namespace Emagine\Endereco\DAL;

use Exception;
use PDO;
use PDOStatement;
use Landim32\EasyDB\DB;
use Emagine\Endereco\Model\CidadeInfo;

/**
 * Class CidadeDAL
 * @package Emagine\Endereco\DAL
 * @tablename cidade
 * @author EmagineCRUD
 */
class CidadeDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				cidade.id_cidade,
				cidade.uf,
				cidade.nome,
				cidade.latitude,
				cidade.longitude
			FROM cidade
		";
	}

	/**
     * @throws Exception
	 * @return CidadeInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\Endereco\\Model\\CidadeInfo");
	}

    /**
     * @throws Exception
     * @param string $palavraChave
     * @param int $limite
     * @return CidadeInfo[]
     */
    public function buscar($palavraChave, $limite) {
        $query = $this->query() . "
            WHERE cidade.nome LIKE :palavraChave
            ORDER BY 
                cidade.nome, 
                cidade.uf
            LIMIT :limite
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":palavraChave", '%' . $palavraChave . '%');
        $db->bindValue(":limite", $limite, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\Endereco\\Model\\CidadeInfo");
    }

    /**
     * @throws Exception
     * @param string $uf
     * @return CidadeInfo[]
     */
    public function listarPorUf($uf) {
        $query = $this->query();
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":uf", $uf);
        return DB::getResult($db,"Emagine\Endereco\\Model\\CidadeInfo");
    }

	/**
     * @throws Exception
	 * @param int $id_cidade
	 * @return CidadeInfo
	 */
	public function pegar($id_cidade) {
		$query = $this->query() . "
			WHERE cidade.id_cidade = :id_cidade
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_cidade", $id_cidade, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\Endereco\\Model\\CidadeInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param CidadeInfo $cidade
	 */
	public function preencherCampo(PDOStatement $db, CidadeInfo $cidade) {
		$db->bindValue(":uf", $cidade->getUf());
		$db->bindValue(":nome", $cidade->getNome());
        $db->bindValue(":latitude", $cidade->getLatitude());
        $db->bindValue(":longitude", $cidade->getLongitude());
	}

	/**
     * @throws Exception
	 * @param CidadeInfo $cidade
	 * @return int
	 */
	public function inserir($cidade) {
		$query = "
			INSERT INTO cidade (
				uf,
				nome,
				latitude,
				longitude
			) VALUES (
				:uf,
				:nome,
				:latitude,
				:longitude
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $cidade);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param CidadeInfo $cidade
	 */
	public function alterar($cidade) {
		$query = "
			UPDATE cidade SET 
				uf = :uf,
				nome = :nome,
				latitude = :latitude,
				longitude = :longitude
			WHERE cidade.id_cidade = :id_cidade
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $cidade);
		$db->bindValue(":id_cidade", $cidade->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_cidade
	 */
	public function excluir($id_cidade) {
		$query = "
			DELETE FROM cidade
			WHERE id_cidade = :id_cidade
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_cidade", $id_cidade, PDO::PARAM_INT);
		$db->execute();
	}
}

