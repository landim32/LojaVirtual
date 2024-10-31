<?php
namespace Emagine\Pedido\DAL;

use Emagine\Social\Model\MensagemInfo;
use Exception;
use PDO;
use PDOStatement;
use Emagine\Pedido\Model\PedidoMensagemInfo;
use Emagine\Social\DAL\MensagemDAL;
use Landim32\EasyDB\DB;

class PedidoMensagemDAL extends MensagemDAL
{
    /**
     * @return string
     */
    protected function query()
    {
        return "
            SELECT
                usuario_mensagem.id_mensagem,
                usuario_mensagem.id_usuario,
                usuario_mensagem.id_autor,
                usuario_mensagem.id_pedido,
                usuario_mensagem.data_inclusao,
                usuario_mensagem.lido,
                usuario_mensagem.mensagem,
                usuario_mensagem.url
            FROM usuario_mensagem
        ";
    }

    /**
     * @param PDOStatement $db
     * @param MensagemInfo $mensagem
     */
    protected function preencherCampo($db, $mensagem) {
        parent::preencherCampo($db, $mensagem);
        if ($mensagem instanceof PedidoMensagemInfo) {
            if ($mensagem->getIdPedido() > 0) {
                $db->bindValue(":id_pedido", $mensagem->getIdPedido(), PDO::PARAM_INT);
            } else {
                $db->bindValue(":id_pedido", null);
            }
        }
    }

    /**
     * @throws Exception
     * @param PedidoMensagemInfo $mensagem
     * @return int
     */
    public function inserir($mensagem) {
        $query = "
            INSERT INTO usuario_mensagem (
                id_usuario,
                id_autor,
                id_pedido,
                data_inclusao,
                lido,
                mensagem,
                url
            ) VALUES (
                :id_usuario,
                :id_autor,
                :id_pedido,
                NOW(),
                0,
                :mensagem,
                :url
            )
        ";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $mensagem);
        $db->execute();
        return DB::lastInsertId();
    }

}