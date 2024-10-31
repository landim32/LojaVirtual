<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 09/11/2017
 * Time: 22:49
 * Tablename: loja
 */

namespace Emagine\Produto\DAL;

use Emagine\Produto\Model\UsuarioLojaInfo;
use PDO;
use PDOStatement;
use Landim32\EasyDB\DB;
use Emagine\Produto\Model\LojaInfo;

/**
 * Class UsuarioLojaDAL
 * @package EmagineProduto\DAL
 * @tablename loja
 * @author EmagineCRUD
 */
class UsuarioLojaDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				loja_usuario.id_loja,
				loja_usuario.id_usuario
			FROM loja_usuario
		";
	}

	/**
     * @throws \Exception
     * @param int $id_loja
	 * @return UsuarioLojaInfo[]
	 */
	public function listar($id_loja) {
		$query = $this->query() . "
		    WHERE loja_usuario.id_loja = :id_loja
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Produto\\Model\\UsuarioLojaInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param UsuarioLojaInfo $usuario
	 */
	public function preencherCampo(PDOStatement $db, UsuarioLojaInfo $usuario) {
		$db->bindValue(":id_loja", $usuario->getIdLoja(), PDO::PARAM_INT);
        $db->bindValue(":id_usuario", $usuario->getIdUsuario(), PDO::PARAM_INT);
	}

	/**
     * @throws \Exception
	 * @param UsuarioLojaInfo $usuario
	 * @return int
	 */
	public function inserir($usuario) {
		$query = "
			INSERT INTO loja_usuario (
				id_loja,
				id_usuario
			) VALUES (
				:id_loja,
				:id_usuario
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $usuario);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws \Exception
	 * @param int $id_loja
	 */
	public function limpar($id_loja) {
		$query = "
			DELETE FROM loja_usuario
			WHERE id_loja = :id_loja
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
		$db->execute();
	}
}

