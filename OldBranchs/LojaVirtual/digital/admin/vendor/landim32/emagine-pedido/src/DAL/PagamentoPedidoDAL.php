<?php
namespace Emagine\Pedido\DAL;

use PDO;
use Exception;
use Emagine\Pagamento\DAL\PagamentoDAL;
use Emagine\Pagamento\Model\PagamentoInfo;
use Landim32\EasyDB\DB;

class PagamentoPedidoDAL extends PagamentoDAL
{
    /**
     * @param int $id_loja
     * @param int $cod_situacao
     * @return PagamentoInfo[]
     * @throws Exception
     */
    public function listarPorLoja($id_loja, $cod_situacao) {
        $query = $this->query() . "
            WHERE (1=1)
        ";
        if ($id_loja > 0) {
            $query .= " 
                AND pagamento.id_pagamento IN (
                    SELECT pedido.id_pagamento
                    FROM pedido
                    WHERE pedido.id_loja = :id_loja
                ) 
            ";
        }
        if ($cod_situacao > 0) {
            $query .= " AND pagamento.cod_situacao = :cod_situacao ";
        }
        $query .= " ORDER BY pagamento.data_vencimento";
        $db = DB::getDB()->prepare($query);
        if ($id_loja > 0) {
            $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        }
        if ($cod_situacao > 0) {
            $db->bindValue(":cod_situacao", $cod_situacao, PDO::PARAM_INT);
        }
        return DB::getResult($db,"Emagine\\Pagamento\\Model\\PagamentoInfo");
    }
}