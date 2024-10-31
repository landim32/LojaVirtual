<?php

namespace Emagine\Produto\Model;

use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\UnidadeBLL;
use stdClass;
use JsonSerializable;
use Exception;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Produto\BLL\CategoriaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Login\Model\UsuarioInfo;

/**
 * Class ProdutoInfo
 * @package EmagineProduto\Model
 * @tablename produto
 * @author EmagineCRUD
 */
class ProdutoInfo implements JsonSerializable
{
    const GERENCIAR_PRODUTO = "gerenciar-produto";

    const ATIVO = 1;
    const INATIVO = 2;

    private $id_produto;
    private $id_origem;
    private $id_loja;
    private $id_usuario;
    private $id_categoria;
    private $id_unidade;
    private $data_inclusao;
    private $ultima_alteracao;
    private $slug;
    private $codigo;
    private $foto;
    private $foto_base64;
    private $nome;
    private $valor;
    private $valor_promocao;
    private $volume;
    private $destaque;
    private $descricao;
    private $quantidade;
    private $quantidade_vendido;
    private $cod_situacao;
    private $usuario = null;
    private $loja = null;
    private $categoria = null;
    private $unidade = null;

    /**
     * Auto Increment Field
     * @return int
     */
    public function getId()
    {
        return $this->id_produto;
    }

    /**
     * @param int $value
     */
    public function setId($value)
    {
        $this->id_produto = $value;
    }

    /**
     * @return int|null
     */
    public function getIdOrigem() {
        return $this->id_origem;
    }

    /**
     * @param int|null $value
     */
    public function setIdOrigem($value) {
        $this->id_origem = $value;
    }

    /**
     * @return int
     */
    public function getIdLoja()
    {
        return $this->id_loja;
    }

    /**
     * @param int $value
     */
    public function setIdLoja($value)
    {
        $this->id_loja = $value;
    }

    /**
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * @param int $value
     */
    public function setIdUsuario($value)
    {
        $this->id_usuario = $value;
    }

    /**
     * @throws Exception
     * @return UsuarioInfo
     */
    public function getUsuario()
    {
        if (is_null($this->usuario) && $this->getIdUsuario() > 0) {
            $bll = new UsuarioBLL();
            $this->usuario = $bll->pegar($this->getIdUsuario());
        }
        return $this->usuario;
    }

    /**
     * @return int
     */
    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    /**
     * @param int $value
     */
    public function setIdCategoria($value)
    {
        $this->id_categoria = $value;
    }

    /**
     * @throws Exception
     * @return CategoriaInfo
     */
    public function getCategoria()
    {
        if (is_null($this->categoria) && $this->getIdCategoria() > 0) {
            $bll = new CategoriaBLL();
            $this->categoria = $bll->pegar($this->getIdCategoria());
        }
        return $this->categoria;
    }

    /**
     * @return int
     */
    public function getIdUnidade()
    {
        return $this->id_unidade;
    }

    /**
     * @param int $value
     */
    public function setIdUnidade($value)
    {
        $this->id_unidade = $value;
    }

    /**
     * @throws Exception
     * @return UnidadeInfo
     */
    public function getUnidade()
    {
        if (is_null($this->unidade) && $this->getIdUnidade() > 0) {
            $bll = new UnidadeBLL();
            $this->unidade = $bll->pegar($this->getIdUnidade());
        }
        return $this->unidade;
    }

    /**
     * @return string
     */
    public function getDataInclusao() {
        return $this->data_inclusao;
    }

    /**
     * @param string $value
     */
    public function setDataInclusao($value) {
        $this->data_inclusao = $value;
    }

    /**
     * @return string
     */
    public function getUltimaAlteracao() {
        return $this->ultima_alteracao;
    }

