<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 06/02/18
 * Time: 10:24
 */

namespace Emagine\Pagamento\DAL;

use Emagine\Pagamento\Model\PagamentoItemInfo;
use PDO;
use PDOStatement;
use Landim32\EasyDB\DB;
use Emagine\Pagamento\Model\PagamentoInfo;

class PagamentoItemDAL
{

    /**
     * @return string
     */
    private function query() {
        return "
            SELECT
                pagamento_item.id_item,
                pagamento_item.id_pagamento,
                pagamento_item.descricao,
                pagamento_item.valor,
                pagamento_item.quantidade
            FROM pagamento_item
        ";
    }

    /**
     * @param int $id_pagamento
     * @return PagamentoItemInfo[]
     * @throws \Landim32\EasyDB\DBException
     */
    public function listar($id_pagamento) {
        $query = $this->query() . "
            WHERE pagamento_item.id_pagamento = :id_pagamento
            ORDER BY pagamento_item.id_item";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_pagamento", $id_pagamento, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Pagamento\\Model\\PagamentoItemInfo");
    }

    /**
     * @param PDOStatement $db
     * @param PagamentoItemInfo $item
     */
    private function preencherCampo(PDOStatement $db, PagamentoItemInfo $item) {
        $db->bindValue(":id_pagamento", $item->getIdPagamento(), PDO::PARAM_INT);
        $db->bindValue(":descricao", $item->getDescricao());
        $db->bindValue(":valor", $item->getValor());
        $db->bindValue(":quantidade", $item->getQuantidade(), PDO::PARAM_INT);
    }

    /**
     * @param PagamentoItemInfo $item
     * @return int
     * @throws \Landim32\EasyDB\DBException
     */
    public function inserir($item) {
        $query = "
			INSERT INTO pagamento_item (
				id_pagamento,
				descricao,
				valor,
				quantidade
			) VALUES (
				:id_pagamento,
				:descricao,
				:valor,
				:quantidade
			)
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $item);
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @param int $id_pagamento
     * @throws \Landim32\EasyDB\DBException
     */
    public function limpar($id_pagamento) {
        $query = "
			DELETE FROM pagamento_item
			WHERE id_pagamento = :id_pagamento
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_pagamento", $id_pagamento, PDO::PARAM_INT);
        $db->execute();
    }

}