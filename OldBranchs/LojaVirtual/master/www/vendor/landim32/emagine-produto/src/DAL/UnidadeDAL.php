<?php
namespace Emagine\Produto\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Produto\Model\UnidadeInfo;

/**
 * Class ProdutoCategoriaDAL
 * @package EmagineProduto\DAL
 * @tablename produto_categoria
 * @author EmagineCRUD
 */
class UnidadeDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				produto_unidade.id_unidade,
				produto_unidade.id_loja,
				produto_unidade.slug,
				produto_unidade.nome
			FROM produto_unidade
		";
	}

	/**
     * @throws Exception
     * @param int $id_loja
	 * @return UnidadeInfo[]
	 */
	public function listar($id_loja) {
		$query = $this->query() . " 
		    WHERE produto_unidade.id_loja = :id_loja 
		    ORDER BY produto_unidade.nome
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Produto\\Model\\UnidadeInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_unidade
	 * @return UnidadeInfo
	 */
	public function pegar($id_unidade) {
		$query = $this->query() . "
			WHERE produto_unidade.id_unidade = :id_unidade
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_unidade", $id_unidade, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Produto\\Model\\UnidadeInfo");
	}

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $slug
     * @return UnidadeInfo
     */
    public function pegarPorSlug($id_loja, $slug) {
        $query = $this->query() . "
			WHERE produto_unidade.id_loja = :id_loja 
			AND produto_unidade.slug = :slug
			LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        $db->bindValue(":slug", $slug);
        return DB::getValueClass($db,"Emagine\\Produto\\Model\\UnidadeInfo");
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $nome
     * @return UnidadeInfo
     */
    public function pegarPorNome($id_loja, $nome) {
        $query = $this->query() . "
			WHERE produto_unidade.id_loja = :id_loja 
			AND produto_unidade.nome = :nome
			LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        $db->bindValue(":nome", $nome);
        return DB::getValueClass($db,"Emagine\\Produto\\Model\\UnidadeInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param UnidadeInfo $unidade
	 */
	public function preencherCampo(PDOStatement $db, UnidadeInfo $unidade) {
        $db->bindValue(":slug", $unidade->getSlug());
		$db->bindValue(":nome", $unidade->getNome());
	}

	/**
     * @throws Exception
	 * @param UnidadeInfo $unidade
	 * @return int
	 */
	public function inserir($unidade) {
		$query = "
			INSERT INTO produto_unidade (
			    id_loja,
			    slug,
			    nome
			) VALUES (
			    :id_loja,
			    :slug,
			    :nome
			)
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $unidade->getIdLoja(), PDO::PARAM_INT);
		$this->preencherCampo($db, $unidade);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param UnidadeInfo $unidade
	 */
	public function alterar($unidade) {
		$query = "
			UPDATE produto_unidade SET 
			    slug = :slug,
			    nome = :nome
			WHERE id_unidade = :id_unidade
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_unidade", $unidade->getId(), PDO::PARAM_INT);
		$this->preencherCampo($db, $unidade);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_unidade
	 */
	public function excluir($id_unidade) {
		$query = "
			DELETE FROM produto_unidade
			WHERE id_unidade = :id_unidade
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_unidade", $id_unidade, PDO::PARAM_INT);
		$db->execute();
	}
}

