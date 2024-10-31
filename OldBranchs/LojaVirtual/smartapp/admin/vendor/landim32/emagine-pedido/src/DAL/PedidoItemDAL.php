<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 20/11/2017
 * Time: 10:16
 * Tablename: pedido_item
 */

namespace Emagine\Pedido\DAL;

use PDO;
use PDOStatement;
use Landim32\EasyDB\DB;
use Emagine\Pedido\Model\PedidoItemInfo;

/**
 * Class PedidoItemDAL
 * @package EmaginePedido\DAL
 * @tablename pedido_item
 * @author EmagineCRUD
 */
class PedidoItemDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				pedido_item.id_pedido,
				pedido_item.id_produto,
				pedido_item.quantidade
			FROM pedido_item
		";
	}

	/**
	 * @return PedidoItemInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\\Pedido\\Model\\PedidoItemInfo");
	}

	/**
	 * @param int $id_pedido
	 * @return PedidoItemInfo[]
	 */
	public function listarPorPedido($id_pedido) {
		$query = $this->query() . "
			WHERE pedido_item.id_pedido = :id_pedido
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_pedido", $id_pedido, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Pedido\\Model\\PedidoItemInfo");
	}

	/**
	 * @param int $id_produto
	 * @return PedidoItemInfo[]
	 */
	public function listarPorProduto($id_produto) {
		$query = $this->query() . "
			WHERE pedido_item.id_produto = :id_produto
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_produto", $id_produto, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Pedido\\Model\\PedidoItemInfo");
	}

	/**
	 * @param int $id_pedido
	 * @param int $id_produto
	 * @return PedidoItemInfo
	 */
	public function pegar($id_pedido, $id_produto) {
		$query = $this->query() . "
			WHERE pedido_item.id_pedido = :id_pedido AND pedido_item.id_produto = :id_produto
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_pedido", $id_pedido);
		$db->bindValue(":id_produto", $id_produto);
		return DB::getValueClass($db,"Emagine\\Pedido\\Model\\PedidoItemInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param PedidoItemInfo $pedido_item
	 */
	public function preencherCampo(PDOStatement $db, PedidoItemInfo $pedido_item) {
		$db->bindValue(":id_pedido", $pedido_item->getIdPedido());
		$db->bindValue(":id_produto", $pedido_item->getIdProduto());
		$db->bindValue(":quantidade", $pedido_item->getQuantidade(), PDO::PARAM_INT);
	}

	/**
	 * @param PedidoItemInfo $pedido_item
	 */
	public function inserir($pedido_item) {
		$query = "
			INSERT INTO pedido_item (
				id_pedido,
				id_produto,
				quantidade
			) VALUES (
				:id_pedido,
				:id_produto,
				:quantidade
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $pedido_item);
		$db->execute();
	}

	/**
	 * @param PedidoItemInfo $pedido_item
	 */
	public function alterar($pedido_item) {
		$query = "
			UPDATE pedido_item SET 
				quantidade = :quantidade
			WHERE pedido_item.id_pedido = :id_pedido AND pedido_item.id_produto = :id_produto
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $pedido_item);
		$db->bindValue(":id_pedido", $pedido_item->getIdPedido(), PDO::PARAM_INT);
		$db->bindValue(":id_produto", $pedido_item->getIdProduto(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
	 * @param int $id_pedido
	 * @param int $id_produto
	 */
	public function excluir($id_pedido, $id_produto) {
		$query = "
			DELETE FROM pedido_item
			WHERE id_pedido = :id_pedido AND id_produto = :id_produto
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_pedido", $id_pedido, PDO::PARAM_INT);
		$db->bindValue(":id_produto", $id_produto, PDO::PARAM_INT);
		$db->execute();
	}
	/**
	 * @param int $id_pedido
	 */
	public function limparPorPedido($id_pedido) {
		$query = "
			DELETE FROM pedido_item
			WHERE id_pedido = :id_pedido
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_pedido", $id_pedido, PDO::PARAM_INT);
		$db->execute();
	}

	/**
	 * @param int $id_produto
	 */
	public function limparPorProduto($id_produto) {
		$query = "
			DELETE FROM pedido_item
			WHERE id_produto = :id_produto
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_produto", $id_produto, PDO::PARAM_INT);
		$db->execute();
	}

}

