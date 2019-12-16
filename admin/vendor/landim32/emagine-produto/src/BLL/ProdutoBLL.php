<?php
namespace Emagine\Produto\BLL;

use Emagine\Log\Factory\LogFactory;
use Emagine\Log\Model\LogInfo;
use Emagine\Produto\DAL\UsuarioLojaDAL;
use Emagine\Produto\Model\ProdutoRetornoInfo;
use Emagine\Social\Factory\MensagemFactory;
use Emagine\Social\Model\MensagemInfo;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Produto\DAL\ProdutoDAL;
use Emagine\Produto\Model\ProdutoInfo;
use Emagine\Produto\Model\ProdutoFiltroInfo;
use Emagine\Produto\Model\CategoriaInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * Class ProdutoBLL
 * @package EmagineProduto\BLL
 * @tablename produto
 * @author EmagineCRUD
 */
class ProdutoBLL {

    /**
     * @return bool
     */
    public static function usaProdutoRoute() {
        if (defined("USA_PRODUTO_ROUTE")) {
            return (USA_PRODUTO_ROUTE == true);
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function usaProdutoAPI() {
        if (defined("USA_PRODUTO_API")) {
            return (USA_PRODUTO_API == true);
        }
        return true;
    }

	/**
	 * @return array<string,string>
	 */
	public function listarSituacao() {
		return array(
			ProdutoInfo::ATIVO => 'Ativo',
			ProdutoInfo::INATIVO => 'Inativo'
		);
	}

    /**
     * @deprecated Use o buscar
     * @param ProdutoFiltroInfo $filtro
     * @return ProdutoInfo[]
     * @throws Exception
     */
    public function listarPorFiltro($filtro) {
        $dal = new ProdutoDAL();
        $retorno = $dal->buscar($filtro);
        return $retorno->getProdutos();
    }

    /**
     * @param ProdutoFiltroInfo $filtro
     * @return ProdutoRetornoInfo
     * @throws Exception
     */
    public function buscar($filtro) {
        $dal = new ProdutoDAL();
        return $dal->buscar($filtro);
    }

	/**
     * @deprecated Use o buscar
     * @throws Exception
     * @param int $id_loja
     * @param int $id_categoria
	 * @return ProdutoInfo[]
	 */
	public function listar($id_loja, $id_categoria = null) {
		$dal = new ProdutoDAL();
		$filtro = new ProdutoFiltroInfo();
		$filtro->setIdLoja($id_loja);
		$filtro->setIdCategoria($id_categoria);
		$retorno = $dal->buscar($filtro);
		return $retorno->getProdutos();
	}

    /**
     * @deprecated Use o buscar
     * @throws Exception
     * @param int $id_loja
     * @return ProdutoInfo[]
     */
    public function listarDestaque($id_loja) {
        $dal = new ProdutoDAL();
        $filtro = new ProdutoFiltroInfo();
        $filtro->setIdLoja($id_loja);
        $filtro->setDestaque(true);
        $retorno = $dal->buscar($filtro);
        return $retorno->getProdutos();
    }

    /**
     * @deprecated Use o buscar
     * @throws Exception
     * @param int $id_loja
     * @param string $palavraChave
     * @return ProdutoInfo[]
     */
    public function buscarPorPalavra($id_loja, $palavraChave) {
        $dal = new ProdutoDAL();
        $filtro = new ProdutoFiltroInfo();
        $filtro->setIdLoja($id_loja);
        $filtro->setPalavraChave($palavraChave);
        $retorno = $dal->buscar($filtro);
        return $retorno->getProdutos();
    }

    /**
     * @deprecated Use o buscar
     * @throws Exception
     * @param string $palavraChave
     * @return ProdutoInfo[]
     */
    public function buscarOriginal($palavraChave) {
        $dal = new ProdutoDAL();
        $filtro = new ProdutoFiltroInfo();
        $filtro->setPalavraChave($palavraChave);
        $filtro->setExibeOrigem(true);
        $retorno = $dal->buscar($filtro);
        return $retorno->getProdutos();
    }

	/**
     * @deprecated Use o buscar
     * @throws Exception
     * @param int $id_loja
	 * @param int $id_usuario
	 * @return ProdutoInfo[]
	 */
	public function listarPorUsuario($id_loja, $id_usuario) {
		$dal = new ProdutoDAL();
        $filtro = new ProdutoFiltroInfo();
        $filtro->setIdLoja($id_loja);
        $filtro->setIdUsuario($id_usuario);
        $retorno = $dal->buscar($filtro);
        return $retorno->getProdutos();
	}

	/**
     * @deprecated Use o buscar
     * @throws Exception
	 * @param int $id_categoria
     * @param int|null $cod_situacao
	 * @return ProdutoInfo[]
	 */
	public function listarPorCategoria($id_categoria, $cod_situacao = null) {
		$dal = new ProdutoDAL();
        $filtro = new ProdutoFiltroInfo();
        $filtro->setIdLoja($id_categoria);
        if (!is_null($filtro->getCodSituacao())) {
            $filtro->setCodSituacao($cod_situacao);
        }
        $retorno = $dal->buscar($filtro);
        return $retorno->getProdutos();
	}


	/**
     * @throws Exception
	 * @param int $id_produto
	 * @return ProdutoInfo
	 */
	public function pegar($id_produto) {
		$dal = new ProdutoDAL();
		return $dal->pegar($id_produto);
	}

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $slug
     * @return ProdutoInfo
     */
	public function pegarPorSlug($id_loja, $slug) {
	    $dal = new ProdutoDAL();
	    return $dal->pegarPorSlug($id_loja, $slug);
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $codigo
     * @return ProdutoInfo
     */
    public function pegarPorCodigo($id_loja, $codigo) {
        $dal = new ProdutoDAL();
        return $dal->pegarPorCodigo($id_loja, $codigo);
    }

    /**
     * @param array<string,string> $postData
     * @param ProdutoInfo $produto
     */
	public function pegarDoPost($postData, &$produto) {
	    if (is_null($produto)) {
	        $produto = new ProdutoInfo();
        }
        if (array_key_exists("id_produto", $postData)) {
            $produto->setId($postData["id_produto"]);
        }
        if (array_key_exists("id_categoria", $postData)) {
            $produto->setIdCategoria($postData["id_categoria"]);
        }
        if (array_key_exists("codigo", $postData)) {
            $produto->setCodigo($postData["codigo"]);
        }
        if (array_key_exists("nome", $postData)) {
            $produto->setNome($postData["nome"]);
        }
        if (array_key_exists("valor", $postData)) {
            $produto->setValor(floatvalx($postData["valor"]));
        }
        if (array_key_exists("valor_promocao", $postData)) {
            $produto->setValorPromocao(floatvalx($postData["valor_promocao"]));
        }
        if (array_key_exists("volume", $postData)) {
            $produto->setVolume(floatvalx($postData["volume"]));
        }
        if (array_key_exists("id_unidade", $postData)) {
            $produto->setIdUnidade(intval($postData["id_unidade"]));
        }
        if (array_key_exists("destaque", $postData)) {
            $produto->setDestaque($postData["destaque"] == "1");
        }
        if (array_key_exists("descricao", $postData)) {
            $produto->setDescricao($postData["descricao"]);
        }
        if (array_key_exists("quantidade", $postData)) {
            $produto->setQuantidade($postData["quantidade"]);
        }
        if (array_key_exists("cod_situacao", $postData)) {
            $produto->setCodSituacao($postData["cod_situacao"]);
        }
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param int $id_produto
     * @param string $slug
     * @return string
     */
    private function slugValido($id_loja, $id_produto, $slug)
    {
        $slug = strtolower($slug);
        $produto = $this->pegarPorSlug($id_loja, $slug);
        if (!is_null($produto) && $produto->getId() != $id_produto) {
            $out = array();
            preg_match_all("#(.*?)-(\d{1,2})#i", $slug, $out, PREG_PATTERN_ORDER);
            if (!isNullOrEmpty($out[1][0]) && is_numeric($out[2][0])) {
                $slug = $this->slugValido($id_loja, $id_produto, $out[1][0] . "-" . (intval($out[2][0]) + 1));
            }
            else {
                $slug = $this->slugValido($id_loja, $id_produto, $slug . '-2');
            }
        }
        return $slug;
    }

	/**
	 * @throws Exception
	 * @param ProdutoInfo $produto
	 */
	protected function validar(&$produto) {
		if (!($produto->getIdLoja() >= 0)) {
			throw new Exception('Informa a loja.');
		}
		if (!($produto->getIdUsuario() >= 0)) {
			throw new Exception('Informe o usuário.');
		}
		if (!($produto->getIdCategoria() >= 0)) {
			throw new Exception('Selecione uma categoria.');
		}
		if (isNullOrEmpty($produto->getNome())) {
			throw new Exception('Preencha o campo Nome.');
		}
        if (strlen($produto->getNome()) > 60) {
            $produto->setNome(cortarTexto($produto->getNome(), 60));
        }
        if (strlen($produto->getCodigo()) > 20) {
            $produto->setCodigo(cortarTexto($produto->getCodigo(), 20));
        }
        if (strlen($produto->getDescricao()) > 2000) {
            $produto->setDescricao(cortarTexto($produto->getDescricao(), 2000));
        }
        $slug = sanitize_slug(strtolower(trim($produto->getNome())));
        $produto->setSlug($this->slugValido($produto->getIdLoja(), $produto->getId(), $slug));
        if (is_null($produto->getQuantidade())) {
            $produto->setQuantidade(0);
        }
        if (!($produto->getCodSituacao() > 0)) {
            $produto->setCodSituacao(ProdutoInfo::ATIVO);
        }
	}

    /**
     * @param ProdutoInfo $produto
     * @throws Exception
     */
	private function gravarFoto(ProdutoInfo &$produto) {

        $pathInfo = pathinfo($produto->getFoto());
        $extensao = $pathInfo['extension'];

        $token = md5(uniqid(rand(), true));
        $produtoDir = UPLOAD_PATH . '/produto';
        if (!file_exists($produtoDir)) {
            if (!is_writable($produtoDir)) {
                throw new Exception(sprintf("Não tem permissão para criar o diretório %s.", $produtoDir));
            }
            @mkdir($produtoDir);
        }
        $arquivoFoto = UPLOAD_PATH . '/produto/' . $token . '.' . $extensao;
        $data = preg_replace('#^data:image/\w+;base64,#i', '', $produto->getFotoBase64());
        file_put_contents($arquivoFoto, base64_decode($data));

        $produto->setFoto($token . "." . $extensao);
    }

	/**
	 * @throws Exception
	 * @param ProdutoInfo $produto
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($produto, $transaction = true) {
		$this->validar($produto);
		if (!isNullOrEmpty($produto->getFoto()) && !isNullOrEmpty($produto->getFotoBase64())) {
		    $this->gravarFoto($produto);
        }
		$dal = new ProdutoDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$id_produto = $dal->inserir($produto);

            $log = (new LogInfo())
                ->setIdLoja($produto->getIdLoja())
                ->setIdUsuario($produto->getIdUsuario())
                ->setCodTipo(LogInfo::INFORMACAO)
                ->setTitulo(sprintf("%s - Incluído", $produto->getNome()))
                ->setDescricao(print_r($produto->jsonSerialize(), true));
            $regraLog = LogFactory::create();
            $regraLog->inserir($log, false);

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
		return $id_produto;
	}

	/**
	 * @throws Exception
	 * @param ProdutoInfo $produto
	 * @param bool $transaction
	 */
	public function alterar($produto, $transaction = true) {
		$this->validar($produto);
        if (!isNullOrEmpty($produto->getFoto()) && !isNullOrEmpty($produto->getFotoBase64())) {
            $this->gravarFoto($produto);
        }
		$dal = new ProdutoDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}

			$produtoOld = $this->pegar($produto->getId());
			$dal->alterar($produto);

			if ($produtoOld->getQuantidade() != $produto->getQuantidade()) {
			    $loja = $produto->getLoja();
			    if ($loja->getControleEstoque() == true) {
			        if ($produto->getQuantidade() < $loja->getEstoqueMinimo()) {
			            $regraMensagem = MensagemFactory::create();
			            $dalLoja = new UsuarioLojaDAL();
			            $usuarios = $dalLoja->listar($loja->getId());
			            foreach ($usuarios as $usuarioLoja) {
			                $textoFormato = "O produto '%s' está com estoque abaixo do mínimo (%s).";
			                $texto = sprintf($textoFormato, $produto->getNome(), $produto->getQuantidade());
			                $url = SITE_URL . "/" . $loja->getSlug() . "/produto/" . $produto->getSlug();
                            $mensagem = (new MensagemInfo())
                                ->setIdUsuario($usuarioLoja->getIdUsuario())
                                ->setMensagem($texto)
                                ->setUrl($url)
                                ->setLido(false);
                            $regraMensagem->inserir($mensagem);
                        }
                    }
                }
            }

            $log = (new LogInfo())
                ->setIdLoja($produto->getIdLoja())
                ->setIdUsuario($produto->getIdUsuario())
                ->setCodTipo(LogInfo::INFORMACAO)
                ->setTitulo(sprintf("%s - Alterado", $produto->getNome()))
                ->setDescricao(print_r($produto->jsonSerialize(), true));
            $regraLog = LogFactory::create();
            $regraLog->inserir($log, false);

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
	 * @param int $id_produto
	 * @param bool $transaction
	 */
	public function excluir($id_produto, $transaction = true) {
		$dal = new ProdutoDAL();
        $quantidade = $dal->pegarQuantidadePedido($id_produto);
        if ($quantidade > 0) {
            $mensagem = "Esse produto está ligado a %s pedido(s). Exclua o(s) pedido(s) antes.";
            throw new Exception(sprintf($mensagem, $quantidade));
        }
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($id_produto);
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
	 * @param int $id_usuario
	 */
	public function limparPorIdUsuario($id_usuario) {
		$dal = new ProdutoDAL();
		$dal->limparPorIdUsuario($id_usuario);
	}

	/**
     * @throws Exception
	 * @param int $id_categoria
	 */
	public function limparPorIdCategoria($id_categoria) {
		$dal = new ProdutoDAL();
		$dal->limparPorIdCategoria($id_categoria);
	}

    /**
     * @param int $id_produto
     * @return int
     * @throws Exception
     */
    public function pegarQuantidade($id_produto) {
        $dal = new ProdutoDAL();
        return $dal->pegarQuantidade($id_produto);
    }

    /**
     * @throws Exception
     * @param int $id_categoria
     * @return int
     */
    public function pegarQuantidadePorCategoria($id_categoria) {
        $dal = new ProdutoDAL();
        return $dal->pegarQuantidadePorCategoria($id_categoria);
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @return int
     */
    public function pegarQuantidadePorLoja($id_loja) {
        $dal = new ProdutoDAL();
        return $dal->pegarQuantidadePorLoja($id_loja);
    }

    /**
     * @param int $id_produto
     * @param int $quantidade
     * @throws Exception
     */
    public function alterarQuantidade($id_produto, $quantidade) {
        $dal = new ProdutoDAL();
        $dal->alterarQuantidade($id_produto, $quantidade);
    }

    /**
     * @throws Exception
     * @param CategoriaInfo $categoria
     * @param LojaInfo $origem
     * @param LojaInfo $destino
     * @return CategoriaInfo
     */
    private function copiarCategoria(CategoriaInfo $categoria, LojaInfo $origem, LojaInfo $destino) {
        $regraCategoria = new CategoriaBLL();
        $novaCategoria = $regraCategoria->pegarPorSlug($destino->getId(), $categoria->getSlug());
        if (!is_null($novaCategoria)) {
            return $novaCategoria;
        }
        $novaCategoria = new CategoriaInfo();
        $novaCategoria->clonarDe($categoria);
        $novaCategoria->setId(0);
        if ($categoria->getIdPai() > 0) {
            $origemCategoria = $regraCategoria->pegar($categoria->getIdPai());
            $destinoCategoria = $this->copiarCategoria($origemCategoria, $origem, $destino);
            $novaCategoria->setIdPai($destinoCategoria->getId());
        }
        $novaCategoria->setIdLoja($destino->getId());
        $id_categoria = $regraCategoria->inserir($novaCategoria);
        return $regraCategoria->pegar($id_categoria);
    }

    /**
     * @param LojaInfo $origem
     * @param LojaInfo $destino
     * @throws Exception
     */
    public function copiar(LojaInfo $origem, LojaInfo $destino) {
        $regraCategoria = new CategoriaBLL();
        $produtos = $this->listar($origem->getId());
        foreach ($produtos as $produtoAntigo) {
            $novoProduto = new ProdutoInfo();
            $novoProduto->clonarDe($produtoAntigo);
            $produto = $this->pegarPorSlug($destino->getId(), $produtoAntigo->getSlug());
            if (!is_null($produto)) {
                $novoProduto->setId($produto->getId());
                $novoProduto->setIdLoja($produto->getIdLoja());
                $novoProduto->setValor($produto->getValor());
                $novoProduto->setValorPromocao($produto->getValorPromocao());
                $novoProduto->setQuantidade($produto->getQuantidade());
                $novoProduto->setQuantidadeVendido($produto->getQuantidadeVendido());
                $novoProduto->setCodSituacao($produto->getCodSituacao());
            }
            else {
                $novoProduto->setId(0);
                $novoProduto->setIdLoja($destino->getId());
            }
            if ($produtoAntigo->getIdCategoria() > 0) {
                $categoria = $regraCategoria->pegar($produtoAntigo->getIdCategoria());
                $novaCategoria = $this->copiarCategoria($categoria, $origem, $destino);
                $novoProduto->setIdCategoria($novaCategoria->getId());
            }
            if ($novoProduto->getId() > 0) {
                $this->alterar($novoProduto);
            }
            else {
                $this->inserir($novoProduto);
            }
        }
    }
}

