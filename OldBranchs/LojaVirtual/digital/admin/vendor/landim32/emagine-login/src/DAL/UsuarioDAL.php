<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/07/2017
 * Time: 13:24
 * Tablename: usuario
 */

namespace Emagine\Login\DAL;

use PDO;
use Exception;
use PDOStatement;
use Landim32\EasyDB\DB;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Login\IDAL\IUsuarioDAL;

class UsuarioDAL implements IUsuarioDAL {

    /**
     * @return string
     */
    protected function query() {
        return "
			SELECT 
				usuario.id_usuario,
				usuario.foto,
				usuario.data_inclusao,
				usuario.ultima_alteracao,
				usuario.ultimo_login,
				usuario.email,
				usuario.slug,
				usuario.nome,
				usuario.senha,
				usuario.telefone,
				usuario.cpf_cnpj,
				usuario.cod_situacao
			FROM usuario
		";
    }

    /**
     * @throws Exception
     * @param int $codSituacao
     * @return UsuarioInfo[]
     */
    public function listar($codSituacao = 0) {
        $query = $this->query();
        if ($codSituacao > 0) {
            $query .= " WHERE usuario.cod_situacao = :cod_situacao ";
        }
        $query .= " ORDER BY usuario.nome ";
        $db = DB::getDB()->prepare($query);
        if ($codSituacao > 0) {
            $db->bindValue(":cod_situacao", $codSituacao, PDO::PARAM_INT);
        }
        return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @throws Exception
     * @param string $palavraChave
     * @return UsuarioInfo[]
     */
    public function buscaPorPalavra($palavraChave) {
        $query = $this->query() . "
            WHERE (
                usuario.nome LIKE :palavra_nome OR
                usuario.email LIKE :palavra_email
            )
            AND usuario.cod_situacao = :cod_situacao
            ORDER BY usuario.nome
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":palavra_nome", '%' . $palavraChave . '%');
        $db->bindValue(":palavra_email", '%' . $palavraChave . '%');
        $db->bindValue(":cod_situacao", UsuarioInfo::ATIVO, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return UsuarioInfo
     */
    public function pegar($id_usuario) {
        $query = $this->query() . "
			WHERE usuario.id_usuario = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @throws Exception
     * @param string $slug
     * @return UsuarioInfo
     */
    public function pegarPorSlug($slug) {
        $query = $this->query() . "
			WHERE usuario.slug = :slug
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":slug", $slug);
        return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @throws Exception
     * @param string $email
     * @return UsuarioInfo
     */
    public function pegarPorEmail($email) {
        $query = $this->query() . "
			WHERE usuario.email = :email
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":email", $email);
        return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @param string $email
     * @param string $senha
     * @return UsuarioInfo|null
     * @throws Exception
     */
    public function pegarPorLogin($email, $senha) {
        $query = $this->query() . "
			WHERE usuario.email = :email
			AND usuario.senha = :senha
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":email", $email);
        $db->bindValue(":senha", $senha);
        return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioInfo");
    }

    /**
     * @param PDOStatement $db
     * @param UsuarioInfo $usuario
     */
    private function preencherCampo(PDOStatement $db, UsuarioInfo $usuario) {
        $db->bindValue(":foto", $usuario->getFoto());
        $db->bindValue(":email", $usuario->getEmail());
        $db->bindValue(":slug", $usuario->getSlug());
        $db->bindValue(":nome", $usuario->getNome());
        $db->bindValue(":telefone", $usuario->getTelefone());
        $db->bindValue(":cpf_cnpj", $usuario->getCpfCnpj());
        $db->bindValue(":cod_situacao", $usuario->getCodSituacao());
    }

    /**
     * @throws Exception
     * @param UsuarioInfo $usuario
     * @return int
     */
    public function inserir($usuario) {
        $query = "
			INSERT INTO usuario (
				foto,
				data_inclusao,
				ultima_alteracao,
				ultimo_login,
				email,
				slug,
				nome,
				senha,
				telefone,
				cpf_cnpj,
				cod_situacao
			) VALUES (
				:foto,
				NOW(),
				NOW(),
				NOW(),
				:email,
				:slug,
				:nome,
				:senha,
				:telefone,
				:cpf_cnpj,
				:cod_situacao
			)
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $usuario);
        $db->bindValue(":senha", $usuario->getSenha());
        $db->execute();
        return DB::lastInsertId();
    }

    /**
     * @throws Exception
     * @param UsuarioInfo $usuario
     */
    public function alterar($usuario) {
        $query = "
			UPDATE usuario SET 
				foto = :foto,
				ultima_alteracao = NOW(),
				email = :email,
				slug = :slug,
				nome = :nome,
				telefone = :telefone,
				cpf_cnpj = :cpf_cnpj,
				cod_situacao = :cod_situacao
			WHERE usuario.id_usuario = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $usuario);
        $db->bindValue(":id_usuario", $usuario->getId(), PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param string $senha
     */
    public function alterarSenha($id_usuario, $senha) {
        $query = "
			UPDATE usuario SET 
				ultima_alteracao = NOW(),
				senha = :senha
			WHERE usuario.id_usuario = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->bindValue(":senha", $senha);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     */
    public function excluir($id_usuario) {
        $query = "
			DELETE FROM usuario
			WHERE id_usuario = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     */
    public function limparGrupoPorIdUsuario($id_usuario) {
        $query = "
			DELETE FROM usuario_grupo
			WHERE id_usuario = :id_usuario
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param int $id_grupo
     */
    public function adicionarGrupo($id_usuario, $id_grupo) {
        $query = "
			INSERT INTO usuario_grupo (
				id_usuario,
				id_grupo
			) VALUES (
				:id_usuario,
				:id_grupo
			)
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $db->bindValue(":id_grupo", $id_grupo, PDO::PARAM_INT);
        $db->execute();
    }
}
