<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 09/11/2017
 * Time: 22:49
 * Tablename: loja
 */

namespace Emagine\Produto\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Produto\Model\LojaOpcaoInfo;

class LojaOpcaoDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				loja_opcao.id_loja,
				loja_opcao.chave,
				loja_opcao.valor
			FROM loja_opcao
		";
	}

	/**
     * @throws Exception
     * @param int $id_loja
	 * @return LojaOpcaoInfo[]
	 */
	public function listar($id_loja) {
		$query = $this->query() . "
		    WHERE loja_opcao.id_loja = :id_loja
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Produto\\Model\\LojaOpcaoInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param LojaOpcaoInfo $opcao
	 */
	public function preencherCampo(PDOStatement $db, LojaOpcaoInfo $opcao) {
		$db->bindValue(":id_loja", $opcao->getIdLoja(), PDO::PARAM_INT);
        $db->bindValue(":chave", $opcao->getChave());
        $db->bindValue(":valor", $opcao->getValor());
	}

	/**
     * @throws Exception
	 * @param LojaOpcaoInfo $opcao
	 */
	public function inserir($opcao) {
		$query = "
			INSERT INTO loja_opcao (
				id_loja,
				chave,
				valor
			) VALUES (
				:id_loja,
				:chave,
				:valor
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $opcao);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_loja
	 */
	public function limpar($id_loja) {
		$query = "
			DELETE FROM loja_opcao
			WHERE id_loja = :id_loja
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
		$db->execute();
	}
}

