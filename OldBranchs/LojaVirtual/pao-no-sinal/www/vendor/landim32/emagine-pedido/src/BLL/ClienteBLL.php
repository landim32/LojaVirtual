<?php
namespace Emagine\Pedido\BLL;

use Exception;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Pedido\DAL\ClienteDAL;

class ClienteBLL extends UsuarioBLL
{
    /**
     * @param int $id_loja
     * @param int $codSituacao
     * @return UsuarioInfo[]
     * @throws Exception
     */
    public function listarPorLoja($id_loja, $codSituacao = 0)
    {
        $dal = new ClienteDAL();
        return $dal->listarPorLoja($id_loja, $codSituacao);
    }

    /**
     * @param int $id_loja
     * @return int
     * @throws Exception
     */
    public function pegarQuantidade($id_loja) {
        $dal = new ClienteDAL();
        return $dal->pegarQuantidade($id_loja);
    }
}