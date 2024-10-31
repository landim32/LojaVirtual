<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 09/11/2017
 * Time: 22:46
 * Tablename: produto_categoria
 */

namespace Emagine\Produto\BLL;

use Emagine\Produto\Ex\CategoriaException;
use Emagine\Produto\Model\LojaInfo;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Produto\DAL\CategoriaDAL;
use Emagine\Produto\Model\CategoriaInfo;

/**
 * Class ProdutoCategoriaBLL
 * @package EmagineProduto\BLL
 * @tablename produto_categoria
 * @author EmagineCRUD
 */
class CategoriaBLL {

    /**
     * @return bool
     */
    public static function usaCategoriaRoute() {
        if (defined("USA_PRODUTO_CATEGORIA_ROUTE")) {
            return (USA_PRODUTO_CATEGORIA_ROUTE == true);
        }
        return true;
    }

	/**
     * @throws Exception
     * @param int $id_loja
	 * @return CategoriaInfo[]
	 */
	public function listar($id_loja) {
		$dal = new CategoriaDAL();
		return $dal->listar($id_loja);
	}

    /**
     * @throws Exception
     * @param int $id_loja
     * @return CategoriaInfo[]
     */
    public function listarPai($id_loja) {
        $dal = new CategoriaDAL();
        return $dal->listarPai($id_loja);
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param int $id_categoria
     * @return CategoriaInfo[]
     */
	public function listarFilho($id_loja, $id_categoria) {
        $dal = new CategoriaDAL();
        return $dal->listar($id_loja, $id_categoria);
    }


	/**
     * @throws Exception
	 * @param int $id_categoria
	 * @return CategoriaInfo
	 */
	public function pegar($id_categoria) {
		$dal = new CategoriaDAL();
		return $dal->pegar($id_categoria);
	}

    /**
     * @throws Exception
     * @param int $id_categoria
     * @return int
     */
    public function pegarQuantidadeFilho($id_categoria) {
        $dal = new CategoriaDAL();
        return $dal->pegarQuantidadeFilho($id_categoria);
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $slug
     * @return CategoriaInfo
     */
    public function pegarPorSlug($id_loja, $slug) {
        $dal = new CategoriaDAL();
        return $dal->pegarPorSlug($id_loja, $slug);
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $nome
     * @return CategoriaInfo
     */
    public function pegarPorNome($id_loja, $nome) {
        $dal = new CategoriaDAL();
        return $dal->pegarPorNome($id_loja, $nome);
    }

    /**
     * @param array<string,string> $postData
     * @param CategoriaInfo $categoria
     */
    public function pegarDoPost($postData, &$categoria = null) {
        if (is_null($categoria)) {
            $categoria = new CategoriaInfo();
        }
        if (array_key_exists("id_categoria", $postData)) {
            $categoria->setId($postData["id_categoria"]);
        }
        if (array_key_exists("id_pai", $postData)) {
            $categoria->setIdPai($postData["id_pai"]);
        }
        if (array_key_exists("nome", $postData)) {
            $categoria->setNome($postData["nome"]);
        }
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param int $id_categoria
     * @param string $slug
     * @return string
     */
    private function slugValido($id_loja, $id_categoria, $slug)
    {
        $slug = strtolower($slug);
        $loja = $this->pegarPorSlug($id_loja, $slug);
        if (!is_null($loja) && $loja->getId() != $id_categoria) {
            $out = array();
            preg_match_all("#(.*?)-(\d{1,2})#i", $slug, $out, PREG_PATTERN_ORDER);
            if (!isNullOrEmpty($out[1][0]) && is_numeric($out[2][0])) {
                $slug = $this->slugValido($id_loja, $id_categoria, $out[1][0] . "-" . (intval($out[2][0]) + 1));
            }
            else {
                $slug = $this->slugValido($id_loja, $id_categoria, $slug . '-2');
            }
        }
        return $slug;
    }

	/**
	 * @throws Exception
	 * @param CategoriaInfo $categoria
	 */
	protected function validar(&$categoria) {
		if (isNullOrEmpty($categoria->getNome())) {
			throw new Exception('Preencha o nome.');
		}
        if (!($categoria->getIdLoja() > 0)) {
            throw new Exception('Nenhuma loja informada.');
        }
		if ($categoria->getIdPai() > 0) {
		    $pai = $this->pegar($categoria->getIdPai());
            $categoria->setNomeCompleto( $pai->getNomeCompleto() . "/" . $categoria->getNome());
        }
        else {
		    $categoria->setNomeCompleto("/" . $categoria->getNome());
        }
        $slug = substr( $categoria->getNomeCompleto(), 1);
        $slug = str_replace("/", "-", $slug);
        $slug = sanitize_slug(strtolower(trim($slug)));
        $categoria->setSlug($this->slugValido($categoria->getIdLoja(), $categoria->getId(), $slug));
	}

	/**
	 * @throws Exception
	 * @param CategoriaInfo $categoria
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($categoria, $transaction = true) {
		$this->validar($categoria);
		$dal = new CategoriaDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$id_categoria = $dal->inserir($categoria);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
		return $id_categoria;
	}

	/**
	 * @throws Exception
	 * @param CategoriaInfo $categoria
	 * @param bool $transaction
	 */
	public function alterar($categoria, $transaction = true) {
		$this->validar($categoria);
		$dal = new CategoriaDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($categoria);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
	}

	/**
	 * @throws Exception
	 * @param int $id_categoria
	 * @param bool $transaction
	 */
	public function excluir($id_categoria, $transaction = true) {
		$dal = new CategoriaDAL();
		$regraProduto = new ProdutoBLL();
		$quantidade = $dal->pegarQuantidadeFilho($id_categoria);
		if ($quantidade > 0) {
		    $mensagemBase = "Essa categoria tem %s subcategoria(s). Exclua-a(s) antes de excluir essa.";
		    throw new CategoriaException(sprintf($mensagemBase, $quantidade));
        }
        $quantidade = $regraProduto->pegarQuantidadePorCategoria($id_categoria);
        if ($quantidade > 0) {
            $mensagemBase = "Essa categoria tem %s produto(s) vincilado(s). Exclua-o(s) antes de excluir essa categoria.";
            throw new CategoriaException(sprintf($mensagemBase, $quantidade));
        }
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($id_categoria);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
	}

    /**
     * @throws Exception
     * @param int $id_loja
     * @return int
     */
    public function pegarQuantidadePorLoja($id_loja) {
        $dal = new CategoriaDAL();
        return $dal->pegarQuantidadePorLoja($id_loja);
    }

    /**
     * @throws Exception
     * @param string $urlFormato
     * @param CategoriaInfo[] $categorias
     * @return string
     */
	public function gerarMenu($urlFormato, $categorias) {
	    $str = "<ul class=\"nav navbar-nav\">";
	    foreach ($categorias as $categoriaPai) {
	        $filhos = $categoriaPai->listarFilho();
	        if (count($filhos) > 0) {
                $str .= "<li class=\"dropdown\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" " .
                    "role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">" . $categoriaPai->getNome() .
                    " <span class=\"caret\"></span></a><ul class=\"dropdown-menu\">";
                foreach ($filhos as $categoriaFilho) {
                    $url = sprintf( $urlFormato, $categoriaFilho->getSlug());
                    $str .= "<li><a href=\"" . $url . "\"><span class=\"badge pull-right\">" . $categoriaFilho->getQuantidade() .
                        "</span>" . $categoriaFilho->getNome() . "</a></li>\n";
                }
                $str .= "</ul></li>";
            }
            else {
	            $url = sprintf( $urlFormato, $categoriaPai->getSlug());
	            $str .= "<li><a href=\"" . $url . "\">" . $categoriaPai->getNome() . "</a></li>\n";
            }
        }
        $str .= "</ul>";
	    return $str;
    }

    /**
     * @throws Exception
     * @param CategoriaInfo $categoria
     * @param string $homeUrl
     * @param string $categoriaUrl
     * @return string
     */
    public function gerarBreadcrumb($categoria, $homeUrl, $categoriaUrl) {
	    $str = "<ol class=\"breadcrumb\">\n";
	    $categorias = array();
	    $categoriaAtual = $categoria;
	    while (!is_null($categoriaAtual)) {
	        array_push($categorias, $categoriaAtual);
	        $categoriaAtual = $categoriaAtual->getPai();
        }
        $categorias = array_reverse($categorias);
	    $str .= "<li><a href='" . $homeUrl . "'><i class='fa fa-home'></i> Home</a></li>\n";
	    /** @var CategoriaInfo $categoriaItem */
        foreach ($categorias as $categoriaItem) {
            $str .= "<li";
            if ($categoriaItem->getId() == $categoria->getId()) {
                $str .= " class='active'";
            }
            $str .= "><a href='" . sprintf($categoriaUrl, $categoria->getSlug()) .
                "'><i class='fa fa-chevron-right'></i> " . $categoriaItem->getNome() . "</a></li>\n";
        }
        $str .= "</ol>";
        return $str;
    }

    /**
     * @param LojaInfo $origem
     * @param LojaInfo $destino
     * @throws Exception
     */
    public function copiar(LojaInfo $origem, LojaInfo $destino) {
        $categorias = $this->listarPai($origem->getId());
        foreach ($categorias as $categoria) {
            $subCategorias = $this->listarFilho($origem->getId(), $categoria->getId());

            $categoria->setIdLoja($destino->getId());
            $categoria->setId(0);
            $novaCategoria = $this->pegarPorSlug($destino->getId(), $categoria->getSlug());
            if (!is_null($novaCategoria)) {
                $id_categoria = $novaCategoria->getId();
            }
            else {
                $id_categoria = $this->inserir($categoria);
            }

            foreach ($subCategorias as $subCategoria) {
                $subCategoria->setIdLoja($destino->getId());
                $subCategoria->setIdPai($id_categoria);
                $subCategoria->setId(0);
                $novaSubCategoria = $this->pegarPorSlug($destino->getId(), $subCategoria->getSlug());
                if (is_null($novaSubCategoria)) {
                    $this->inserir($subCategoria);
                }
            }
        }
    }

}

