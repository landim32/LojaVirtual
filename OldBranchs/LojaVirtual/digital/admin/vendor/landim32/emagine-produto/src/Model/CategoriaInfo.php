<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 09/11/2017
 * Time: 22:46
 * Tablename: produto_categoria
 */

namespace Emagine\Produto\Model;

use Emagine\Produto\BLL\ProdutoBLL;
use stdClass;
use JsonSerializable;
use Exception;
use Emagine\Produto\BLL\CategoriaBLL;

/**
 * Class CategoriaInfo
 * @package EmagineProduto\Model
 * @tablename produto_categoria
 * @author EmagineCRUD
 */
class CategoriaInfo implements JsonSerializable {

    const GERENCIAR_CATEGORIA = "gerenciar-categoria";

	private $id_categoria;
	private $id_loja;
	private $id_pai;
	private $nome;
	private $nome_completo;
	private $slug;
	private $foto;
	private $categoria = null;
	private $quantidade = null;
	private $filhos = null;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_categoria;
	}

	/**
	 * @param int $value
	 */
	public function setId($value) {
		$this->id_categoria = $value;
	}

    /**
     * @return int
     */
	public function getIdLoja() {
	    return $this->id_loja;
    }

    /**
     * @param int $value
     */
    public function setIdLoja($value) {
	    $this->id_loja = $value;
    }

    /**
     * @return int
     */
	public function getIdPai() {
	    return $this->id_pai;
    }

    /**
     * @param int $value
     */
    public function setIdPai($value) {
	    $this->id_pai = $value;
    }

	/**
	 * @return string
	 */
	public function getNome() {
		return $this->nome;
	}

	/**
	 * @param string $value
	 */
	public function setNome($value) {
		$this->nome = $value;
	}

	/**
	 * @return string
	 */
	public function getNomeCompleto() {
		return $this->nome_completo;
	}

	/**
	 * @param string $value
	 */
	public function setNomeCompleto($value) {
		$this->nome_completo = $value;
	}

	/**
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @param string $value
	 */
	public function setSlug($value) {
		$this->slug = $value;
	}

	/**
	 * @return string
	 */
	public function getFoto() {
		return $this->foto;
	}

	/**
	 * @param string $value
	 */
	public function setFoto($value) {
		$this->foto = $value;
	}

    /**
     * @throws Exception
     * @return int
     */
	public function getQuantidade() {
        if (is_null($this->quantidade) && $this->getId() > 0) {
            $regraProduto = new ProdutoBLL();
            $this->quantidade = $regraProduto->pegarQuantidadePorCategoria($this->getId());
        }
	    return $this->quantidade;
    }

    /**
     * @throws Exception
     * @return CategoriaInfo[]
     */
    public function listarFilho() {
        if (is_null($this->filhos) && $this->getIdLoja() > 0 && $this->getId() > 0) {
            $regraCategoria = new CategoriaBLL();
            $this->filhos = $regraCategoria->listarFilho($this->getIdLoja(), $this->getId());
        }
        return $this->filhos;
    }

    /**
     * @throws Exception
     * @return CategoriaInfo|null
     */
    public function getPai() {
        if (is_null($this->categoria) && $this->getIdPai() > 0) {
            $regraCategoria = new CategoriaBLL();
            $this->categoria = $regraCategoria->pegar($this->getIdPai());
        }
        return $this->categoria;
    }

    /**
     * @param int $largura
     * @param int $altura
     * @return string
     * @throws Exception
     */
    public function getFotoUrl($largura = 120, $altura = 120) {
        if (!defined("SITE_URL")) {
            throw new Exception("SITE_URL não foi definido.");
        }
        if (isNullOrEmpty($this->getFoto())) {
            return SITE_URL . sprintf("/img/%sx%s/anonimo.png", $largura, $altura);
        }
        return SITE_URL . sprintf("/categoria/%sx%s/", $largura, $altura) . $this->getFoto();
    }

    /**
     * @param CategoriaInfo $categoria
     */
    public function clonarDe(CategoriaInfo $categoria) {
        $this->setId($categoria->getId());
        $this->setIdLoja($categoria->getIdLoja());
        $this->setIdPai($categoria->getIdPai());
        $this->setNome($categoria->getNome());
        $this->setNomeCompleto($categoria->getNomeCompleto());
        $this->setSlug($categoria->getSlug());
        $this->setFoto($categoria->getFoto());
    }

	/**
     * @throws Exception
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_categoria = $this->getId();
        $value->id_loja = $this->getIdLoja();
        $value->id_pai = $this->getIdPai();
		$value->nome = $this->getNome();
		$value->nome_completo = $this->getNomeCompleto();
		$value->slug = $this->getSlug();
		$value->foto = $this->getFoto();
		$value->foto_url = $this->getFotoUrl();
		$value->quantidade = $this->getQuantidade();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return CategoriaInfo
	 */
	public static function fromJson($value) {
		$categoria = new CategoriaInfo();
		$categoria->setId($value->id_categoria);
        $categoria->setIdLoja($value->id_loja);
        $categoria->setIdPai($value->id_pai);
		$categoria->setNome($value->nome);
		$categoria->setNomeCompleto($value->nome_completo);
		$categoria->setSlug($value->slug);
		$categoria->setFoto($value->foto);
		return $categoria;
	}

}

