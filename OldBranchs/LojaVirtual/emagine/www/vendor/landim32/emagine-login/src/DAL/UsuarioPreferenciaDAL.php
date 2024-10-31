<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/07/2017
 * Time: 13:07
 * Tablename: usuario_preferencia
 */

namespace Emagine\Login\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Login\IDAL\IUsuarioPreferenciaDAL;
use Emagine\Login\Model\UsuarioPreferenciaInfo;

/**
 * Class UsuarioPreferenciaDAL
 * @package EmagineAuth\DAL
 * @tablename usuario_preferencia
 * @author EmagineCRUD
 */
class UsuarioPreferenciaDAL implements IUsuarioPreferenciaDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				usuario_preferencia.id_usuario,
				usuario_preferencia.chave,
				usuario_preferencia.valor
			FROM usuario_preferencia
		";
	}

	/**
     * @throws Exception
	 * @return UsuarioPreferenciaInfo[]
	 */
	public function listar() {
		$query = $this->query();
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioPreferenciaInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @return UsuarioPreferenciaInfo[]
	 */
	public function listarPorIdUsuario($id_usuario) {
		$query = $this->query() . "
			WHERE usuario_preferencia.id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioPreferenciaInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @param string $chave
	 * @return UsuarioPreferenciaInfo
	 */
	public function pegar($id_usuario, $chave) {
		$query = $this->query() . "
			WHERE usuario_preferencia.id_usuario = :id_usuario 
			AND usuario_preferencia.chave = :chave
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario);
		$db->bindValue(":chave", $chave);
		return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioPreferenciaInfo");
	}

    /**
     * @throws Exception
     * @param string $chave
     * @param string $valor
     * @return UsuarioPreferenciaInfo
     */
    public function pegarPorValor($chave, $valor) {
        $query = $this->query() . "
			WHERE usuario_preferencia.chave = :chave
			AND usuario_preferencia.valor = :valor
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":chave", $chave);
        $db->bindValue(":valor", $valor);
        return DB::getValueClass($db,"Emagine\\Login\\Model\\UsuarioPreferenciaInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param UsuarioPreferenciaInfo $preferencia
	 */
	public function preencherCampo(PDOStatement $db, UsuarioPreferenciaInfo $preferencia) {
		$db->bindValue(":id_usuario", $preferencia->getIdUsuario());
		$db->bindValue(":chave", $preferencia->getChave());
		$db->bindValue(":valor", $preferencia->getValor());
	}

	/**
     * @throws Exception
	 * @param UsuarioPreferenciaInfo $preferencia
	 */
	public function inserir($preferencia) {
		$query = "
			INSERT INTO usuario_preferencia (
				id_usuario,
				chave,
				valor
			) VALUES (
				:id_usuario,
				:chave,
				:valor
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $preferencia);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param UsuarioPreferenciaInfo $preferencia
	 */
	public function alterar($preferencia) {
		$query = "
			UPDATE usuario_preferencia SET 
				valor = :valor
			WHERE usuario_preferencia.id_usuario = :id_usuario 
			AND usuario_preferencia.chave = :chave
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $preferencia);
		$db->bindValue(":id_usuario", $preferencia->getIdUsuario(), PDO::PARAM_INT);
		$db->bindValue(":chave", $preferencia->getChave());
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @param string $chave
	 */
	public function excluir($id_usuario, $chave) {
		$query = "
			DELETE FROM usuario_preferencia
			WHERE id_usuario = :id_usuario 
			AND chave = :chave
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		$db->bindValue(":chave", $chave);
		$db->execute();
	}
	/**
     * @throws Exception
	 * @param int $id_usuario
	 */
	public function limpar($id_usuario) {
		$query = "
			DELETE FROM usuario_preferencia
			WHERE id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		$db->execute();
	}

}

