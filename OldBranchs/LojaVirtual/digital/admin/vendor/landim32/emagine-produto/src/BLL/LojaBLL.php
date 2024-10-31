<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 09/11/2017
 * Time: 22:49
 * Tablename: loja
 */

namespace Emagine\Produto\BLL;

use Emagine\Base\BLL\CookieBLL;
use Emagine\Log\Factory\LogFactory;
use Emagine\Log\Model\LogInfo;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Base\BLL\ValidaCpfCnpj;
use Emagine\Produto\DAL\LojaDAL;
use Emagine\Produto\DAL\UsuarioLojaDAL;
use Emagine\Produto\DAL\LojaOpcaoDAL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Ex\LojaException;

/**
 * Class LojaBLL
 * @package EmagineProduto\BLL
 * @tablename loja
 * @author EmagineCRUD
 */
class LojaBLL {

    const LOJA_COOKIE = "LOJA_COOKIE";
    const RAIO_COOKIE = "RAIO_COOKIE";

    /**
     * @return bool
     */
    public static function usaLojaRoute() {
        if (defined("USA_LOJA_ROUTE")) {
            return (USA_LOJA_ROUTE == true);
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function usaLojaUnica() {
        if (defined("LOJA_UNICA")) {
            return (LOJA_UNICA == true);
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function usaLojaCookie() {
        if (defined("LOJA_USA_COOKIE")) {
            return (LOJA_USA_COOKIE == true);
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function usaPagamentoOnline() {
        if (defined("LOJA_PAGAMENTO_ONLINE")) {
            return (LOJA_PAGAMENTO_ONLINE == true);
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function usaDebitoOnline() {
        if (defined("LOJA_DEBITO_ONLINE")) {
            return (LOJA_DEBITO_ONLINE == true);
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function usaRetiradaMapeada() {
        if (defined("LOJA_RETIRADA_MAPEADA")) {
            return (LOJA_RETIRADA_MAPEADA == true);
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function usaBuscaLojaPorCep() {
        if (defined("LOJA_BUSCA_POR_CEP")) {
            return (LOJA_BUSCA_POR_CEP == true);
        }
        return false;
    }

	/**
	 * @return array<string,string>
	 */
	public function listarSituacao() {
		return array(
			LojaInfo::ATIVO => 'Ativo',
			LojaInfo::INATIVO => 'Inativo'
		);
	}

    /**
     * @return array<string,string>
     */
    public function listarGateway() {
        return array(
            LojaInfo::GATEWAY_CIELO => 'Cielo',
            LojaInfo::GATEWAY_IPAG => 'IPag',
            LojaInfo::GATEWAY_YAPAY => 'YaPay',
        );
    }

	/**
     * @throws
     * @param int|null $id_seguimento
	 * @return LojaInfo[]
	 */
	public function listar($id_seguimento = null) {
		$dal = new LojaDAL();
		return $dal->listar($id_seguimento);
	}

    /**
     * @throws
     * @param int $id_usuario
     * @return LojaInfo[]
     */
    public function listarPorUsuario($id_usuario) {
        $regraUsuario = new UsuarioBLL();
        $usuario = $regraUsuario->pegar($id_usuario);
        if (!is_null($usuario) && $usuario->temPermissao(UsuarioInfo::ADMIN)) {
            return $this->listar();
        }
        else {
            $dal = new LojaDAL();
            return $dal->listarPorUsuario($id_usuario);
        }
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param int $raio
     * @param int $id_seguimento
     * @return LojaInfo[]
     * @throws Exception
     */
    public function buscarPorPosicao($latitude, $longitude, $raio = 100, $id_seguimento = 0) {
        $dal = new LojaDAL();
        return $dal->buscarPorPosicao($latitude, $longitude, $raio, $id_seguimento);
    }

    /**
     * @throws Exception
     * @param string $palavraChave
     * @return LojaInfo[]
     */
    public function buscarPorPalavra($palavraChave) {
        $dal = new LojaDAL();
        return $dal->buscarPorPalavra($palavraChave);
    }

	/**
     * @throws Exception
	 * @param int $id_loja
	 * @return LojaInfo
	 */
	public function pegar($id_loja) {
		$dal = new LojaDAL();
		return $dal->pegar($id_loja);
	}

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param int $id_loja
     * @return LojaInfo
     */
    public function pegarPorUsuario($id_usuario, $id_loja) {
        $dal = new LojaDAL();
        return $dal->pegarPorUsuario($id_usuario, $id_loja);
    }

    /**
     * @throws Exception
     * @param string $slug
     * @return LojaInfo
     */
    public function pegarPorSlug($slug) {
        $dal = new LojaDAL();
        return $dal->pegarPorSlug($slug);
    }

    /**
     * @param array<string,string> $postData
     * @param LojaInfo $loja
     */
	public function pegarDoPost($postData, &$loja = null) {
	    if (is_null($loja)) {
	        $loja = new LojaInfo();
        }
        if (array_key_exists("id_loja", $postData)) {
	        $loja->setId($postData["id_loja"]);
        }
        if (array_key_exists("id_seguimento", $postData)) {
            $loja->setIdSeguimento($postData["id_seguimento"]);
        }
        if (array_key_exists("nome", $postData)) {
            $loja->setNome($postData["nome"]);
        }
        if (array_key_exists("cnpj", $postData)) {
            $loja->setCnpj($postData["cnpj"]);
        }
        if (array_key_exists("email", $postData)) {
            $loja->setEmail($postData["email"]);
        }
        if (array_key_exists("descricao", $postData)) {
            $loja->setDescricao($postData["descricao"]);
        }
        if (array_key_exists("cep", $postData)) {
            $loja->setCep($postData["cep"]);
        }
        if (array_key_exists("logradouro", $postData)) {
            $loja->setLogradouro($postData["logradouro"]);
        }
        if (array_key_exists("complemento", $postData)) {
            $loja->setComplemento($postData["complemento"]);
        }
        if (array_key_exists("numero", $postData)) {
            $loja->setNumero($postData["numero"]);
        }
        if (array_key_exists("bairro", $postData)) {
            $loja->setBairro($postData["bairro"]);
        }
        if (array_key_exists("cidade", $postData)) {
            $loja->setCidade($postData["cidade"]);
        }
        if (array_key_exists("uf", $postData)) {
            $loja->setUf($postData["uf"]);
        }
        if (array_key_exists("latitude", $postData)) {
            $loja->setLatitude($postData["latitude"]);
        }
        if (array_key_exists("longitude", $postData)) {
            $loja->setLongitude($postData["longitude"]);
        }
        if (array_key_exists("cod_gateway", $postData)) {
            $loja->setCodGateway($postData["cod_gateway"]);
        }
        if (array_key_exists("cod_situacao", $postData)) {
            $loja->setCodSituacao($postData["cod_situacao"]);
        }
        $loja->setUsaRetirar(intval($postData["usa_retirar"]) == 1);
        $loja->setUsaRetiradaMapeada(intval($postData["usa_retirada_mapeada"]) == 1);
        $loja->setUsaEntregar(intval($postData["usa_entregar"]) == 1);
        $loja->setControleEstoque(intval($postData["controle_estoque"]) == 1);
        $loja->setUsaGateway(intval($postData["usa_gateway"]) == 1);
        $loja->setAceitaCreditoOnline(intval($postData["aceita_credito_online"]) == 1);
        $loja->setAceitaDebitoOnline(intval($postData["aceita_debito_online"]) == 1);
        $loja->setAceitaBoleto(intval($postData["aceita_boleto"]) == 1);
        $loja->setAceitaDinheiro(intval($postData["aceita_dinheiro"]) == 1);
        $loja->setAceitaCartaoOffline(intval($postData["aceita_cartao_offline"]) == 1);
        if (array_key_exists("estoque_minimo", $postData)) {
            $loja->setEstoqueMinimo(intval($postData["estoque_minimo"]));
        }
        if (array_key_exists("valor_minimo", $postData)) {
            $loja->setValorMinimo(floatvalx($postData["valor_minimo"]));
        }
        if (array_key_exists("mensagem_retirada", $postData)) {
            $loja->setMensagemRetirada($postData["mensagem_retirada"]);
        }
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $slug
     * @return string
     */
    private function slugValido($id_loja, $slug)
    {
        $slug = strtolower($slug);
        $loja = $this->pegarPorSlug($slug);
        if (!is_null($loja) && $loja->getId() != $id_loja) {
            $out = array();
            preg_match_all("#(.*?)-(\d{1,2})#i", $slug, $out, PREG_PATTERN_ORDER);
            if (!isNullOrEmpty($out[1][0]) && is_numeric($out[2][0])) {
                $slug = $this->slugValido($id_loja, $out[1][0] . "-" . (intval($out[2][0]) + 1));
            }
            else {
                $slug = $this->slugValido($id_loja, $slug . '-2');
            }
        }
        return $slug;
    }

	/**
	 * @throws Exception
	 * @param LojaInfo $loja
	 */
	protected function validar(&$loja) {

		if (isNullOrEmpty($loja->getNome())) {
			throw new Exception('Preencha o nome da loja.');
		}
        if (isNullOrEmpty($loja->getEmail())) {
            throw new Exception('Preencha o email da loja.');
        }
		if (isNullOrEmpty($loja->getCodSituacao())) {
			throw new Exception('Preencha a situação.');
		}
        if (!isNullOrEmpty($loja->getCnpj())) {
		    $validarCnpj = new ValidaCpfCnpj($loja->getCnpj());
		    if (!$validarCnpj->validar()) {
		        throw new Exception("O CNPJ está inválido.");
            }
        }

        $latitude = $loja->getLatitude();
        $latitude = str_replace(",", ".", $latitude);
        $loja->setLatitude(floatval($latitude));
        if ($loja->getLatitude() == 0) {
            throw new Exception("Informe a latitude.");
        }

        $longitude = $loja->getLongitude();
        $longitude = str_replace(",", ".", $longitude);
        $loja->setLongitude(floatval($longitude));
        if ($loja->getLongitude() == 0) {
            throw new Exception("Informe a longitude.");
        }

        if (strlen($loja->getNome()) > 60) {
            $loja->setNome(cortarTexto($loja->getNome(), 60));
        }
        if (strlen($loja->getEmail()) > 300) {
            $loja->setEmail(cortarTexto($loja->getEmail(), 300));
        }

        $cep = $loja->getCep();
        $cep = preg_replace("/[^0-9]/", "", $cep);
        if (strlen($cep) > 10) {
            $cep = cortarTexto( $cep, 10);
        }
        $loja->setCep($cep);

        if (strlen($loja->getLogradouro()) > 60) {
            $loja->setLogradouro(cortarTexto($loja->getLogradouro(), 60));
        }
        if (strlen($loja->getComplemento()) > 40) {
            $loja->setComplemento(cortarTexto($loja->getComplemento(), 40));
        }
        if (strlen($loja->getNumero()) > 20) {
            $loja->setNumero(cortarTexto($loja->getNumero(), 20));
        }
        if (strlen($loja->getBairro()) > 60) {
            $loja->setBairro(cortarTexto($loja->getBairro(), 60));
        }
        if (strlen($loja->getCidade()) > 50) {
            $loja->setCidade(cortarTexto($loja->getCidade(), 50));
        }
        $loja->setUf(strtoupper(cortarTexto($loja->getUf(), 2)));
        if (strlen($loja->getDescricao()) > 500) {
            $loja->setDescricao(cortarTexto($loja->getDescricao(), 500));
        }
        if (strlen($loja->getMensagemRetirada()) > 300) {
            $loja->setMensagemRetirada(cortarTexto($loja->getMensagemRetirada(), 300));
        }

        $slug = sanitize_slug(strtolower(trim($loja->getNome())));
        $loja->setSlug($slug);
        $loja->setSlug($this->slugValido($loja->getId(), $loja->getSlug()));
        $loja->setUf(strtoupper($loja->getUf()));

        if (LojaBLL::usaDebitoOnline() != true) {
            $loja->setAceitaDebitoOnline(false);
        }
        if (LojaBLL::usaPagamentoOnline() != true) {
            $loja->setAceitaCreditoOnline(false);
            $loja->setAceitaDebitoOnline(false);
            $loja->setAceitaBoleto(false);
        }
	}

	/**
	 * @throws Exception
	 * @param LojaInfo $loja
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($loja, $transaction = true) {
		$this->validar($loja);
		$dal = new LojaDAL();
		$dalUsuario = new UsuarioLojaDAL();
		$dalOpcao = new LojaOpcaoDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
            $loja->listarUsuario();
			$id_loja = $dal->inserir($loja);
			foreach ($loja->listarUsuario() as $usuario) {
                $usuario->setIdLoja($id_loja);
                $dalUsuario->inserir($usuario);
            }
            foreach ($loja->listarOpcao() as $opcao) {
                $opcao->setIdLoja($id_loja);
                $dalOpcao->inserir($opcao);
            }
            $log = (new LogInfo())
                ->setIdUsuario(UsuarioBLL::pegarIdUsuarioAtual())
                ->setCodTipo(LogInfo::INFORMACAO)
                ->setTitulo(sprintf("Loja '%s' - Incluído", $loja->getNome()))
                ->setDescricao(print_r($loja->jsonSerialize(), true));
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
		return $id_loja;
	}

	/**
	 * @throws Exception
	 * @param LojaInfo $loja
	 * @param bool $transaction
	 */
	public function alterar($loja, $transaction = true) {
		$this->validar($loja);
		$dal = new LojaDAL();
        $dalUsuario = new UsuarioLojaDAL();
        $dalOpcao = new LojaOpcaoDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
            $loja->listarUsuario();
			$dalUsuario->limpar($loja->getId());
			$dal->alterar($loja);
            foreach ($loja->listarUsuario() as $usuario) {
                $usuario->setIdLoja($loja->getId());
                $dalUsuario->inserir($usuario);
            }

            $loja->listarOpcao();
            $dalOpcao->limpar($loja->getId());
            foreach ($loja->listarOpcao() as $opcao) {
                $opcao->setIdLoja($loja->getId());
                $dalOpcao->inserir($opcao);
            }

            $log = (new LogInfo())
                ->setIdUsuario(UsuarioBLL::pegarIdUsuarioAtual())
                ->setCodTipo(LogInfo::INFORMACAO)
                ->setTitulo(sprintf("Loja '%s' - Alterado", $loja->getNome()))
                ->setDescricao(print_r($loja->jsonSerialize(), true));
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
	 * @param int $id_loja
	 * @param bool $transaction
	 */
	public function excluir($id_loja, $transaction = true) {
	    $regraProduto = new ProdutoBLL();
	    $quantidade = $regraProduto->pegarQuantidadePorLoja($id_loja);
	    if ($quantidade > 0) {
	        $mensagem = "Essa loja possui %s produto(s). Exclua-o(s) antes de excluir a loja.";
	        throw new LojaException(sprintf($mensagem, $quantidade));
        }
        $regraCategoria = new CategoriaBLL();
	    $quantidade = $regraCategoria->pegarQuantidadePorLoja($id_loja);
        if ($quantidade > 0) {
            $mensagem = "Essa loja possui %s categoria(s). Exclua-a(s) antes de excluir a loja.";
            throw new LojaException(sprintf($mensagem, $quantidade));
        }

		$dal = new LojaDAL();
        $dalUsuario = new UsuarioLojaDAL();
        $dalOpcao = new LojaOpcaoDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
            $dalUsuario->limpar($id_loja);
			$dalOpcao->limpar($id_loja);
			$dal->excluir($id_loja);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
            $loja = $this->pegar($id_loja);
            $log = (new LogInfo())
                ->setIdUsuario(UsuarioBLL::pegarIdUsuarioAtual())
                ->setCodTipo(LogInfo::ERRO)
                ->setTitulo(sprintf("Loja '%s' - Erro ao excluir", $loja->getNome()))
                ->setDescricao($e->getTraceAsString());
            $regraLog = LogFactory::create();
            $regraLog->inserir($log, false);
			throw new Exception("Não foi possível excluir. A loja ainda possui vínculos.", 0, $e);
		}
	}

    /**
     * @throws Exception
     * @return LojaInfo|null
     */
    public function pegarAtual() {
        if (LojaBLL::usaLojaCookie() == true) {
            $cookie = new CookieBLL();
            $texto = $cookie->pegar(LojaBLL::LOJA_COOKIE);
            if (isNullOrEmpty($texto)) {
                return null;
            }
            $json = json_decode($texto);
            if (is_null($json)) {
                return null;
            }
            return LojaInfo::fromJson($json);
        }
        return null;
    }

    /**
     * @param LojaInfo $loja
     * @throws Exception
     */
    public function gravarAtual(LojaInfo $loja) {
        if (LojaBLL::usaLojaCookie() == true) {
            $cookie = new CookieBLL();
            $texto = json_encode($loja);
            $cookie->gravar(LojaBLL::LOJA_COOKIE, $texto);
        }
    }

    /**
     * Remover Cookie de endereço
     */
    public function limparAtual() {
        if (LojaBLL::usaLojaCookie() == true) {
            $cookie = new CookieBLL();
            $cookie->remover(LojaBLL::LOJA_COOKIE);
        }
    }

    /**
     * @return int|null
     */
    public function getRaio() {
        $cookie = new CookieBLL();
        $raio = intval($cookie->pegar(LojaBLL::RAIO_COOKIE));
        if (isNullOrEmpty($raio)) {
            return null;
        }
        return intval($raio);
    }

    /**
     * @param int $raio
     * @throws Exception
     */
    public function setRaio($raio) {
        $cookie = new CookieBLL();
        $cookie->gravar(LojaBLL::RAIO_COOKIE, $raio);
    }

    /**
     * @param LojaInfo $loja
     * @param UsuarioInfo|null $usuario
     * @throws Exception
     */
    public function validarPermissao(LojaInfo $loja, UsuarioInfo $usuario = null) {
        if (is_null($usuario)) {
            $usuario = UsuarioBLL::pegarUsuarioAtual();
        }
        if (is_null($usuario)) {
            throw new Exception("Usuário não informado.");
        }
        if (is_null($loja)) {
            throw new Exception("Loja não definida.");
        }
        if (!($usuario->temPermissao(UsuarioInfo::ADMIN))) {
            $lojaAtual = $this->pegarPorUsuario($usuario->getId(), $loja->getId());
            if (is_null($lojaAtual)) {
                throw new Exception("Nenhuma loja vinculada ao seu usuário.");
            }
        }
    }

    /**
     * @param UsuarioInfo|null $usuario
     * @return LojaInfo
     * @throws Exception
     */
    public function pegarPorUsuarioComAcesso(UsuarioInfo $usuario = null) {
        if (is_null($usuario)) {
            $usuario = UsuarioBLL::pegarUsuarioAtual();
        }
        if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
            $loja = $this->pegarAtual();
            if (is_null($loja)) {
                $lojas = $this->listar();
                if (!(count($lojas) > 0)) {
                    throw new Exception("Nenhuma loja vinculada ao seu usuário.");
                }
                /** @var LojaInfo $loja */
                $loja = array_values($lojas)[0];
            }
        }
        else {
            $lojas = $this->listarPorUsuario($usuario->getId());
            if (!(count($lojas) > 0)) {
                throw new Exception("Nenhuma loja vinculada ao seu usuário.");
            }
            /** @var LojaInfo $loja */
            $loja = array_values($lojas)[0];
        }
        return $loja;
    }

}

