<?php
namespace Emagine\Banner\BLL;

use Emagine\Banner\DAL\BannerDAL;
use Emagine\Banner\Model\BannerFiltroInfo;
use Emagine\Banner\Model\BannerInfo;
use Emagine\Log\Factory\LogFactory;
use Emagine\Log\Model\LogInfo;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Produto\Model\LojaInfo;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Banner\DAL\BannerPecaDAL;
use Emagine\Banner\Model\BannerPecaInfo;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\UploadedFile;

/**
 * Class BannerPecaBLL
 * @package Emagine\Banner\BLL
 * @tablename banner_peca
 * @author EmagineCRUD
 */
class BannerPecaBLL {

	/**
     * @throws Exception
     * @param int $id_banner
     * @param int $id_loja
	 * @return BannerPecaInfo[]
	 */
	public function listar($id_banner = 0, $id_loja = 0) {
		$dal = new BannerPecaDAL();
		$filtro = new BannerFiltroInfo();
		if ($id_banner > 0) {
            $filtro->setIdBanner($id_banner);
        }
        if ($id_loja > 0) {
            $filtro->setIdLoja($id_loja);
        }
		return $dal->listar($filtro);
	}

    /**
     * @param BannerFiltroInfo $filtro
     * @return BannerPecaInfo[]
     * @throws Exception
     */
	private function organizar(BannerFiltroInfo $filtro) {
        $dalBanner = new BannerDAL();
        $dalPeca = new BannerPecaDAL();
        $pecas = $dalPeca->listar($filtro);
        if (count($pecas) > 0) {
            $pageview = $dalBanner->pegarPageview($filtro->getIdBanner());
            $offset = $pageview % count($pecas);
            $novasPecas = array_merge(array_slice($pecas, $offset), array_slice($pecas, 0, $offset));
            if ($filtro->getQuantidade() > 0) {
                $novasPecas = array_slice($pecas, 0, $filtro->getQuantidade());
            }
            foreach ($novasPecas as $peca) {
                $dalPeca->contarPageview($peca->getId());
            }
            $dalBanner->contarPageview($filtro->getIdBanner());
            return $novasPecas;
        }
        return array();
    }

    /**
     * @throws Exception
     * @param BannerFiltroInfo $filtro
     * @return BannerPecaInfo[]
     */
    public function gerar(BannerFiltroInfo $filtro) {
        try{
            DB::beginTransaction();
            if (!($filtro->getIdBanner() > 0) && !isNullOrEmpty($filtro->getSlugBanner())) {
                $regraBanner = new BannerBLL();
                $banner = $regraBanner->pegarPorSlug($filtro->getSlugBanner());
                $filtro->setIdBanner($banner->getId());
                //$filtro->setSlugBanner($banner->getSlug());
            }
            $pecas = $this->organizar($filtro);
            DB::commit();
        }
        catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
        return $pecas;
    }

	/**
     * @throws Exception
	 * @param int $idPeca
	 * @return BannerPecaInfo
	 */
	public function pegar($idPeca) {
		$dal = new BannerPecaDAL();
		return $dal->pegar($idPeca);
	}

	/**
	 * @param string[] $postData
	 * @param BannerPecaInfo|null $peca
	 * @return BannerPecaInfo
	 */
	public function pegarDoPost($postData, $peca = null) {
		if (is_null($peca)) {
			$peca = new BannerPecaInfo();
		}
		if (array_key_exists("id_peca", $postData)) {
			$peca->setId($postData["id_peca"]);
		}
		if (array_key_exists("id_banner", $postData)) {
			$peca->setIdBanner($postData["id_banner"]);
		}
        if (array_key_exists("id_loja", $postData)) {
            $peca->setIdLoja($postData["id_loja"]);
        }
        if (array_key_exists("id_loja_destino", $postData)) {
            $peca->setIdLojaDestino($postData["id_loja_destino"]);
        }
        if (array_key_exists("id_produto", $postData)) {
            $peca->setIdProduto($postData["id_produto"]);
        }
        if (array_key_exists("cod_destino", $postData)) {
            $peca->setCodDestino(intval($postData["cod_destino"]));
        }
		if (array_key_exists("nome", $postData)) {
			$peca->setNome($postData["nome"]);
		}
		if (array_key_exists("url", $postData)) {
			$peca->setUrl($postData["url"]);
		}
		return $peca;
	}

	/**
	 * @throws Exception
	 * @param BannerPecaInfo $peca
	 */
	protected function validar(&$peca) {
	    if (!($peca->getIdBanner() > 0)) {
            throw new Exception('O espaço do banner não foi selecionado.');
        }
        if (isNullOrEmpty($peca->getNomeArquivo())) {
            throw new Exception('A imagem não foi enviada.');
        }
		if (isNullOrEmpty($peca->getNome())) {
			throw new Exception('Preencha o nome.');
		}
		$banner = $peca->getBanner();
		if ($banner->getCodTipo() != BannerInfo::ADMIN) {
            if (!($peca->getIdLoja() > 0)) {
                throw new Exception('A loja tem que estar vinculada quando o banner não for administrativo.');
            }
        }
		switch ($peca->getCodDestino()) {
            case BannerPecaInfo::DESTINO_LOJA:
                if (!($peca->getIdLojaDestino() > 0)) {
                    throw new Exception('A loja de destino não foi selecionada.');
                }
                $peca->setIdProduto(null);
                $peca->setUrl(null);
                break;
            case BannerPecaInfo::DESTINO_PRODUTO:
                if (!($peca->getIdProduto() > 0)) {
                    throw new Exception('O produto não foi selecionado.');
                }
                $peca->setIdLojaDestino(null);
                $peca->setUrl(null);
                break;
            case BannerPecaInfo::DESTINO_URL:
                if (isNullOrEmpty($peca->getUrl())) {
                    throw new Exception('A URL não foi informada.');
                }
                $peca->setIdLojaDestino(null);
                $peca->setIdProduto(null);
                break;
        }
	}

