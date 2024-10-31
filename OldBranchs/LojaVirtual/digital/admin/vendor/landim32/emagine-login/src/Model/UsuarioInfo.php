<?php
namespace Emagine\Login\Model;

use Emagine\Login\Factory\GrupoFactory;
use Emagine\Login\Factory\UsuarioFactory;
use stdClass;
use JsonSerializable;
use Exception;
use Emagine\Base\BLL\ValidaCpfCnpj;
use Emagine\Base\BLL\ValidaTelefone;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\BLL\GrupoBLL;
use Emagine\Login\DALFactory\UsuarioEnderecoDALFactory;
use Emagine\Login\DALFactory\UsuarioPreferenciaDALFactory;

/**
 * Class UsuarioInfo
 * @package EmagineAuth\Model
 * @tablename usuario
 * @author EmagineCRUD
 */
class UsuarioInfo implements JsonSerializable {

    const ADMIN = "admin";
    const GERENCIAR_USUARIO = "gerenciar-usuario";
    const INCLUIR_USUARIO = "incluir-usuario";
    const GERENCIAR_GRUPO = "gerenciar-grupo";
    const VISUALIZAR_PERMISSAO = "visualizar-permissao";

	const ATIVO = 1;
	const AGUARDANDO_VALIDACAO = 2;
	const BLOQUEADO = 3;
	const INATIVO = 4;

