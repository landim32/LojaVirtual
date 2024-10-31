<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 27/01/2017
 * Time: 23:29
 */

namespace Emagine\Social\DAL;

use Landim32\EasyDB\DB;
use PDO;
use Emagine\Login\DAL\UsuarioDAL;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Social\Model\RelacionamentoInfo;

class UsuarioSocialDAL extends UsuarioDAL
{
    /**
     * @param $email
     * @return UsuarioInfo
     */
    public function pegarConvite($email) {
        $query = $this->query()."
            INNER JOIN usuario_convite ON usuario_convite.id_usuario = usuario.id_usuario
            WHERE usuario_convite.email = :email
            ORDER BY usuario.data_inclusao DESC
            LIMIT 1
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":email", $email);
        $db->execute();
        return DB::getValueClass($db, "\\Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @param string $email
     * @param string $nome
     */
    public function gravarConvite($email, $nome) {
        $query = "
            INSERT INTO usuario_convite (
                id_usuario,
                data_inclusao,
                nome,
                email
            ) VALUES (
                :id_usuario,
                NOW(),
                :nome,
                :email
            )
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", UsuarioBLL::pegarIdUsuarioAtual());
        $db->bindValue(":nome", $nome);
        $db->bindValue(":email", $email);
        $db->execute();
    }

    /**
     * @param int $id_usuario
     * @return int
     */
    public function quantidadeAmigo($id_usuario) {
        $query = "
            SELECT COUNT(*) AS 'quantidade'
            FROM usuario_relacionamento
            WHERE usuario_relacionamento.id_origem = :id_usuario
            AND usuario_relacionamento.tipo IN (1,2)
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->execute();
        return DB::getValue($db, "quantidade");
    }

    /**
     * @param string $palavra
     * @param int $limite
     * @return UsuarioInfo[]
     */
    public function buscarAmigo($palavra, $limite = 15) {
        $query = $this->query()."
            WHERE usuario.cod_situacao = :cod_situacao
            AND usuario.nome LIKE :palavra
            ORDER BY usuario.nome
            LIMIT :limite
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":palavra", '%'.$palavra.'%');
        $db->bindValue(":cod_situacao", UsuarioInfo::ATIVO, PDO::PARAM_INT);
        $db->bindValue(":limite", $limite, PDO::PARAM_INT);
        $db->execute();
        return DB::getResult($db, "\\Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @param int $id_usuario
     * @return UsuarioInfo[]
     */
    public function listarAmigo($id_usuario) {
        $query = $this->query()."
            INNER JOIN usuario_relacionamento on usuario_relacionamento.id_destino = usuario.id_usuario
            WHERE usuario_relacionamento.id_origem = :id_usuario
            AND usuario_relacionamento.tipo IN (1,2)
            AND usuario.ativo = 1
            ORDER BY usuario.nome
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->execute();
        return DB::getResult($db, "\\Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @param int $id_usuario
     * @return UsuarioInfo[]
     */
    public function listarSolicitacao($id_usuario) {
        $query = $this->query()."
            INNER JOIN usuario_relacionamento on usuario_relacionamento.id_origem = usuario.id_usuario
            WHERE usuario_relacionamento.id_destino = :id_usuario
            AND usuario_relacionamento.tipo = 0
            AND usuario.ativo = 1
            ORDER BY
                usuario_relacionamento.data_inclusao
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->execute();
        return DB::getResult($db, "\\Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @param int $id_origem
     * @param int $id_destino
     * @return RelacionamentoInfo
     */
    public function pegarRelacionamento($id_origem, $id_destino) {
        $query = "
            SELECT
                id_origem,
                id_destino,
                tipo,
                seguir,
                data_inclusao
            FROM usuario_relacionamento
            WHERE (
                id_origem = :id_origem AND
                id_destino = :id_destino
            ) OR (
                id_origem = :id_destino AND
                id_destino = :id_origem
            )
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_origem", $id_origem, PDO::PARAM_INT);
        $db->bindValue(":id_destino", $id_destino, PDO::PARAM_INT);
        $db->execute();
        return DB::getValueClass($db, "\\EmagineSocial\\Model\\RelacionamentoInfo");
    }

    /**
     * @param RelacionamentoInfo $relacionamento
     */
    public function inserirRelacionamento($relacionamento) {
        $query = "
            INSERT INTO usuario_relacionamento (
                id_origem,
                id_destino,
                tipo,
                seguir,
                data_inclusao
            ) VALUES (
                :id_origem,
                :id_destino,
                :tipo,
                :seguir,
                NOW()
            )
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_origem", $relacionamento->getIdOrigem(), PDO::PARAM_INT);
        $db->bindValue(":id_destino", $relacionamento->getIdDestino(), PDO::PARAM_INT);
        $db->bindValue(":tipo", $relacionamento->getTipo());
        $db->bindValue(":seguir", $relacionamento->getSeguir());
        $db->execute();
    }

    /**
     * @param RelacionamentoInfo $relacionamento
     */
    public function alterarRelacionamento($relacionamento) {
        $query = "
            UPDATE usuario_relacionamento SET
                tipo = :tipo,
                seguir = :seguir,
                data_inclusao = NOW()
            WHERE id_origem = :id_origem
            AND id_destino = :id_destino
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_origem", $relacionamento->getIdOrigem(), PDO::PARAM_INT);
        $db->bindValue(":id_destino", $relacionamento->getIdDestino(), PDO::PARAM_INT);
        $db->bindValue(":tipo", $relacionamento->getTipo());
        $db->bindValue(":seguir", $relacionamento->getSeguir());
        $db->execute();
    }

    /**
     * @param int $id_origem
     * @param int $id_destino
     */
    public function excluirRelacionamento($id_origem, $id_destino) {
        $query = "
            DELETE FROM usuario_relacionamento
            WHERE (
                id_origem = :id_origem AND
                id_destino = :id_destino
            ) OR (
                id_origem = :id_destino AND
                id_destino = :id_origem
            )
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_origem", $id_origem, PDO::PARAM_INT);
        $db->bindValue(":id_destino", $id_destino, PDO::PARAM_INT);
        $db->execute();
    }
}