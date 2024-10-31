<?php
namespace Emagine\Pagamento\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Pagamento\Model\PagamentoOpcaoInfo;

class PagamentoOpcaoDAL
{

    /**
     * @return string
     */
    private function query() {
        return "
            SELECT
                pagamento_opcao.id_pagamento,
                pagamento_opcao.chave,
                pagamento_opcao.valor
            FROM pagamento_opcao
        ";
    }

    /**
     * @throws Exception
     * @param int $id_pagamento
     * @return PagamentoOpcaoInfo[]
     */
    public function listar($id_pagamento) {
        $query = $this->query() . "
            WHERE pagamento_opcao.id_pagamento = :id_pagamento
            ORDER BY pagamento_opcao.chave";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_pagamento", $id_pagamento, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Pagamento\\Model\\PagamentoOpcaoInfo");
    }

    /**
     * @param PDOStatement $db
     * @param PagamentoOpcaoInfo $opcao
     */
    private function preencherCampo(PDOStatement $db, PagamentoOpcaoInfo $opcao) {
        $db->bindValue(":id_pagamento", $opcao->getIdPagamento(), PDO::PARAM_INT);
        $db->bindValue(":chave", $opcao->getChave());
        $db->bindValue(":valor", $opcao->getValor());
    }

    /**
     * @param PagamentoOpcaoInfo $opcao
     * @throws \Landim32\EasyDB\DBException
     */
    public function inserir($opcao) {
        $query = "
			INSERT INTO pagamento_opcao (
				id_pagamento,
				chave,
				valor
			) VALUES (
				:id_pagamento,
				:chave,
				:valor
			)
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $opcao);
        $db->execute();
    }

    /**
     * @param int $id_pagamento
     * @throws \Landim32\EasyDB\DBException
     */
    public function limpar($id_pagamento) {
        $query = "
			DELETE FROM pagamento_opcao
			WHERE id_pagamento = :id_pagamento
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_pagamento", $id_pagamento, PDO::PARAM_INT);
        $db->execute();
    }

}