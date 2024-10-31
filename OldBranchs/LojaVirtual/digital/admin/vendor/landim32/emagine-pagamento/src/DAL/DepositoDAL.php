<?php
namespace Emagine\Pagamento\DAL;

use Exception;
use PDO;
use PDOStatement;
use Landim32\EasyDB\DB;
use Emagine\Pagamento\Model\DepositoInfo;

class DepositoDAL
{

    /**
     * @return string
     */
    private function query() {
        return "
            SELECT
                pagamento_deposito.id_deposito,
                pagamento_deposito.desconto,
                pagamento_deposito.nome,
                pagamento_deposito.banco,
                pagamento_deposito.agencia,
                pagamento_deposito.conta,
                pagamento_deposito.correntista,
                pagamento_deposito.cpf_cnpj,
                pagamento_deposito.mensagem
            FROM pagamento_deposito
        ";
    }

    /**
     * @return DepositoInfo[]
     * @throws Exception
     */
    public function listar() {
        $query = $this->query() . "
            ORDER BY pagamento_deposito.nome";
        $db = DB::getDB()->prepare($query);
        return DB::getResult($db,"Emagine\\Pagamento\\Model\\DepositoInfo");
    }

    /**
     * @param int $id_deposito
     * @return DepositoInfo
     * @throws Exception
     */
    public function pegar($id_deposito) {
        $query = $this->query() . "
            WHERE pagamento_deposito.id_deposito = :id_deposito
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_deposito", $id_deposito, PDO::PARAM_INT);
        return DB::getValueAsClass($db,"Emagine\\Pagamento\\Model\\DepositoInfo");
    }

    /**
     * @param PDOStatement $db
     * @param DepositoInfo $deposito
     */
    private function preencherCampo(PDOStatement $db, DepositoInfo $deposito) {
        $db->bindValue(":desconto", $deposito->getDesconto());
        $db->bindValue(":nome", $deposito->getNome(), PDO::PARAM_INT);
        $db->bindValue(":banco", $deposito->getBanco());
        $db->bindValue(":agencia", $deposito->getAgencia());
        $db->bindValue(":conta", $deposito->getConta());
        $db->bindValue(":correntista", $deposito->getCorrentista());
        $db->bindValue(":cpf_cnpj", $deposito->getCpfCnpj());
        $db->bindValue(":mensagem", $deposito->getMensagem());
    }

    /**
     * @param DepositoInfo $deposito
     * @return int
     * @throws Exception
     */
    public function inserir($deposito) {
        $query = "
			INSERT INTO pagamento_deposito (
                desconto,
                nome,
                banco,
                agencia,
                conta,
			    correntista,
			    cpf_cnpj,
                mensagem
			) VALUES (
                :desconto,
                :nome,
                :banco,
                :agencia,
                :conta,
			    :correntista,
			    :cpf_cnpj,
                :mensagem
			)
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $deposito);
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @param int $id_deposito
     * @throws Exception
     */
    public function limpar($id_deposito) {
        $query = "
			DELETE FROM pagamento_deposito
			WHERE id_deposito = :id_deposito
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_deposito", $id_deposito, PDO::PARAM_INT);
        $db->execute();
    }

}