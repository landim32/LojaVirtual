<?php
namespace Emagine\Banner\Model;

use stdClass;
use Exception;
use JsonSerializable;
use Emagine\Base\EmagineApp;
use Emagine\Banner\BLL\BannerBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;

/**
 * Class BannerPecaInfo
 * @package Emagine\Banner\Model
 * @tablename banner_peca
 * @author EmagineCRUD
 */
class BannerPecaInfo implements JsonSerializable {

    const DESTINO_PRODUTO = 0;
    const DESTINO_LOJA = 1;
    const DESTINO_URL = 2;
    const GERENCIAR_PECA = "gerenciar-peca";

	private $id_peca;
	private $id_banner;
	private $id_loja;
    private $id_loja_destino;
	private $id_produto;
	private $cod_destino;
	private $nome;
	private $nome_arquivo;
	private $data_inclusao;
	private $ultima_alteracao;
	private $url;
	private $pageview;
	private $ordem = 0;
	private $banner = null;
	private $loja = null;
    private $loja_destino = null;
	private $produto = null;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_peca;
	}

	/**
	 * @param int $value
     * @return $this
	 */
	public function setId($value) {
		$this->id_peca = $value;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getIdBanner() {
		return $this->id_banner;
	}

	/**
	 * @param int $value
     * @return $this
	 */
	public function setIdBanner($value) {
		$this->id_banner = $value;
		return $this;
	}

    /**
     * @return int|null
     */
    public function getIdLoja() {
        return $this->id_loja;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function setIdLoja($value) {
        $this->id_loja = $value;
        return $this;
    }


    /**
     * @return int|null
     */
    public function getIdLojaDestino() {
        return $this->id_loja_destino;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function setIdLojaDestino($value) {
        $this->id_loja_destino = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdProduto() {
        return $this->id_produto;
    }

    /**
     * @param int|null $value
     */
    public function setIdProduto($value) {
        $this->id_produto = $value;
    }

    /**
     * @return int
     */
    public function getCodDestino() {
        return $this->cod_destino;
    }

    /**
     * @param int $value
     */
    public function setCodDestino($value) {
        $this->cod_destino = $value;
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
	public function getNomeArquivo() {
		return $this->nome_arquivo;
	}

	/**
	 * @param string $value
	 */
	public function setNomeArquivo($value) {
		$this->nome_arquivo = $value;
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
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $value
	 */
	public function setUrl($value) {
		$this->url = $value;
	}

    /**
     * @return int
     */
	public function getOrdem() {
	    return $this->ordem;
    }

    /**
     * @param int $value
     */
    public function setOrdem($value) {
	    $this->ordem = $value;
    }

    /**
     * @return string
     */
    public function getDataInclusaoStr() {
        return date("d/m/Y H:i", strtotime($this->data_inclusao));
    }

    /**
     * @return string
     */
    public function getUltimaAlteracaoStr() {
        return date("d/m/Y H:i", strtotime($this->ultima_alteracao));
    }

    /**
     * @return int
     */
    public function getPageview() {
        return $this->pageview;
    }

    /**
     * @param int $value
     */
    public function setPageview($value) {
        $this->pageview = $value;
    }

    /**
     * @throws Exception
     * @return BannerInfo
     */
    public function getBanner() {
        if (is_null($this->banner) && $this->getIdBanner() > 0) {
            $regraBanner = new BannerBLL();
            $this->banner = $regraBanner->pegar($this->getIdBanner());
        }
        return $this->banner;
    }

    /**
     * @throws Exception
     * @return LojaInfo
     */
    public function getLoja() {
        if (is_null($this->loja) && $this->getIdLoja() > 0) {
            $regraLoja = new LojaBLL();
            $this->loja = $regraLoja->pegar($this->getIdLoja());
        }
        return $this->loja;
    }

    /**
     * @throws Exception
     * @return LojaInfo
     */
    public function getLojaDestino() {
        if (is_null($this->loja_destino) && $this->getIdLojaDestino() > 0) {
            $regraLoja = new LojaBLL();
            $this->loja_destino = $regraLoja->pegar($this->getIdLojaDestino());
        }
        return $this->loja_destino;
    }
    /**
     * @throws Exception
     * @return ProdutoInfo
     */
    public function getProduto() {
        if (is_null($this->produto) && $this->getIdProduto() > 0) {
            $regraProduto = new ProdutoBLL();
            $this->produto = $regraProduto->pegar($this->getIdProduto());
        }
        return $this->produto;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getProdutoNome() {
        $produto = $this->getProduto();
        if (!is_null($produto)) {
            return $produto->getNome();
        }
        return "";
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getProdutoAdminUrl() {
        $url = "#";
        if ($this->getIdProduto() > 0) {
            $produto = $this->getProduto();
            $app = EmagineApp::getApp();
            $url = $app->getBaseUrl() . "/produto/" . $produto->getSlug();
        }
        return $url;
    }

    /**
     * @param int $largura
     * @param int $altura
     * @return string
     * @throws Exception
     */
    public function getImagemUrl($largura = 0, $altura = 0) {
        if (!defined("SITE_URL")) {
            throw new Exception("SITE_URL não foi definido.");
        }
        $banner = $this->getBanner();
        if (is_null($banner)){
            //return SITE_URL . "/img/" . $largura . "x" . $altura . "/";
            //throw new Exception("Area do banner não definida.");
            return "";
        }
        if (!($largura > 0)) {
            $largura = $banner->getLargura();
        }
        if (!($altura > 0)) {
            $altura = $banner->getAltura();
        }
        return SITE_URL . "/ad/" . $largura . "x" . $altura . "/" . $this->getNomeArquivo();
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getUrlDestino() {
        if (!defined("SITE_URL")) {
            throw new Exception("SITE_URL não foi definido.");
        }
        $url = "#";
        switch ($this->getCodDestino()) {
            case BannerPecaInfo::DESTINO_LOJA:
                if ($this->getIdLojaDestino() > 0) {
                    //$regraLoja = new LojaBLL();
                    $loja = $this->getLojaDestino();
                    if (!is_null($loja)) {
                        $url = SITE_URL . "/" . $loja->getSlug();
                    }
                }
                break;
            case BannerPecaInfo::DESTINO_PRODUTO:
                if (!($this->getIdProduto() > 0)) {
                    //$regraProduto = new ProdutoBLL();
                    //$produto = $regraProduto->pegar($this->getIdProduto());
                    $produto = $this->getProduto();
                    if (!is_null($produto)) {
                        $url = $produto->getUrl();
                    }
                }
                break;
            default:
                $url = $this->getUrl();
                break;
        }
        return $url;
    }

	/**
     * @throws Exception
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_peca = intval($this->getId());
		$value->id_banner = intval($this->getIdBanner());
		$value->id_loja = intval($this->getIdLoja());
        $value->id_loja_destino = intval($this->getIdLojaDestino());
		$value->id_produto = intval($this->getIdProduto());
		$value->cod_destino = $this->getCodDestino();
		$value->banner = $this->getBanner()->jsonSerialize();
		$value->nome = $this->getNome();
		$value->nome_arquivo = $this->getNomeArquivo();
		$value->data_inclusao = $this->getDataInclusao();
        $value->data_inclusao_str = $this->getDataInclusaoStr();
        $value->ultima_alteracao = $this->getUltimaAlteracao();
		$value->ultima_alteracao_str = $this->getUltimaAlteracaoStr();
		$value->url = $this->getUrl();
		$value->ordem = intval($this->getOrdem());
		$value->imagem_url = $this->getImagemUrl();
		$value->pageview = intval($this->getPageview());
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return BannerPecaInfo
	 */
	public static function fromJson($value) {
		$peca = new BannerPecaInfo();
		$peca->setId($value->id_peca);
		$peca->setIdBanner($value->id_banner);
        $peca->setIdLoja($value->id_loja);
        $peca->setIdLojaDestino($value->id_loja_destino);
        $peca->setIdProduto($value->id_produto);
        $peca->setCodDestino($value->cod_destino);
		$peca->setNome($value->nome);
		$peca->setNomeArquivo($value->nome_arquivo);
		$peca->setDataInclusao($value->data_inclusao);
		$peca->setUltimaAlteracao($value->ultima_alteracao);
		$peca->setUrl($value->url);
		$peca->setOrdem($value->ordem);
		$peca->setPageview($value->pageview);
		return $peca;
	}

}

