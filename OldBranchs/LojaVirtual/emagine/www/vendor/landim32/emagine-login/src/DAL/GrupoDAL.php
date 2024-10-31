<?php
namespace Emagine\Login\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Login\IDAL\IGrupoDAL;
use Emagine\Login\Model\GrupoInfo;

/**
 * Class GrupoDAL
 * @package EmagineAuth\DAL
 * @tablename grupo
 * @author EmagineCRUD
 */
class GrupoDAL implements IGrupoDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				grupo.id_grupo,
				grupo.nome
			FROM grupo
		";
	}

	/**
     * @throws Exception
	 * @return GrupoInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\\Login\\Model\\GrupoInfo");
	}

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return GrupoInfo[]
     */
    public function listarPorIdUsuario($id_usuario) {
        $query = $this->query() . "
            INNER JOIN usuario_grupo ON usuario_grupo.id_grupo = grupo.id_grupo
            WHERE usuario_grupo.id_usuario = :id_usuario
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Login\\Model\\GrupoInfo");
    }

    /**
     * @throws Exception
     * @param int $id_grupo
     * @return string[]
     */
    public function listarPermissaoPorIdGrupo($id_grupo) {
        $query = "
            SELECT grupo_permissao.slug
            FROM grupo_permissao
			WHERE grupo_permissao.id_grupo = :id_grupo
		";;
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_grupo", $id_grupo, PDO::PARAM_INT);
        return DB::getList($db,"slug");
    }

	/**
     * @throws Exception
	 * @param int $id_grupo
	 * @return GrupoInfo
	 */
	public function pegar($id_grupo) {
		$query = $this->query() . "
			WHERE grupo.id_grupo = :id_grupo
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_grupo", $id_grupo, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Login\\Model\\GrupoInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param GrupoInfo $grupo
	 */
	public function preencherCampo(PDOStatement $db, GrupoInfo $grupo) {
		$db->bindValue(":nome", $grupo->getNome());
	}

	/**
     * @throws Exception
	 * @param GrupoInfo $grupo
	 * @return int
	 */
	public function inserir($grupo) {
		$query = "
			INSERT INTO grupo (
				nome
			) VALUES (
				:nome
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $grupo);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param GrupoInfo $grupo
	 */
	public function alterar($grupo) {
		$query = "
			UPDATE grupo SET 
				nome = :nome
			WHERE grupo.id_grupo = :id_grupo
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $grupo);
		$db->bindValue(":id_grupo", $grupo->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_grupo
	 */
	public function excluir($id_grupo) {
		$query = "
			DELETE FROM grupo
			WHERE id_grupo = :id_grupo
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_grupo", $id_grupo, PDO::PARAM_INT);
		$db->execute();
	}

    /**
     * @throws Exception
     * @param int $id_grupo
     */
    public function limparPermissaoPorIdGrupo($id_grupo) {
        $query = "
			DELETE FROM grupo_permissao
			WHERE id_grupo = :id_grupo
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_grupo", $id_grupo, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_grupo
     * @param string $slug
     */
    public function adicionarPermissao($id_grupo, $slug) {
        $query = "
			INSERT INTO grupo_permissao (
				id_grupo,
				slug
			) VALUES (
				:id_grupo,
				:slug
			)
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_grupo", $id_grupo, PDO::PARAM_INT);
        $db->bindValue(":slug", $slug);
        $db->execute();
    }
}

