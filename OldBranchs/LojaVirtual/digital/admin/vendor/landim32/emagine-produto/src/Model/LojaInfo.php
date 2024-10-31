<?php
namespace Emagine\Produto\Model;

use stdClass;
use Exception;
use JsonSerializable;
use Emagine\Base\BLL\ValidaCpfCnpj;
use Emagine\Produto\DAL\LojaOpcaoDAL;
use Emagine\Produto\DAL\UsuarioLojaDAL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Produto\BLL\SeguimentoBLL;

/**
 * Class LojaInfo
 * @package EmagineProduto\Model
 * @tablename loja
 * @author EmagineCRUD
 */
class LojaInfo implements JsonSerializable {

    const GERENCIAR_LOJA = "gerenciar-loja";
    const CONFIGURA_LOJA = "configura-loja";

	const ATIVO = 1;
	const INATIVO = 2;

	const GATEWAY_CIELO = "cielo";
	const GATEWAY_IPAG = "ipag";
	const GATEWAY_YAPAY = "yapag";

	private $id_loja;
	private $id_seguimento;
	private $slug;
	private $email;
	private $foto;
	private $nome;
	private $cnpj;
	private $descricao;
	private $cep;
	private $logradouro;
	private $complemento;
	private $numero;
	private $bairro;
	private $cidade;
	private $uf;
	private $latitude;
	private $longitude;
	private $usa_retirar;
    private $usa_retirada_mapeada;
	private $usa_entregar;
	private $controle_estoque;
	private $usa_gateway;
    private $cod_gateway;
    private $aceita_credito_online;
    private $aceita_debito_online;
    private $aceita_boleto;
    private $aceita_dinheiro;
    private $aceita_cartao_offline;
    private $estoque_minimo;
    private $valor_minimo;
	private $distancia;
	private $cod_situacao;
	private $nota;
	private $mensagem_retirada;
	private $seguimento = null;
	private $usuarios = null;
	private $opcoes = null;
	private $quantidade = null;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_loja;
	}

	/**
	 * @param int $value
	 */
	public function setId($value) {
		$this->id_loja = $value;
	}

    /**
     * @return int
     */
    public function getIdSeguimento() {
        return $this->id_seguimento;
    }

    /**
     * @param int $value
     */
    public function setIdSeguimento($value) {
        $this->id_seguimento = $value;
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
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $value
     */
    public function setEmail($value) {
        $this->email = $value;
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
	public function getCnpj() {
	    return $this->cnpj;
    }

    /**
     * @param string $value
     */
    public function setCnpj($value) {
	    $this->cnpj = $value;
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
     * @return string
     */
	public function getCep() {
	    return $this->cep;
    }

    /**
     * @param string $value
     */
    public function setCep($value) {
	    $this->cep = $value;
    }

    /**
     * @return string
     */
	public function getLogradouro() {
	    return $this->logradouro;
    }

    /**
     * @param $value
     */
    public function setLogradouro($value) {
	    $this->logradouro = $value;
    }

    /**
     * @return string
     */
    public function getComplemento() {
        return $this->complemento;
    }

    /**
     * @param string $value
     */
    public function setComplemento($value) {
        $this->complemento = $value;
    }

    /**
     * @return string
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * @param string $value
     */
    public function setNumero($value) {
        $this->numero = $value;
    }

    /**
     * @return string
     */
    public function getBairro() {
        return $this->bairro;
    }

    /**
     * @param string $value
     */
    public function setBairro($value) {
        $this->bairro = $value;
    }

    /**
     * @return string
     */
    public function getCidade() {
        return $this->cidade;
    }

    /**
     * @param string $value
     */
    public function setCidade($value) {
        $this->cidade = $value;
    }

    /**
     * @return string
     */
    public function getUf() {
        return $this->uf;
    }

    /**
     * @param $value
     */
    public function setUf($value) {
        $this->uf = $value;
    }

    /**
     * @return float
     */
	public function getLatitude() {
	    return $this->latitude;
    }

    /**
     * @param float $value
     */
    public function setLatitude($value) {
	    $this->latitude = $value;
    }

    /**
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * @param float $value
     */
    public function setLongitude($value) {
        $this->longitude = $value;
    }

    /**
     * @return bool
     */
    public function getUsaRetirar() {
        return $this->usa_retirar;
    }

    /**
     * @param bool $value
     */
    public function setUsaRetirar($value) {
        $this->usa_retirar = $value;
    }

    /**
     * @return bool
     */
    public function getUsaRetiradaMapeada() {
        return $this->usa_retirada_mapeada;
    }

    /**
     * @param bool $value
     */
    public function setUsaRetiradaMapeada($value) {
        $this->usa_retirada_mapeada = $value;
    }

    /**
     * @return bool
     */
    public function getUsaEntregar() {
        return $this->usa_entregar;
    }

    /**
     * @param bool $value
     */
    public function setUsaEntregar($value) {
        $this->usa_entregar = $value;
    }

    /**
     * @return bool
     */
    public function getControleEstoque() {
        return $this->controle_estoque;
    }

    /**
     * @param bool $value
     */
    public function setControleEstoque($value) {
        $this->controle_estoque = $value;
    }

    /**
     * @return bool
     */
    public function getUsaGateway() {
        return $this->usa_gateway;
    }

    /**
     * @param bool $value
     */
    public function setUsaGateway($value) {
        $this->usa_gateway = $value;
    }

    /**
     * @return string
     */
    public function getCodGateway() {
        return $this->cod_gateway;
    }

    /**
     * @param string $value
     */
    public function setCodGateway($value) {
        $this->cod_gateway = $value;
    }

    /**
     * @return string
     */
    public function getGatewayStr() {
        $bll = new LojaBLL();
        $gateways = $bll->listarGateway();
        if (array_key_exists($this->getCodGateway(), $gateways)) {
            return $gateways[$this->getCodGateway()];
        }
        return "Nenhum";
    }

    /**
     * @return bool
     */
    public function getAceitaCreditoOnline() {
        return $this->aceita_credito_online;
    }

    /**
     * @param bool $value
     */
    public function setAceitaCreditoOnline($value) {
        $this->aceita_credito_online = $value;
    }

    /**
     * @return bool
     */
    public function getAceitaDebitoOnline() {
        return $this->aceita_debito_online;
    }

    /**
     * @param bool $value
     */
    public function setAceitaDebitoOnline($value) {
        $this->aceita_debito_online = $value;
    }

    /**
     * @return bool
     */
    public function getAceitaBoleto() {
        return $this->aceita_boleto;
    }

    /**
     * @param bool $value
     */
    public function setAceitaBoleto($value) {
        $this->aceita_boleto = $value;
    }

    /**
     * @return bool
     */
    public function getAceitaDinheiro() {
        return $this->aceita_dinheiro;
    }

    /**
     * @param bool $value
     */
    public function setAceitaDinheiro($value) {
        $this->aceita_dinheiro = $value;
    }

    /**
     * @return bool
     */
    public function getAceitaCartaoOffline() {
        return $this->aceita_cartao_offline;
    }

    /**
     * @param bool $value
     */
    public function setAceitaCartaoOffline($value) {
        $this->aceita_cartao_offline = $value;
    }

    /**
     * @return int
     */
    public function getEstoqueMinimo() {
        return $this->estoque_minimo;
    }

    /**
     * @param int $value
     */
    public function setEstoqueMinimo($value) {
        $this->estoque_minimo = $value;
    }

    /**
     * @return double
     */
    public function getValorMinimo() {
        return $this->valor_minimo;
    }

    /**
     * @param double $value
     */
    public function setValorMinimo($value) {
        $this->valor_minimo = $value;
    }

    /**
     * @return string
     */
    public function getValorMinimoStr() {
        return "R$ " . number_format($this->getValorMinimo(), 2, ",", ".");
    }

    /**
     * @return double
     */
    public function getDistancia() {
        return $this->distancia;
    }

    /**
     * @param double $value
     */
    public function setDistancia($value) {
        $this->distancia = $value;
    }

    /**
     * @return string
     */
    public function getDistanciaStr() {
        $distancia = $this->getDistancia() / 1000;
        if ($this->getDistancia() > 0) {
            return number_format($distancia, 1, ",", ".") . "km";
        }
        return null;
    }

	/**
	 * @return int
	 */
	public function getCodSituacao() {
		return $this->cod_situacao;
	}

	/**
	 * @param int $value
	 */
	public function setCodSituacao($value) {
		$this->cod_situacao = $value;
	}

    /**
     * @return int
     */
	public function getNota() {
	    return $this->nota;
    }

    /**
     * @param int $value
     */
    public function setNota($value) {
	    $this->nota = $value;
    }

    /**
     * @return int
     */
    public function getMensagemRetirada() {
        return $this->mensagem_retirada;
    }

    /**
     * @param int $value
     */
    public function setMensagemRetirada($value) {
        $this->mensagem_retirada = $value;
    }

	/**
	 * @return string
	 */
	public function getSituacaoStr() {
		$bll = new LojaBLL();
		$lista = $bll->listarSituacao();
		return $lista[$this->getCodSituacao()];
	}

    /**
     * @return string
     */
	public function getSituacaoClasse() {
	    switch ($this->getCodSituacao()) {
            case LojaInfo::ATIVO:
                return "label label-primary";
                break;
            case LojaInfo::INATIVO:
                return "label label-danger";
                break;
            default:
                return "label label-default";
                break;
        }
    }

    /**
     * @return LojaOpcaoInfo[]
     * @throws Exception
     */
	public function listarOpcao() {
	    if (is_null($this->opcoes)) {
            $this->opcoes = array();
	        if ($this->getId() > 0) {
	            $dal = new LojaOpcaoDAL();
	            foreach ($dal->listar($this->getId()) as $opcao) {
	                $this->opcoes[$opcao->getChave()] = $opcao->getValor();
                }
            }
        }
        return $this->opcoes;
    }

    /**
     * @param string $chave
     * @param string $valor
     * @throws Exception
     */
	public function adicionarOpcao($chave, $valor) {
        $this->listarOpcao();
        $this->opcoes[$chave] = $valor;
    }

    /**
     * @param string $chave
     * @throws Exception
     */
    public function removerOpcao($chave) {
	    $this->listarOpcao();
	    unset($this->opcoes[$chave]);
    }

    /**
     * Limpar opções
     */
    public function limparOpcao() {
        $this->opcoes = array();
    }

    /**
     * @param UsuarioLojaInfo $usuario
     * @throws Exception
     */
	public function adicionarUsuario(UsuarioLojaInfo $usuario) {
	    $this->listarUsuario();
	    $this->usuarios[] = $usuario;
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     */
    public function removerUsuario($id_usuario) {
        $usuarios = array();
	    /** @var UsuarioLojaInfo $usuario */
        foreach ($this->listarUsuario() as $usuario) {
	        if ($usuario->getIdUsuario() != $id_usuario) {
	            $usuarios[] = $usuario;
            }
        }
        $this->usuarios = $usuarios;
    }

    /**
     * @throws Exception
     * @return UsuarioLojaInfo[]
     */
	public function listarUsuario() {
	    if (is_null($this->usuarios)) {
	        if ($this->getId() > 0) {
                $regraUsuario = new UsuarioLojaDAL();
                $this->usuarios = $regraUsuario->listar($this->getId());
            }
            else {
	            $this->limparUsuario();
            }
        }
        return $this->usuarios;
    }

    /**
     *
     */
    public function limparUsuario() {
        $this->usuarios = array();
    }

    /**
     * @return string
     */
    public function getPosicaoStr() {
        $str = "";
        $str .= number_format($this->getLatitude(), 5, ".", "");
        $str .= ",";
        $str .= number_format($this->getLongitude(), 5, ".", "");
        return $str;
    }

    /**
     * @return string
     */
    public function getEnderecoCompleto() {
        $endereco = array();
        if (!isNullOrEmpty($this->getLogradouro())) {
            $endereco[] = $this->getLogradouro();
        }
        if (!isNullOrEmpty($this->getComplemento())) {
            $endereco[] = $this->getComplemento();
        }
        if (!isNullOrEmpty($this->getNumero())) {
            $endereco[] = $this->getNumero();
        }
        if (!isNullOrEmpty($this->getBairro())) {
            $endereco[] = $this->getBairro();
        }
        if (!isNullOrEmpty($this->getCidade())) {
            $endereco[] = $this->getCidade();
        }
        if (!isNullOrEmpty($this->getUf())) {
            $endereco[] = $this->getUf();
        }
        if (!isNullOrEmpty($this->getCep())) {
            $endereco[] = $this->getCep();
        }
        return implode(", ", $endereco);
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
        return SITE_URL . sprintf("/loja/%sx%s/", $largura, $altura) . $this->getFoto();
    }

    /**
     * @return string
     */
    public function getCnpjStr() {
        $cnpj = $this->getCnpj();
        if (!isNullOrEmpty($cnpj)) {
            $validaCnpj = new ValidaCpfCnpj($cnpj);
            return $validaCnpj->formatar();
        }
        return "";
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getQuantidade()
    {
        if (is_null($this->quantidade) && $this->getId() > 0) {
            $regraProduto = new ProdutoBLL();
            $this->quantidade = $regraProduto->pegarQuantidadePorLoja($this->getId());
        }
        return $this->quantidade;
    }

    /**
     * @return SeguimentoInfo
     * @throws Exception
     */
    public function getSeguimento()
    {
        if (is_null($this->seguimento) && $this->getIdSeguimento() > 0) {
            $regraSeguimento = new SeguimentoBLL();
            $this->seguimento = $regraSeguimento->pegar($this->getIdSeguimento());
        }
        return $this->seguimento;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getNomeSeguimento() {
        $seguimento = $this->getSeguimento();
        if (!is_null($seguimento)) {
            return $seguimento->getNome();
        }
        return "";
    }

	/**
     * @throws Exception
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_loja = intval($this->getId());
		$value->id_seguimento = intval($this->getIdSeguimento());
		$value->slug = $this->getSlug();
		$value->foto = $this->getFoto();
        $value->foto_url = $this->getFotoUrl();
		$value->email = $this->getEmail();
		$value->cnpj = $this->getCnpj();
		$value->nome = $this->getNome();
		$value->descricao = $this->getDescricao();
		$value->cep = $this->getCep();
        $value->logradouro = $this->getLogradouro();
        $value->complemento = $this->getComplemento();
        $value->numero = $this->getNumero();
        $value->bairro = $this->getBairro();
        $value->cidade = $this->getCidade();
        $value->uf = $this->getUf();
        $value->endereco_completo = $this->getEnderecoCompleto();
		$value->latitude = floatval($this->getLatitude());
		$value->longitude = floatval($this->getLongitude());
        $value->usa_retirar = boolval($this->getUsaRetirar());
        $value->usa_entregar = boolval($this->getUsaEntregar());
        $value->usa_retirada_mapeada = boolval($this->getUsaRetiradaMapeada());
        $value->usa_gateway = boolval($this->getUsaGateway());
        $value->controle_estoque = intval($this->getControleEstoque());
        $value->cod_gateway = $this->getCodGateway();
        $value->aceita_credito_online = boolval($this->getAceitaCreditoOnline());
        $value->aceita_debito_online = boolval($this->getAceitaDebitoOnline());
        $value->aceita_boleto = boolval($this->getAceitaBoleto());
        $value->aceita_dinheiro = boolval($this->getAceitaDinheiro());
        $value->aceita_cartao_offline = boolval($this->getAceitaCartaoOffline());
        $value->estoque_minimo = intval($this->getEstoqueMinimo());
        $value->valor_minimo = floatval($this->getValorMinimo());
        $value->valor_minimo_str = $this->getValorMinimoStr();
		$value->distancia = intval($this->getDistancia());
        $value->distancia_str = $this->getDistanciaStr();
		$value->cod_situacao = intval($this->getCodSituacao());
		$value->nota = intval($this->getNota());
        $value->mensagem_retirada = $this->getMensagemRetirada();
		$usuarios = $this->listarUsuario();
		if (count($usuarios) > 0) {
            $value->usuarios = array();
            foreach ($usuarios as $usuario) {
                $value->usuarios[] = $usuario->jsonSerialize();
            }
        }
        $opcoes = $this->listarOpcao();
        if (count($opcoes) > 0) {
            $value->opcoes = array();
            foreach ($opcoes as $opcao) {
                $value->opcoes[] = $opcao->jsonSerialize();
            }
        }
		return $value;
	}

	/**
     * @throws Exception
	 * @param stdClass $value
	 * @return LojaInfo
	 */
	public static function fromJson($value) {
		$loja = new LojaInfo();
		$loja->setId($value->id_loja);
        $loja->setIdSeguimento($value->id_seguimento);
		$loja->setSlug($value->slug);
		$loja->setFoto($value->foto);
		$loja->setEmail($value->email);
		$loja->setNome($value->nome);
		$loja->setCnpj($value->cnpj);
		$loja->setDescricao($value->descricao);
		$loja->setCep($value->cep);
        $loja->setLogradouro($value->logradouro);
        $loja->setComplemento($value->complemento);
        $loja->setNumero($value->numero);
        $loja->setBairro($value->bairro);
        $loja->setCidade($value->cidade);
        $loja->setUf($value->uf);
		$loja->setLatitude($value->latitude);
		$loja->setLongitude($value->longitude);
        $loja->setUsaRetirar($value->usa_retirar);
        $loja->setUsaRetiradaMapeada($value->usa_retirada_mapeada);
        $loja->setUsaEntregar($value->usa_entregar);
        $loja->setControleEstoque($value->controle_estoque);
        $loja->setUsaGateway($value->usa_gateway);
        $loja->setCodGateway($value->cod_gateway);
        $loja->setAceitaCreditoOnline($value->aceita_credito_online);
        $loja->setAceitaDebitoOnline($value->aceita_debito_online);
        $loja->setAceitaBoleto($value->aceita_boleto);
        $loja->setAceitaDinheiro($value->aceita_dinheiro);
        $loja->setAceitaCartaoOffline($value->aceita_cartao_offline);
        $loja->setEstoqueMinimo($value->estoque_minimo);
        $loja->setValorMinimo($value->valor_minimo);
        $loja->setDistancia($value->distancia);
		$loja->setCodSituacao($value->cod_situacao);
        $loja->setMensagemRetirada($value->mensagem_retirada);
        $loja->limparUsuario();
        if (isset($value->usuarios)) {
            foreach ($value->usuarios as $usuario) {
                $loja->adicionarUsuario(UsuarioLojaInfo::fromJson($usuario));
            }
        }
        $loja->limparOpcao();
        if (isset($value->opcoes)) {
            foreach ($value->opcoes as $opcao) {
                $opt = LojaOpcaoInfo::fromJson($opcao);
                $loja->adicionarOpcao($opt->getChave(), $opt->getValor());
            }
        }
		return $loja;
	}

}