    /**
     * @param string $value
     */
    public function setUltimaAlteracao($value) {
        $this->ultima_alteracao = $value;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $value
     */
    public function setSlug($value)
    {
        $this->slug = $value;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param string $value
     */
    public function setCodigo($value)
    {
        $this->codigo = $value;
    }

    /**
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @param string $value
     */
    public function setFoto($value)
    {
        $this->foto = $value;
    }

    /**
     * @return string
     */
    public function getFotoBase64()
    {
        return $this->foto_base64;
    }

    /**
     * @param string $value
     */
    public function setFotoBase64($value)
    {
        $this->foto_base64 = $value;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $value
     */
    public function setNome($value)
    {
        $this->nome = $value;
    }

    /**
     * @return double
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param double $value
     */
    public function setValor($value)
    {
        $this->valor = $value;
    }

    /**
     * @return double
     */
    public function getValorPromocao()
    {
        return $this->valor_promocao;
    }

    /**
     * @param double $value
     */
    public function setValorPromocao($value)
    {
        $this->valor_promocao = $value;
    }

    /**
     * @return double
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param double $value
     */
    public function setVolume($value)
    {
        $this->volume = $value;
    }

	/**
	 * @return bool
	 */
	public function getDestaque() {
		return $this->destaque;
	}

	/**
	 * @param bool $value
	 */
	public function setDestaque($value) {
		$this->destaque = $value;
	}

	/**
	 * @return string
	 */
	public function getDescricao() {
		return $this->descricao;
	}

	/**
	 * @param string $value
	 */
	public function setDescricao($value) {
		$this->descricao = $value;
	}

	/**
	 * @return int
	 */
	public function getQuantidade() {
		return $this->quantidade;
	}

	/**
	 * @param int $value
	 */
	public function setQuantidade($value) {
		$this->quantidade = $value;
	}

	/**
	 * @return int
	 */
	public function getQuantidadeVendido() {
		return $this->quantidade_vendido;
	}

	/**
	 * @param int $value
	 */
	public function setQuantidadeVendido($value) {
		$this->quantidade_vendido = $value;
	}

	/**
	 * @return int
	 */
	public function getCodSituacao() {
		return $this->cod_situacao;
	}

	/**
	 * @param int-option $value
	 */
	public function setCodSituacao($value) {
		$this->cod_situacao = $value;
	}

    /**
     * @throws Exception
     * @return LojaInfo
     */
    public function getLoja()
    {
        if (is_null($this->loja) && $this->getIdLoja() > 0) {
            $bll = new LojaBLL();
            $this->loja = $bll->pegar($this->getIdLoja());
        }
        return $this->loja;
    }

    /**
     * @return string
     */
	public function getValorStr() {
	    return number_format($this->valor, 2, ",", ".");
    }

    /**
     * @return string
     */
    public function getValorPromocaoStr() {
        return number_format($this->valor_promocao, 2, ",", ".");
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getVolumeStr() {
        $str = number_format($this->getVolume(), 1, ",", ".");
        if (substr($str, -2) == ",0") {
            $str = substr($str, 0, -2);
        }
        if ($this->getIdUnidade() > 0) {
            $unidade = $this->getUnidade();
            if (!is_null($unidade)) {
                $str .= $unidade->getNome();
            }
        }
        return $str;
    }

    /**
     * @return double
     */
    public function getValorFinal() {
        if ($this->getValorPromocao() > 0) {
            return $this->getValorPromocao();
        }
        else {
            return $this->getValor();
        }
    }

    /**
     * @return string
     */
    public function getValorFinalStr() {
        return number_format($this->getValorFinal(), 2, ",", ".");
    }

	/**
	 * @return string
	 */
	public function getSituacaoStr() {
		$bll = new ProdutoBLL();
		$lista = $bll->listarSituacao();
		return $lista[$this->getCodSituacao()];
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
	    if (!isNullOrEmpty($this->getFoto())) {
            return SITE_URL . sprintf("/produto/%sx%s/", $largura, $altura) . $this->getFoto();
        }
        return SITE_URL . sprintf("/img/%sx%s/sem-foto.jpg", $largura, $altura);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getUrl() {
        if (!defined("SITE_URL")) {
            throw new Exception("SITE_URL não foi definido.");
        }
        $loja = $this->getLoja();
        if (is_null($loja)){
            return "";
        }
        $categoria = $this->getCategoria();
        if (is_null($categoria)){
            return "";
        }
	    return SITE_URL . "/" . $loja->getSlug() . "/" . $categoria->getSlug() . "/" . $this->getSlug();
    }

    /**
     * @param ProdutoInfo $produto
     */
    public function clonarDe(ProdutoInfo $produto) {
        $this->setId($produto->getId());
        $this->setIdOrigem($produto->getIdOrigem());
        $this->setIdLoja($produto->getIdLoja());
        $this->setIdUsuario($produto->getIdUsuario());
        $this->setIdCategoria($produto->getIdCategoria());
        $this->setIdUnidade($produto->getIdUnidade());
        $this->setDataInclusao($produto->data_inclusao);
        $this->setUltimaAlteracao($produto->ultima_alteracao);
        $this->setSlug($produto->getSlug());
        $this->setCodigo($produto->getCodigo());
        $this->setFoto($produto->getFoto());
        $this->setFotoBase64($produto->getFotoBase64());
        $this->setNome($produto->getNome());
        $this->setValor($produto->getValor());
        $this->setValorPromocao($produto->getValorPromocao());
        $this->setVolume($produto->getVolume());
        $this->setDestaque($produto->getDestaque());
        $this->setDescricao($produto->getDescricao());
        $this->setQuantidade($produto->getQuantidade());
        $this->setQuantidadeVendido($produto->getQuantidadeVendido());
        $this->setCodSituacao($produto->getCodSituacao());
    }

	/**
     * @throws Exception
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_produto = $this->getId();
		$value->id_origem = $this->getIdOrigem();
		$value->id_loja = $this->getIdLoja();
		$value->id_usuario = $this->getIdUsuario();
		$value->id_categoria = $this->getIdCategoria();
		$value->id_unidade = $this->getIdUnidade();
		$value->data_inclusao = $this->getDataInclusao();
		$value->ultima_alteracao = $this->getUltimaAlteracao();
		$value->categoria = $this->getCategoria();
		$value->unidade = $this->getUnidade();
		$value->slug = $this->getSlug();
		$value->codigo = $this->getCodigo();
		$value->foto = $this->getFoto();
		$value->foto_url = $this->getFotoUrl(300, 300);
		$value->foto_base64 = $this->getFotoBase64();
		$value->nome = $this->getNome();
		$value->valor = floatval($this->getValor());
		$value->valor_promocao = floatval($this->getValorPromocao());
		$value->volume = floatval($this->getVolume());
		$value->volume_str = $this->getVolumeStr();
		$value->destaque = ($this->getDestaque() == 1);
		$value->descricao = $this->getDescricao();
		$value->quantidade = intval($this->getQuantidade());
		$value->quantidade_vendido = intval($this->getQuantidadeVendido());
		$value->cod_situacao = intval($this->getCodSituacao());
		$value->situacao = $this->getSituacaoStr();
		$value->url = $this->getUrl();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return ProdutoInfo
	 */
	public static function fromJson($value) {
		$produto = new ProdutoInfo();
		$produto->setId($value->id_produto);
		$produto->setIdOrigem($value->id_origem);
		$produto->setIdLoja($value->id_loja);
		$produto->setIdUsuario($value->id_usuario);
		$produto->setIdCategoria($value->id_categoria);
        $produto->setIdUnidade($value->id_unidade);
        $produto->setDataInclusao($value->data_inclusao);
        $produto->setUltimaAlteracao($value->ultima_alteracao);
		$produto->setSlug($value->slug);
		$produto->setCodigo($value->codigo);
		$produto->setFoto($value->foto);
        $produto->setFotoBase64($value->foto_base64);
		$produto->setNome($value->nome);
		$produto->setValor($value->valor);
		$produto->setValorPromocao($value->valor_promocao);
		$produto->setVolume($value->volume);
		$produto->setDestaque($value->destaque);
		$produto->setDescricao($value->descricao);
		$produto->setQuantidade($value->quantidade);
		$produto->setQuantidadeVendido($value->quantidade_vendido);
		$produto->setCodSituacao($value->cod_situacao);
		return $produto;
	}

}

