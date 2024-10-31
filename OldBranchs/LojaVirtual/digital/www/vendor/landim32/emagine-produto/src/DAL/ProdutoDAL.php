<?php
namespace Emagine\Produto\DAL;

use Emagine\Produto\Model\ProdutoFiltroInfo;
use Emagine\Produto\Model\ProdutoRetornoInfo;
use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Produto\Model\ProdutoInfo;

/**
 * Class ProdutoDAL
 * @package EmagineProduto\DAL
 * @tablename produto
 * @author EmagineCRUD
 */
class ProdutoDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT SQL_CALC_FOUND_ROWS
				produto.id_produto,
				produto.id_origem,
				produto.id_loja,
				produto.id_usuario,
				produto.id_categoria,
				produto.id_unidade,
				produto.data_inclusao,
				produto.ultima_alteracao,
				produto.slug,
				produto.codigo,
				produto.foto,
				produto.nome,
				produto.valor,
				produto.valor_promocao,
				produto.volume,
				produto.destaque,
				produto.descricao,
				produto.quantidade,
				produto.quantidade_vendido,
				produto.cod_situacao
			FROM produto
		";
	}

    /**
     * @throws Exception
     * @param ProdutoFiltroInfo $filtro
     * @return ProdutoRetornoInfo
     */
    public function buscar($filtro) {
        $palavrasChave = array();
        if (!isNullOrEmpty(trim($filtro->getPalavraChave()))) {
            $palavras = explode(" ", trim($filtro->getPalavraChave()));
            $i = 1;
            foreach ($palavras as $palavra) {
                $palavrasChave[":p" . $i] = '%' . $palavra . '%';
                $i++;
            }
        }
        $query = $this->query() . "
		    WHERE (1=1)
		";
        if (!is_null($filtro->getIdLoja())) {
            $query .= " AND produto.id_loja = :id_loja ";
        }
        if (!is_null($filtro->getIdUsuario())) {
            $query .= " AND produto.id_usuario = :id_usuario ";
        }
        if (!is_null($filtro->getIdCategoria())) {
            $query .= " AND produto.id_categoria = :id_categoria ";
        }
        if (!is_null($filtro->getDestaque())) {
            $query .= " AND produto.destaque = :destaque ";
        }
        if (!is_null($filtro->getCodSituacao())) {
            $query .= " AND produto.cod_situacao = :cod_situacao ";
        }
        if (!is_null($filtro->getApenasEstoque()) && $filtro->getApenasEstoque() == true) {
            $query .= " AND produto.quantidade > 0 ";
        }
        if (!is_null($filtro->getApenasPromocao())) {
            if ($filtro->getApenasPromocao() == true) {
                $query .= " AND produto.valor_promocao > 0 ";
            }
            else {
                $query .= " AND produto.valor_promocao <= 0 ";
            }
        }
        if (count($palavrasChave) > 0) {
            $queryParam = array();
            foreach ($palavrasChave as $key => $value) {
                $queryParam[] = "produto.nome LIKE " . $key;
            }
            $condicao = ($filtro->getCondicao()) ? " OR " : " AND ";
            $query .= " AND (" . implode($condicao, $queryParam) . ") ";
        }

        if (!is_null($filtro->getExibeOrigem())) {
            if ($filtro->getExibeOrigem() == true) {
                $query .= " AND (produto.id_origem IS NULL) ";
            }
            else {
                $query .= " AND (produto.id_origem IS NOT NULL) ";
            }
        }

        $query .= " ORDER BY produto.nome ";
        if ($filtro->getTamanhoPagina() > 0) {
            $pg = $filtro->getPagina() - 1;
            if ($pg < 0) $pg = 0;
            $pgini = ($pg * $filtro->getTamanhoPagina());
            $query .= " LIMIT " . $pgini . ", " . $filtro->getTamanhoPagina();
        }

        $db = DB::getDB()->prepare($query);
        if (!is_null($filtro->getIdLoja())) {
            $db->bindValue(":id_loja", $filtro->getIdLoja(), PDO::PARAM_INT);
        }
        if (!is_null($filtro->getIdUsuario())) {
            $db->bindValue(":id_usuario", $filtro->getIdUsuario(), PDO::PARAM_INT);
        }
        if (!is_null($filtro->getIdCategoria())) {
            $db->bindValue(":id_categoria", $filtro->getIdCategoria(), PDO::PARAM_INT);
        }
        if (!is_null($filtro->getDestaque())) {
            $db->bindValue(":destaque", ($filtro->getDestaque() == true), PDO::PARAM_BOOL);
        }
        if (!is_null($filtro->getCodSituacao())) {
            $db->bindValue(":cod_situacao", $filtro->getCodSituacao(), PDO::PARAM_INT);
        }
        if (count($palavrasChave) > 0) {
            foreach ($palavrasChave as $key => $value) {
                $db->bindValue($key, $value);
            }
        }
        $produtos = DB::getResult($db,"Emagine\\Produto\\Model\\ProdutoInfo");
        if ($filtro->getTamanhoPagina() > 0) {
            $total = DB::getDB()->query('SELECT FOUND_ROWS();')->fetch(PDO::FETCH_COLUMN);
        }
        else {
            $total = count($produtos);
        }
        return (new ProdutoRetornoInfo())
            ->setTamanhoPagina($filtro->getTamanhoPagina())
            ->setPagina($filtro->getPagina())
            ->setTotal($total)
            ->setProdutos($produtos);
    }

	/**
     * @throws Exception
	 * @param int $id_produto
	 * @return ProdutoInfo
	 */
	public function pegar($id_produto) {
		$query = $this->query() . "
			WHERE produto.id_produto = :id_produto
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_produto", $id_produto, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Produto\\Model\\ProdutoInfo");
	}

    /**
     * @param int $id_produto
     * @return int
     * @throws Exception
     */
    public function pegarQuantidade($id_produto) {
        $query = "
            SELECT produto.quantidade
            FROM produto 
            WHERE produto.id_produto = :id_produto
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_produto", $id_produto, PDO::PARAM_INT);
        $db->execute();
        return DB::getValue($db,"quantidade");
    }

    /**
     * @param int $id_produto
     * @param int $quantidade
     * @throws Exception
     */
    public function alterarQuantidade($id_produto, $quantidade) {
        $query = "
			UPDATE produto SET 
				quantidade = :quantidade
			WHERE produto.id_produto = :id_produto
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_produto", $id_produto, PDO::PARAM_INT);
        $db->bindValue(":quantidade", $quantidade, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $slug
     * @return ProdutoInfo
     */
    public function pegarPorSlug($id_loja, $slug) {
        $query = $this->query() . "
			WHERE produto.slug = :slug
			AND produto.id_loja = :id_loja
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        $db->bindValue(":slug", $slug);
        return DB::getValueClass($db,"Emagine\\Produto\\Model\\ProdutoInfo");
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $codigo
     * @return ProdutoInfo
     */
    public function pegarPorCodigo($id_loja, $codigo) {
        $query = $this->query() . "
			WHERE produto.id_loja = :id_loja
			AND produto.codigo = :codigo
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        $db->bindValue(":codigo", $codigo);
        return DB::getValueClass($db,"Emagine\\Produto\\Model\\ProdutoInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param ProdutoInfo $produto
	 */
	public function preencherCampo(PDOStatement $db, ProdutoInfo $produto) {
        if (!is_null($produto->getIdOrigem()) && $produto->getIdOrigem() > 0) {
            $db->bindValue(":id_origem", $produto->getIdOrigem(), PDO::PARAM_INT);
        }
        else {
            $db->bindValue(":id_origem", null);
        }
		$db->bindValue(":id_usuario", $produto->getIdUsuario());
		$db->bindValue(":id_categoria", $produto->getIdCategoria());
		if ($produto->getIdUnidade() > 0) {
            $db->bindValue(":id_unidade", $produto->getIdUnidade());
        }
        else {
            $db->bindValue(":id_unidade", null);
        }
		$db->bindValue(":slug", $produto->getSlug());
		$db->bindValue(":codigo", $produto->getCodigo());
		$db->bindValue(":foto", $produto->getFoto());
		$db->bindValue(":nome", $produto->getNome());
		$db->bindValue(":valor", number_format($produto->getValor(), 2, ".", ""));
		$db->bindValue(":valor_promocao", number_format($produto->getValorPromocao(), 2, ".", ""));
		$db->bindValue(":volume", number_format($produto->getVolume(), 2, ".", ""));
		$db->bindValue(":destaque", ($produto->getDestaque() == true), PDO::PARAM_BOOL);
		$db->bindValue(":descricao", $produto->getDescricao());
		$db->bindValue(":quantidade", $produto->getQuantidade(), PDO::PARAM_INT);
		$db->bindValue(":cod_situacao", $produto->getCodSituacao());
	}

	/**
     * @throws Exception
	 * @param ProdutoInfo $produto
	 * @return int
	 */
	public function inserir($produto) {
		$query = "
			INSERT INTO produto (
			    id_origem,
				id_loja,
				id_usuario,
				id_categoria,
				id_unidade,
				data_inclusao,
				ultima_alteracao,
				slug,
				codigo,
				foto,
				nome,
				valor,
				valor_promocao,
				volume,
				destaque,
				descricao,
				quantidade,
				cod_situacao
			) VALUES (
			    :id_origem,
				:id_loja,
				:id_usuario,
				:id_categoria,
				:id_unidade,
				NOW(),
				NOW(),
				:slug,
				:codigo,
				:foto,
				:nome,
				:valor,
				:valor_promocao,
				:volume,
				:destaque,
				:descricao,
				:quantidade,
				:cod_situacao
			)
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $produto->getIdLoja(), PDO::PARAM_INT);
		$this->preencherCampo($db, $produto);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param ProdutoInfo $produto
	 */
	public function alterar($produto) {
		$query = "
			UPDATE produto SET
			    id_origem = :id_origem, 
				id_usuario = :id_usuario,
				id_categoria = :id_categoria,
				id_unidade = :id_unidade,
				ultima_alteracao = NOW(),
				slug = :slug,
				codigo = :codigo,
				foto = :foto,
				nome = :nome,
				valor = :valor,
				valor_promocao = :valor_promocao,
				volume = :volume,
				destaque = :destaque,
				descricao = :descricao,
				quantidade = :quantidade,
				cod_situacao = :cod_situacao
			WHERE produto.id_produto = :id_produto
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $produto);
		$db->bindValue(":id_produto", $produto->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_produto
	 */
	public function excluir($id_produto) {
		$query = "
			DELETE FROM produto
			WHERE id_produto = :id_produto
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_produto", $id_produto, PDO::PARAM_INT);
		$db->execute();
	}
	/**
     * @throws Exception
	 * @param int $id_usuario
	 */
	public function limparPorIdUsuario($id_usuario) {
		$query = "
			DELETE FROM produto
			WHERE id_usuario = :id_usuario
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_categoria
	 */
	public function limparPorIdCategoria($id_categoria) {
		$query = "
			DELETE FROM produto
			WHERE id_categoria = :id_categoria
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_categoria", $id_categoria, PDO::PARAM_INT);
		$db->execute();
	}

    /**
     * @throws Exception
     * @param int $id_categoria
     * @return int
     */
    public function pegarQuantidadePorCategoria($id_categoria) {
        $query = "
            SELECT COUNT(*) AS 'quantidade'
            FROM produto
			WHERE produto.id_categoria = :id_categoria 
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
            FROM produto
			WHERE produto.id_loja = :id_loja 
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        return intval(DB::getValue($db,"quantidade"));
    }

    /**
     * @throws Exception
     * @param int $id_produto
     * @return int
     */
    public function pegarQuantidadePedido($id_produto) {
        $query = "
            SELECT COUNT(pedido_item.id_pedido) AS 'quantidade'
            FROM pedido_item
			WHERE pedido_item.id_produto = :id_produto 
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_produto", $id_produto, PDO::PARAM_INT);
        return intval(DB::getValue($db,"quantidade"));
    }

}

