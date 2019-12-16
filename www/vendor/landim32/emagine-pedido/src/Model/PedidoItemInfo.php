<?php

namespace Emagine\Pedido\Model;

use stdClass;
use JsonSerializable;
use Exception;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Produto\Model\ProdutoInfo;

/**
 * Class PedidoItemInfo
 * @package EmaginePedido\Model
 * @tablename pedido_item
 * @author EmagineCRUD
 */
class PedidoItemInfo implements JsonSerializable {

	private $id_pedido;
	private $id_produto;
	private $quantidade;
	private $pedido = null;
	private $produto = null;

	/**
	 * @return int
	 */
	public function getIdPedido() {
		return $this->id_pedido;
	}

	/**
	 * @param int $value
	 */
	public function setIdPedido($value) {
		$this->id_pedido = $value;
	}

	/**
     * @throws Exception
	 * @return PedidoInfo
	 */
	public function getPedido() {
		if (is_null($this->pedido) && $this->getIdPedido() > 0) {
			$bll = new PedidoBLL();
			$this->pedido = $bll->pegar($this->getIdPedido());
		}
		return $this->pedido;
	}

	/**
	 * @return int
	 */
	public function getIdProduto() {
		return $this->id_produto;
	}

	/**
	 * @param int $value
	 */
	public function setIdProduto($value) {
		$this->id_produto = $value;
	}

	/**
	 * @return ProdutoInfo
	 */
	public function getProduto() {
		if (is_null($this->produto) && $this->getIdProduto() > 0) {
			$bll = new ProdutoBLL();
			$this->produto = $bll->pegar($this->getIdProduto());
		}
		return $this->produto;
	}

	/**
	 * @return int
	 */
	public function getQuantidade() {
		return $this->quantidade;
	}

	/**
	 * @param int $value
	 */
	public function setQuantidade($value) {
		$this->quantidade = $value;
	}

	/**
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_pedido = $this->getIdPedido();
		$value->id_produto = $this->getIdProduto();
		$value->produto = $this->getProduto();
		$value->quantidade = $this->getQuantidade();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return PedidoItemInfo
	 */
	public static function fromJson($value) {
		$pedido_item = new PedidoItemInfo();
		$pedido_item->setIdPedido($value->id_pedido);
		$pedido_item->setIdProduto($value->id_produto);
		$pedido_item->setQuantidade($value->quantidade);
		return $pedido_item;
	}

}

