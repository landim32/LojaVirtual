<?php
namespace Emagine\Social\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Social\Model\MensagemInfo;

class MensagemDAL
{
    /**
     * @return string
     */
    protected function getInfo() {
        if (defined("MENSAGEM_INFO")) {
            return MENSAGEM_INFO;
        }
        else {
            return "Emagine\\Social\\Model\\MensagemInfo";
        }
    }

    /**
     * @return string
     */
    protected function query() {
        return "
            SELECT
                usuario_mensagem.id_mensagem,
                usuario_mensagem.id_usuario,
                usuario_mensagem.id_autor,
                usuario_mensagem.data_inclusao,
                usuario_mensagem.lido,
                usuario_mensagem.mensagem,
                usuario_mensagem.url
            FROM usuario_mensagem
        ";
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param bool|null $lido
     * @return MensagemInfo[]
     */
    public function listar($id_usuario, $lido = null) {
        $query = $this->query() . "
            WHERE (
                usuario_mensagem.id_usuario = :id_usuario OR
                usuario_mensagem.id_autor = :id_autor
            )
        ";
        if (!is_null($lido)) {
            $query .= " AND usuario_mensagem.lido = :lido";
        }
        $query .= " ORDER BY usuario_mensagem.data_inclusao DESC";

        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->bindValue(":id_autor", $id_usuario, PDO::PARAM_INT);
        if (!is_null($lido)) {
            $db->bindValue(":lido", $lido, PDO::PARAM_BOOL);
        }
        $db->execute();
        return DB::getResult($db, $this->getInfo());
    }

    /**
     * Lista as mensagens que eu recebi
     * @throws Exception
     * @param int $id_usuario
     * @param bool|null $lido
     * @return MensagemInfo[]
     */
    public function listarMeu($id_usuario, $lido = null) {
        $query = $this->query() . "
            WHERE usuario_mensagem.id_usuario = :id_usuario
        ";
        if (!is_null($lido)) {
            $query .= " AND usuario_mensagem.lido = :lido";
        }
        $query .= " ORDER BY usuario_mensagem.data_inclusao DESC";
        //var_dump($query, $id_usuario, $lido);

        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        if (!is_null($lido)) {
            $db->bindValue(":lido", $lido, PDO::PARAM_BOOL);
        }
        $db->execute();
        return DB::getResult($db, $this->getInfo());
    }

    /**
     * Lista as mensagens enviadas por mim
     * @throws Exception
     * @param int $id_usuario
     * @param bool|null $lido
     * @return MensagemInfo[]
     */
    public function listarSaida($id_usuario, $lido = null) {
        $query = $this->query() . "
            WHERE usuario_mensagem.id_autor = :id_usuario
        ";
        if (!is_null($lido)) {
            $query .= " AND usuario_mensagem.lido = :lido";
        }
        $query .= " ORDER BY usuario_mensagem.data_inclusao DESC";

        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        if (!is_null($lido)) {
            $db->bindValue(":lido", $lido, PDO::PARAM_BOOL);
        }
        $db->execute();
        return DB::getResult($db, $this->getInfo());
    }

    /**
     * @throws Exception
     * @param int $id_mensagem
     * @return MensagemInfo
     */
    public function pegar($id_mensagem) {
        $query = $this->query()."
            WHERE usuario_mensagem.id_mensagem = :id_mensagem
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_mensagem", $id_mensagem, PDO::PARAM_INT);
        $db->execute();
        return DB::getValueClass($db, $this->getInfo());
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param int|null $id_autor
     */
    public function marcarLido($id_usuario, $id_autor = null) {
        $query = "
            UPDATE usuario_mensagem SET
                lido = 1
            WHERE id_usuario = :id_usuario
        ";
        if(!is_null($id_autor)){
            $query .= " AND id_autor = :id_autor ";
        }
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        if (!is_null($id_autor)) {
            $db->bindValue(":id_autor", $id_autor, PDO::PARAM_INT);
        }
        $db->execute();
    }

    /**
     * @param PDOStatement $db
     * @param MensagemInfo $mensagem
     */
    protected function preencherCampo($db, $mensagem) {
        $db->bindValue(":id_usuario", $mensagem->getIdUsuario(), PDO::PARAM_INT);
        if ($mensagem->getIdAutor() > 0) {
            $db->bindValue(":id_autor", $mensagem->getIdAutor(), PDO::PARAM_INT);
        }
        else {
            $db->bindValue(":id_autor", null);
        }
        $db->bindValue(":mensagem", $mensagem->getMensagem());
        $db->bindValue(":url", $mensagem->getUrl());
    }

    /**
     * @throws Exception
     * @param MensagemInfo $mensagem
     * @return int
     */
    public function inserir($mensagem) {
        $query = "
            INSERT INTO usuario_mensagem (
                id_usuario,
                id_autor,
                data_inclusao,
                lido,
                mensagem,
                url
            ) VALUES (
                :id_usuario,
                :id_autor,
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

    /**
     * @throws Exception
     * @param int $id_mensagem
     */
    public function excluir($id_mensagem) {
        $query = "
            DELETE FROM usuario_mensagem
            WHERE id_mensagem = :id_mensagem
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_mensagem", $id_mensagem, PDO::PARAM_INT);
        $db->execute();
    }
}