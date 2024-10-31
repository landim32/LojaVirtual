<?php
namespace Emagine\Pedido\DAL;

use PDO;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Login\DAL\UsuarioDAL;
use Emagine\Login\Model\UsuarioInfo;

class ClienteDAL extends UsuarioDAL
{
    /**
     * @throws Exception
     * @param int $id_loja
     * @param int $codSituacao
     * @return UsuarioInfo[]
     */
    public function listarPorLoja($id_loja, $codSituacao = 0) {
        $query = $this->query();
        $query .= " 
            WHERE usuario.id_usuario IN (
                SELECT pedido.id_usuario
                FROM pedido
                WHERE pedido.id_loja = :id_loja
            ) 
        ";
        if ($codSituacao > 0) {
            $query .= " AND usuario.cod_situacao = :cod_situacao ";
        }
        $query .= " ORDER BY usuario.nome ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        if ($codSituacao > 0) {
            $db->bindValue(":cod_situacao", $codSituacao, PDO::PARAM_INT);
        }
        return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @param int $id_loja
     * @return int
     * @throws Exception
     */
    public function pegarQuantidade($id_loja) {
        $query = "
            SELECT COUNT(*) AS 'quantidade'
            FROM usuario
			WHERE usuario.id_usuario IN (
                SELECT pedido.id_usuario
                FROM pedido
                WHERE pedido.id_loja = :id_loja
            )
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        return intval( DB::getValue($db, "quantidade") );
    }
}