    /**
     * @param BannerPecaInfo $peca
     * @param Request $request
     * @throws Exception
     */
	public function atualizarFoto(BannerPecaInfo &$peca, Request $request) {
        $directory = UPLOAD_PATH . '/banner';
        if (!file_exists($directory)) {
            @mkdir($directory, 755);
        }
        $uploadedFiles = $request->getUploadedFiles();

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $uploadedFiles['foto'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $basename = bin2hex(random_bytes(8));
            $filename = sprintf('%s.%0.8s', $basename, $extension);

            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
            $peca->setNomeArquivo($filename);
        }
    }

	/**
	 * @throws Exception
	 * @param BannerPecaInfo $peca
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($peca, $transaction = true) {
		$id_peca = 0;
		$this->validar($peca);
		$dal = new BannerPecaDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$idPeca = $dal->inserir($peca);

            $log = (new LogInfo())
                ->setIdLoja($peca->getIdLoja())
                ->setIdUsuario(UsuarioBLL::pegarIdUsuarioAtual())
                ->setCodTipo(LogInfo::INFORMACAO)
                ->setTitulo(sprintf("Banner '%s' - Incluído", $peca->getNome()))
                ->setDescricao(print_r($peca->jsonSerialize(), true));
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
		return $idPeca;
	}

	/**
	 * @throws Exception
	 * @param BannerPecaInfo $peca
	 * @param bool $transaction
	 */
	public function alterar($peca, $transaction = true) {
		$this->validar($peca);
		$dal = new BannerPecaDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($peca);

            $log = (new LogInfo())
                ->setIdLoja($peca->getIdLoja())
                ->setIdUsuario(UsuarioBLL::pegarIdUsuarioAtual())
                ->setCodTipo(LogInfo::INFORMACAO)
                ->setTitulo(sprintf("Banner '%s' - Alterado", $peca->getNome()))
                ->setDescricao(print_r($peca->jsonSerialize(), true));
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
	 * @param int $id_peca
	 * @param bool $transaction
	 */
	public function excluir($id_peca, $transaction = true) {
	    $peca = $this->pegar($id_peca);
		$dal = new BannerPecaDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($id_peca);

            $log = (new LogInfo())
                ->setIdLoja($peca->getIdLoja())
                ->setIdUsuario(UsuarioBLL::pegarIdUsuarioAtual())
                ->setCodTipo(LogInfo::INFORMACAO)
                ->setTitulo(sprintf("Banner '%s' - Excluído", $peca->getNome()))
                ->setDescricao(print_r($peca->jsonSerialize(), true));
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
     * @param BannerPecaInfo $peca
     * @param int $ordem
     * @throws Exception
     */
	protected function alterarOrdem($peca, $ordem) {
        $dal = new BannerPecaDAL();
        $outraPeca = $dal->pegarPorOrdem($peca->getIdLoja(), $peca->getIdBanner(), $ordem);
        if (!is_null($outraPeca)) {
            $dal->alterarOrdem($outraPeca->getId(), $peca->getOrdem());
        }
        $dal->alterarOrdem($peca->getId(), $ordem);
    }

    /**
     * @param int $id_banner
     * @param int $id_loja
     * @throws Exception
     */
    protected function reordenar($id_banner, $id_loja) {
        $ordem = 0;
        $dal = new BannerPecaDAL();
	    $pecas = $this->listar($id_banner, $id_loja);
	    foreach ($pecas as $peca) {
            $dal->alterarOrdem($peca->getId(), $ordem);
            $ordem++;
        }
    }

    /**
     * @param int $id_peca
     * @throws Exception
     */
    public function moverAcima($id_peca) {
        try{
            DB::beginTransaction();
            $peca = $this->pegar($id_peca);
            $this->alterarOrdem($peca, $peca->getOrdem() + 1);
            $this->reordenar($peca->getIdBanner(), $peca->getIdLoja());
            DB::commit();
        }
        catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $id_peca
     * @throws Exception
     */
    public function moverAbaixo($id_peca) {
        try{
            DB::beginTransaction();
            $peca = $this->pegar($id_peca);
            $this->alterarOrdem($peca, $peca->getOrdem() - 1);
            $this->reordenar($peca->getIdBanner(), $peca->getIdLoja());
            DB::commit();
        }
        catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

	/**
     * @throws Exception
	 * @param int $idBanner
	 */
	public function limparPorIdBanner($idBanner) {
		$dal = new BannerPecaDAL();
		$dal->limparPorIdBanner($idBanner);
	}

}

