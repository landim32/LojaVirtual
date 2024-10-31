<?php
namespace Emagine\Pedido\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Pedido\Model\PedidoHorarioInfo;

/**
 * Class PedidoHorarioDAL
 * @package Emagine\Pedido\DAL
 * @tablename pedido_horario
 * @author EmagineCRUD
 */
class PedidoHorarioDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				pedido_horario.id_horario,
				pedido_horario.id_loja,
				pedido_horario.inicio,
				pedido_horario.fim
			FROM pedido_horario
		";
	}

	/**
     * @throws Exception
     * @param int $id_loja
	 * @return PedidoHorarioInfo[]
	 */
	public function listar($id_loja) {
        $query = $this->query() . "
			WHERE pedido_horario.id_loja = :id_loja
			ORDER BY 
			    pedido_horario.inicio,
			    pedido_horario.fim
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Pedido\\Model\\PedidoHorarioInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_horario
	 * @return PedidoHorarioInfo
	 */
	public function pegar($id_horario) {
		$query = $this->query() . "
			WHERE pedido_horario.id_horario = :id_horario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_horario", $id_horario, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Pedido\\Model\\PedidoHorarioInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param PedidoHorarioInfo $horario
	 */
	public function preencherCampo(PDOStatement $db, PedidoHorarioInfo $horario) {
		$db->bindValue(":id_loja", $horario->getIdLoja());
		$db->bindValue(":inicio", $horario->getInicio(), PDO::PARAM_INT);
		$db->bindValue(":fim", $horario->getFim(), PDO::PARAM_INT);
	}

	/**
     * @throws Exception
	 * @param PedidoHorarioInfo $horario
	 * @return int
	 */
	public function inserir($horario) {
		$query = "
			INSERT INTO pedido_horario (
				id_loja,
				inicio,
				fim
			) VALUES (
				:id_loja,
				:inicio,
				:fim
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $horario);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param PedidoHorarioInfo $horario
	 */
	public function alterar($horario) {
		$query = "
			UPDATE pedido_horario SET 
				id_loja = :id_loja,
				inicio = :inicio,
				fim = :fim
			WHERE pedido_horario.id_horario = :id_horario
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $horario);
		$db->bindValue(":id_horario", $horario->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_horario
	 */
	public function excluir($id_horario) {
		$query = "
			DELETE FROM pedido_horario
			WHERE id_horario = :id_horario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_horario", $id_horario, PDO::PARAM_INT);
		$db->execute();
	}
	/**
     * @throws Exception
	 * @param int $id_loja
	 */
	public function limparPorIdLoja($id_loja) {
		$query = "
			DELETE FROM pedido_horario
			WHERE id_loja = :id_loja
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
		$db->execute();
	}

}

