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
use Emagine\Login\IDAL\IUsuarioEnderecoDAL;
use Emagine\Login\Model\UsuarioEnderecoInfo;

class UsuarioEnderecoDAL implements IUsuarioEnderecoDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				usuario_endereco.id_endereco,
				usuario_endereco.id_usuario,
				usuario_endereco.cep,
				usuario_endereco.logradouro,
				usuario_endereco.complemento,
				usuario_endereco.numero,
				usuario_endereco.bairro,
				usuario_endereco.cidade,
				usuario_endereco.uf,
				usuario_endereco.latitude,
				usuario_endereco.longitude
			FROM usuario_endereco
		";
	}

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @return UsuarioEnderecoInfo[]
	 */
	public function listar($id_usuario) {
		$query = $this->query() . "
			WHERE usuario_endereco.id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Login\\Model\\UsuarioEnderecoInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param UsuarioEnderecoInfo $endereco
	 */
	public function preencherCampo(PDOStatement $db, UsuarioEnderecoInfo $endereco) {
        $db->bindValue(":cep", $endereco->getCep());
		$db->bindValue(":logradouro", $endereco->getLogradouro());
        $db->bindValue(":complemento", $endereco->getComplemento());
        $db->bindValue(":numero", $endereco->getNumero());
        $db->bindValue(":bairro", $endereco->getBairro());
        $db->bindValue(":cidade", $endereco->getCidade());
        $db->bindValue(":uf", $endereco->getUf());
        $db->bindValue(":latitude", $endereco->getLatitude());
        $db->bindValue(":longitude", $endereco->getLongitude());
	}

	/**
     * @throws Exception
	 * @param UsuarioEnderecoInfo $endereco
	 */
	public function inserir($endereco) {
		$query = "
			INSERT INTO usuario_endereco (
				id_usuario,
				cep,
				logradouro,
				complemento,
				numero,
				bairro,
				cidade,
				uf,
				latitude,
				longitude
			) VALUES (
				:id_usuario,
				:cep,
				:logradouro,
				:complemento,
				:numero,
				:bairro,
				:cidade,
				:uf,
				:latitude,
				:longitude
			)
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_usuario", $endereco->getIdUsuario(), PDO::PARAM_INT);
		$this->preencherCampo($db, $endereco);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param UsuarioEnderecoInfo $endereco
	 */
	public function alterar($endereco) {
		$query = "
			UPDATE usuario_endereco SET
			    cep = :cep, 
				logradouro = :logradouro,
				complemento = :complemento,
				numero = :numero,
				bairro = :bairro,
				cidade = :cidade,
				uf = :uf,
				latitude = :latitude,
				longitude = :longitude
			WHERE usuario_endereco.id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
        $this->preencherCampo($db, $endereco);
        $db->bindValue(":id_endereco", $endereco->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_endereco
	 */
	public function excluir($id_endereco) {
		$query = "
			DELETE FROM usuario_endereco
			WHERE id_endereco = :id_endereco
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_endereco", $id_endereco, PDO::PARAM_INT);
		$db->execute();
	}
	/**
     * @throws Exception
	 * @param int $id_usuario
	 */
	public function limpar($id_usuario) {
		$query = "
			DELETE FROM usuario_endereco
			WHERE id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		$db->execute();
	}

}

