<?php
namespace Emagine\Pedido\BLL;

use Emagine\Pedido\DAL\PagamentoPedidoDAL;
use Exception;
use Emagine\Pagamento\Model\PagamentoInfo;

class PagamentoPedidoBLL
{
    /**
     * @param int $id_loja
     * @param int $cod_situacao
     * @return PagamentoInfo[]
     * @throws Exception
     */
    public function listarPorLoja($id_loja, $cod_situacao = 0) {
        $dal = new PagamentoPedidoDAL();
        return $dal->listarPorLoja($id_loja, $cod_situacao);
    }
}