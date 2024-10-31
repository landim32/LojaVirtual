<?php
namespace Emagine\Pagamento\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Pagamento\Model\CartaoInfo;

class CartaoDAL
{

    /**
     * @return string
     */
    private function query() {
        return "
            SELECT
                pagamento_cartao.id_cartao,
                pagamento_cartao.id_usuario,
                pagamento_cartao.bandeira,
                pagamento_cartao.nome,
                pagamento_cartao.token,
                pagamento_cartao.cvv
            FROM pagamento_cartao
        ";
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return CartaoInfo[]
     */
    public function listar($id_usuario) {
        $query = $this->query() . "
            WHERE pagamento_cartao.id_usuario = :id_usuario 
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Pagamento\\Model\\CartaoInfo");
    }

    /**
     * @throws Exception
     * @param int $id_cartao
     * @return CartaoInfo
     */
    public function pegar($id_cartao) {
        $query = $this->query() . "
            WHERE pagamento_cartao.id_cartao = :id_cartao
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cartao", $id_cartao, PDO::PARAM_INT);
        return DB::getValueAsClass($db,"Emagine\\Pagamento\\Model\\CartaoInfo");
    }

    /**
     * @throws Exception
     * @param string $token
     * @return CartaoInfo
     */
    public function pegarPorToken($token) {
        $query = $this->query() . "
            WHERE pagamento_cartao.token = :token
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":token", $token);
        return DB::getValueAsClass($db,"Emagine\\Pagamento\\Model\\CartaoInfo");
    }

    /**
     * @param PDOStatement $db
     * @param CartaoInfo $cartao
     */
    private function preencherCampo(PDOStatement $db, CartaoInfo $cartao) {
        $db->bindValue(":id_usuario", $cartao->getIdUsuario(), PDO::PARAM_INT);
        $db->bindValue(":bandeira", $cartao->getBandeira(), PDO::PARAM_INT);
        $db->bindValue(":nome", $cartao->getNome());
        $db->bindValue(":token", $cartao->getToken());
        $db->bindValue(":cvv", $cartao->getCVV());
    }

    /**
     * @throws Exception
     * @param CartaoInfo $cartao
     * @return int
     */
    public function inserir($cartao) {
        $query = "
			INSERT INTO pagamento_cartao (
                id_usuario,
                bandeira,
                nome,
                token,
                cvv
			) VALUES (
				:id_usuario,
                :bandeira,
                :nome,
                :token,
                :cvv
			)
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $cartao);
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @throws Exception
     * @param CartaoInfo $cartao
     */
    public function alterar($cartao) {
        $query = "
			UPDATE pagamento_cartao SET
				id_usuario = :id_usuario,
                bandeira = :bandeira,
                nome = :nome,
                token = :token,
                cvv = :cvv
			WHERE id_cartao = :id_cartao
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $cartao);
        $db->bindValue(":id_cartao", $cartao->getId(), PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_cartao
     */
    public function excluir($id_cartao) {
        $query = "
			DELETE FROM pagamento_cartao
			WHERE id_cartao = :id_cartao
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cartao", $id_cartao, PDO::PARAM_INT);
        $db->execute();
    }

}