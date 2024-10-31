<?php
namespace Emagine\Pagamento\BLL;

use Exception;
use Emagine\Pagamento\DAL\DepositoDAL;
use Emagine\Pagamento\Model\DepositoInfo;

class DepositoBLL
{
    /**
     * @return DepositoInfo[]
     * @throws Exception
     */
    public function listar() {
        $dal = new DepositoDAL();
        return $dal->listar();
    }

    /**
     * @throws Exception
     * @param int $id_deposito
     * @return DepositoInfo
     */
    public function pegar($id_deposito) {
        $dal = new DepositoDAL();
        return $dal->pegar($id_deposito);
    }
}