	protected $id_usuario;
    protected $foto;
    protected $data_inclusao;
    protected $ultima_alteracao;
    protected $ultimo_login;
    protected $email;
    protected $slug;
    protected $nome;
    protected $senha;
    protected $telefone;
    protected $cpf_cnpj;
    protected $cod_situacao;
    protected $enderecos = null;
    protected $preferencias = null;
    protected $grupos = null;
    protected $permissoes = null;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_usuario;
	}

	/**
	 * @param int $value
	 */
	public function setId($value) {
		$this->id_usuario = $value;
	}

	/**
	 * @return string
	 */
	public function getFoto() {
        //if (isNullOrEmpty($this->foto)) {
        //    return ImagemAnonima::gerar();
        //}
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
	public function getDataInclusao() {
		return $this->data_inclusao;
	}

    /**
     * @return string
     */
	public function getDataInclusaoStr() {
	    return date("d/m/Y H:i", strtotime($this->data_inclusao));
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
     * @return string
     */
	public function getUltimaAlteracaoStr() {
        return date("d/m/Y H:i", strtotime($this->ultima_alteracao));
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
	public function getUltimoLogin() {
		return $this->ultimo_login;
	}

	/**
	 * @param string $value
	 */
	public function setUltimoLogin($value) {
		$this->ultimo_login = $value;
	}

    /**
     * @return string
     */
    public function getUltimoLoginStr() {
        return date("d/m/Y H:i", strtotime($this->ultimo_login));
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
	public function getSenha() {
		return $this->senha;
	}

	/**
	 * @param string $value
	 */
	public function setSenha($value) {
		$this->senha = $value;
	}

	/**
	 * @return string
	 */
	public function getTelefone() {
		return $this->telefone;
	}

	/**
	 * @param string $value
	 */
	public function setTelefone($value) {
		$this->telefone = $value;
	}

    /**
     * @return string
     */
	public function getTelefoneStr() {
	    if (!isNullOrEmpty($this->getTelefone())) {
	        $validaTelefone = new ValidaTelefone($this->getTelefone());
            return $validaTelefone->formatar();
        }
        return "";
    }

    /**
     * @return string
     */
	public function getCpfCnpj() {
	    return $this->cpf_cnpj;
    }

    /**
     * @param string $value
     */
    public function setCpfCnpj($value) {
	    $this->cpf_cnpj = $value;
    }

    /**
     * @return string
     */
    public function getCpfCnpjStr() {
        if (!isNullOrEmpty($this->getCpfCnpj())) {
            $validaCpfCnpj = new ValidaCpfCnpj($this->getCpfCnpj());
            return $validaCpfCnpj->formatar();
        }
        return "";
    }

    /**
     * @return bool
     */
    public function getPJ() {
        if (!isNullOrEmpty($this->getCpfCnpj())) {
            $validaCpfCnpj = new ValidaCpfCnpj($this->getCpfCnpj());
            return $validaCpfCnpj->validarCnpj();
        }
        return false;
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
	 * @return string
	 */
	public function getSituacaoStr() {
		$regraUsuario = UsuarioFactory::create();
		$lista = $regraUsuario->listarSituacao();
		return $lista[$this->getCodSituacao()];
	}

    /**
     * @return string
     */
	public function getSituacaoClasse() {
	    switch ($this->getCodSituacao()) {
            case UsuarioInfo::ATIVO:
                return "label label-success";
                break;
            case UsuarioInfo::INATIVO:
                return "label label-danger";
                break;
            case UsuarioInfo::BLOQUEADO:
                return "label label-danger";
                break;
            case UsuarioInfo::AGUARDANDO_VALIDACAO:
                return "label label-warning";
                break;
            default:
                return "label label-primary";
                break;
        }
    }

    /**
     * @throws Exception
     * @return UsuarioEnderecoInfo[]
     */
    public function listarEndereco() {
        if (is_null($this->enderecos)) {
            if ($this->getId() > 0) {
                $dal = UsuarioEnderecoDALFactory::create();
                $this->enderecos = $dal->listar($this->getId());
            }
            else {
                $this->limparEndereco();
            }
        }
        return $this->enderecos;
    }

    /**
     * Limpa todos os Preferências relacionados com Usuários.
     */
    public function limparEndereco() {
        $this->enderecos = array();
    }

    /**
     * @throws Exception
     * @param UsuarioEnderecoInfo $endereco
     */
    public function adicionarEndereco($endereco) {
        $this->listarEndereco();
        $this->enderecos[] = $endereco;
    }

	/**
     * @throws Exception
	 * @return UsuarioPreferenciaInfo[]
	 */
	public function listarPreferencia() {
		if (is_null($this->preferencias)) {
		    if ($this->getId() > 0) {
                $dalPreferencia = UsuarioPreferenciaDALFactory::create();
                $this->preferencias = array();
                foreach ($dalPreferencia->listarPorIdUsuario($this->getId()) as $pref) {
                    $this->preferencias[$pref->getChave()] = $pref;
                }
            }
            else {
		        $this->limparPreferencia();
            }
		}
		return $this->preferencias;
	}

	/**
	 * Limpa todos os Preferências relacionados com Usuários.
	 */
	public function limparPreferencia() {
		$this->preferencias = array();
	}

	/**
     * @throws Exception
	 * @param UsuarioPreferenciaInfo $preferencia
	 */
	public function adicionarPreferencia($preferencia) {
		$this->listarPreferencia();
		$this->preferencias[$preferencia->getChave()] = $preferencia;
	}

    /**
     * @throws Exception
     * @param string $chave
     * @return string
     */
	public function getPreferencia($chave) {
        $this->listarPreferencia();
        /** @var UsuarioPreferenciaInfo $preferencia */
        $preferencia = $this->preferencias[$chave];
        if (!is_null($preferencia)) {
            return $preferencia->getValor();
        }
        return "";
    }

    /**
     * @throws Exception
     * @param string $chave
     * @param string $valor
     */
    public function setPreferencia($chave, $valor) {
        $this->listarPreferencia();
        /** @var UsuarioPreferenciaInfo $preferencia */
        $preferencia = $this->preferencias[$chave];
        if (is_null($preferencia)) {
            $preferencia = new UsuarioPreferenciaInfo();
            $preferencia->setIdUsuario($this->getId());
            $preferencia->setChave($chave);
            $preferencia->setValor($valor);
            $this->preferencias[$chave] = $preferencia;
        }
        else {
            $preferencia->setValor($valor);
        }
    }

    /**
     * @throws Exception
     * @param string $chave
     */
    public function removerPreferencia($chave) {
        $this->listarPreferencia();
        unset($this->preferencias[$chave]);
    }

	/**
     * @throws Exception
	 * @return GrupoInfo[]
	 */
	public function listarGrupo() {
		if (is_null($this->grupos)) {
		    if ($this->getId() > 0) {
                $regraGrupo = GrupoFactory::create();
                $this->grupos = $regraGrupo->listarPorIdUsuario($this->getId());
            }
            else {
                $this->limparGrupo();
            }
		}
		return $this->grupos;
	}

	/**
	 * Limpa todos os Grupos de Usuários relacionados com Usuários.
	 */
	public function limparGrupo() {
		$this->grupos = array();
	}

	/**
     * @throws Exception
	 * @param GrupoInfo $grupo
	 */
	public function adicionarGrupo($grupo) {
		$this->listarGrupo();
		$this->grupos[] = $grupo;
	}

    /**
     * @throws Exception
     * @param int $id_grupo
     * @return bool
     */
	public function temGrupo($id_grupo) {
	    $retorno = false;
	    foreach ($this->listarGrupo() as $grupo) {
	        if ($grupo->getId() == $id_grupo) {
	            $retorno = true;
	            break;
            }
        }
        return $retorno;
    }

    /**
     * @throws Exception
     * @return string[]
     */
    public function listarPermissao() {
	    if (is_null($this->permissoes)) {
            $this->permissoes = array();
            foreach ($this->listarGrupo() as $grupo) {
                foreach ($grupo->listarPermissao() as $permissao) {
                    if (!in_array($permissao->getSlug(), $this->permissoes)) {
                        $this->permissoes[] = $permissao->getSlug();
                    }
                }
            }
        }
        return $this->permissoes;
    }

    /**
     * @throws Exception
     * @param string $chave
     * @return bool
     */
    public function temPermissao($chave) {
        $permissoes = $this->listarPermissao();
        return (
            in_array(UsuarioInfo::ADMIN, $permissoes) ||
            in_array($chave, $permissoes)
        );
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getGrupoStr() {
	    $grupos = array();
        foreach ($this->listarGrupo() as $grupo) {
            $grupos[] = $grupo->getNome();
        }
        return implode(", ", $grupos);
    }

    /**
     * Pegar a URL da foto do usuário
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getThumbnailUrl($width = 40, $height = 40) {
        $regraUsuario = UsuarioFactory::create();
        return $regraUsuario->getThumbnailUrl($this->getFoto(), $width, $height);
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getFotoUrl($width = 40, $height = 40) {
        return $this->getThumbnailUrl($width, $height);
    }

	/**
     * @throws Exception
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_usuario = $this->getId();
		$value->foto = $this->getFoto();
        $value->foto_url = $this->getFotoUrl(120, 120);
		$value->data_inclusao = $this->getDataInclusao();
		$value->ultima_alteracao = $this->getUltimaAlteracao();
		$value->ultimo_login = $this->getUltimoLogin();
		$value->email = $this->getEmail();
		$value->slug = $this->getSlug();
		$value->nome = $this->getNome();
		$value->senha = $this->getSenha();
		$value->telefone = $this->getTelefone();
        $value->telefone_str = $this->getTelefoneStr();
		$value->cpf_cnpj = $this->getCpfCnpj();
        $value->cpf_cnpj_str = $this->getCpfCnpjStr();
        $value->pj = $this->getPJ();
		$value->cod_situacao = $this->getCodSituacao();
		$value->situacao = $this->getSituacaoStr();

		$enderecos = $this->listarEndereco();
		if (count($enderecos) > 0) {
            $value->enderecos = array();
            foreach ($enderecos as $endereco) {
                $value->enderecos[] = $endereco->jsonSerialize();
            }
        }
        $preferencias = $this->listarPreferencia();
        if (count($preferencias) > 0) {
            $value->preferencias = array();
            foreach ($preferencias as $item) {
                $value->preferencias[] = $item->jsonSerialize();
            }
        }
        $grupos = $this->listarGrupo();
        if (count($grupos) > 0) {
            $value->grupos = array();
            foreach ($grupos as $item) {
                $value->grupos[] = $item->jsonSerialize();
            }
        }
		return $value;
	}

	/**
     * @throws Exception
	 * @param stdClass $value
	 * @return UsuarioInfo
	 */
	public static function fromJson($value) {
		$usuario = new UsuarioInfo();
		$usuario->setId($value->id_usuario);
		$usuario->setFoto($value->foto);
		$usuario->setDataInclusao($value->data_inclusao);
		$usuario->setUltimaAlteracao($value->ultima_alteracao);
		$usuario->setUltimoLogin($value->ultimo_login);
		$usuario->setEmail($value->email);
		$usuario->setSlug($value->slug);
		$usuario->setNome($value->nome);
		$usuario->setSenha($value->senha);
		$usuario->setTelefone($value->telefone);
		$usuario->setCpfCnpj($value->cpf_cnpj);
		$usuario->setCodSituacao($value->cod_situacao);
        if (isset($value->enderecos)) {
            $usuario->limparEndereco();
            foreach ($value->enderecos as $endereco) {
                $usuario->adicionarEndereco(UsuarioEnderecoInfo::fromJson($endereco));
            }
        }
		if (isset($value->preferencias)) {
			$usuario->limparPreferencia();
			foreach ($value->preferencias as $item) {
				$usuario->adicionarPreferencia(UsuarioPreferenciaInfo::fromJson($item));
			}
		}
		if (isset($value->grupos)) {
			$usuario->limparGrupo();
			foreach ($value->grupos as $item) {
				$usuario->adicionarGrupo(GrupoInfo::fromJson($item));
			}
		}
		return $usuario;
	}

}

