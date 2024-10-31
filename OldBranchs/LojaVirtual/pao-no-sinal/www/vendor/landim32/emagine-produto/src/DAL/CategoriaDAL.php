<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 09/11/2017
 * Time: 22:46
 * Tablename: produto_categoria
 */

namespace Emagine\Produto\DAL;

use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Produto\Model\CategoriaInfo;

/**
 * Class ProdutoCategoriaDAL
 * @package EmagineProduto\DAL
 * @tablename produto_categoria
 * @author EmagineCRUD
 */
class CategoriaDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				produto_categoria.id_categoria,
				produto_categoria.id_loja,
				produto_categoria.id_pai,
				produto_categoria.nome,
				produto_categoria.nome_completo,
				produto_categoria.slug,
				produto_categoria.foto
			FROM produto_categoria
		";
	}

	/**
     * @throws Exception
     * @param int $id_loja
     * @param int|null $id_pai
	 * @return CategoriaInfo[]
	 */
	public function listar($id_loja, $id_pai = null) {
		$query = $this->query();
		$query .= " WHERE produto_categoria.id_loja = :id_loja ";
		if (!is_null($id_pai)) {
		    $query .= " AND produto_categoria.id_pai = :id_pai ";
        }
		$query .= " ORDER BY produto_categoria.nome_completo";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        if (!is_null($id_pai)) {
            $db->bindValue(":id_pai", $id_pai, PDO::PARAM_INT);
        }
		return DB::getResult($db,"Emagine\\Produto\\Model\\CategoriaInfo");
	}

    /**
     * @throws Exception
     * @param int $id_loja
     * @return CategoriaInfo[]
     */
    public function listarPai($id_loja) {
        $query = $this->query();
        $query .= " 
            WHERE produto_categoria.id_loja = :id_loja
            AND produto_categoria.id_pai IS NULL 
            ORDER BY produto_categoria.nome_completo
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Produto\\Model\\CategoriaInfo");
    }

	/**
     * @throws \Exception
	 * @param int $id_categoria
	 * @return CategoriaInfo
	 */
	public function pegar($id_categoria) {
		$query = $this->query() . "
			WHERE produto_categoria.id_categoria = :id_categoria
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_categoria", $id_categoria, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Produto\\Model\\CategoriaInfo");
	}

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $slug
     * @return CategoriaInfo
     */
    public function pegarPorSlug($id_loja, $slug) {
        $query = $this->query() . "
			WHERE produto_categoria.id_loja = :id_loja 
			AND produto_categoria.slug = :slug
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        $db->bindValue(":slug", $slug);
        return DB::getValueClass($db,"Emagine\\Produto\\Model\\CategoriaInfo");
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $nome
     * @return CategoriaInfo
     */
    public function pegarPorNome($id_loja, $nome) {
        $query = $this->query() . "
			WHERE produto_categoria.id_loja = :id_loja 
			AND produto_categoria.nome = :nome
			LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        $db->bindValue(":nome", $nome);
        return DB::getValueClass($db,"Emagine\\Produto\\Model\\CategoriaInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param CategoriaInfo $categoria
	 */
	public function preencherCampo(PDOStatement $db, CategoriaInfo $categoria) {
	    $id_pai = $categoria->getIdPai() > 0 ? $categoria->getIdPai() : null;
        $db->bindValue(":id_pai", $id_pai, PDO::PARAM_INT);
		$db->bindValue(":nome", $categoria->getNome());
		$db->bindValue(":nome_completo", $categoria->getNomeCompleto());
		$db->bindValue(":slug", $categoria->getSlug());
		$db->bindValue(":foto", $categoria->getFoto());
	}

	/**
     * @throws Exception
	 * @param CategoriaInfo $categoria
	 * @return int
	 */
	public function inserir($categoria) {
		$query = "
			INSERT INTO produto_categoria (
			    id_loja,
			    id_pai,
				nome,
				nome_completo,
				slug,
				foto
			) VALUES (
			    :id_loja,
			    :id_pai,
				:nome,
				:nome_completo,
				:slug,
				:foto
			)
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $categoria->getIdLoja(), PDO::PARAM_INT);
		$this->preencherCampo($db, $categoria);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param CategoriaInfo $categoria
	 */
	public function alterar($categoria) {
		$query = "
			UPDATE produto_categoria SET 
			    id_pai = :id_pai,
				nome = :nome,
				nome_completo = :nome_completo,
				slug = :slug,
				foto = :foto
			WHERE produto_categoria.id_categoria = :id_categoria
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $categoria);
		$db->bindValue(":id_categoria", $categoria->getId(), PDO::PARAM_INT);
		$db->execute();
	}

    /**
     * @throws Exception
     * @param int $id_categoria
     * @return int
     */
    public function pegarQuantidadeFilho($id_categoria) {
        $query = "
            SELECT COUNT(*) AS 'quantidade'
            FROM produto_categoria
			WHERE produto_categoria.id_pai = :id_categoria 
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_categoria", $id_categoria, PDO::PARAM_INT);
        return intval(DB::getValue($db,"quantidade"));
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @return int
     */
    public function pegarQuantidadePorLoja($id_loja) {
        $query = "
            SELECT COUNT(*) AS 'quantidade'
            FROM produto_categoria
			WHERE produto_categoria.id_loja = :id_loja 
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        return intval(DB::getValue($db,"quantidade"));
    }

	/**
     * @throws Exception
	 * @param int $id_categoria
	 */
	public function excluir($id_categoria) {
		$query = "
			DELETE FROM produto_categoria
			WHERE id_categoria = :id_categoria
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_categoria", $id_categoria, PDO::PARAM_INT);
		$db->execute();
	}
}

