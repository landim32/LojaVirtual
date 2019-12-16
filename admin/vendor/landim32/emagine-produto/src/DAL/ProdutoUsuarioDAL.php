<?php
namespace Emagine\Produto\DAL;

use PDO;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Login\DAL\UsuarioDAL;
use Emagine\Login\Model\UsuarioInfo;

class ProdutoUsuarioDAL extends UsuarioDAL
{
    /**
     * @param int $idLoja
     * @param int $codSituacao
     * @return UsuarioInfo[]
     * @throws Exception
     */
    public function listarPorLoja($idLoja, $codSituacao = 0)
    {
        $query = $this->query() . "
            INNER JOIN loja_usuario ON loja_usuario.id_usuario = usuario.id_usuario
            WHERE loja_usuario.id_loja = :id_loja
        ";
        if ($codSituacao > 0) {
            $query .= " WHERE usuario.cod_situacao = :cod_situacao ";
        }
        $query .= " ORDER BY usuario.nome ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $idLoja, PDO::PARAM_INT);
        if ($codSituacao > 0) {
            $db->bindValue(":cod_situacao", $codSituacao, PDO::PARAM_INT);
        }
        return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }
}