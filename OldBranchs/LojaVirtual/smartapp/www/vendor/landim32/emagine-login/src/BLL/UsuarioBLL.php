<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/07/2017
 * Time: 13:24
 * Tablename: usuario
 */

namespace Emagine\Login\BLL;

use Emagine\Login\DALFactory\GrupoDALFactory;
use Emagine\Login\Factory\GrupoFactory;
use Emagine\Login\Factory\UsuarioFactory;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Base\BLL\MailJetBLL;
use Emagine\Base\BLL\ValidaCpfCnpj;
use Emagine\Login\DALFactory\UsuarioDALFactory;
use Emagine\Login\DALFactory\UsuarioEnderecoDALFactory;
use Emagine\Login\DALFactory\UsuarioPreferenciaDALFactory;
use Emagine\Login\Model\UsuarioInfo;

/**
 * Class UsuarioBLL
 * @package EmagineAuth\BLL
 * @tablename usuario
 * @author EmagineCRUD
 */
class UsuarioBLL {

    const TOKEN_VALIDACAO = "TOKEN_VALIDACAO";
    const USUARIO_LISTA = "usuario_lista";
    const USUARIO_ATUAL = "usuario_atual";
    const SITE_COOKIE_PATH = "/";
    const SITE_COOKIE_AUTH = "EMAGINE_AUTH_SITE_AUTH";
    const SITE_SECRET_KEY = "dk;l1894!851éds-fghjg4lui:è3afàzgq_f4fá.";

    /**
     * @return bool
     */
    public static function usaFoto() {
        if (defined("USUARIO_USA_FOTO")) {
            return (USUARIO_USA_FOTO == true);
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function usaLoginRoute() {
        if (defined("USA_LOGIN_ROUTE")) {
            return (USA_LOGIN_ROUTE === true);
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function getValidaEmail() {
        if (defined("USUARIO_VALIDA_EMAIL")) {
            return USUARIO_VALIDA_EMAIL;
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function getRequerValidacao() {
        if (defined("USUARIO_REQUER_VALIDACAO")) {
            return USUARIO_REQUER_VALIDACAO;
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function getTelefoneObrigatorio() {
        if (defined("USUARIO_TELEFONE_OBRIGATORIO")) {
            return USUARIO_TELEFONE_OBRIGATORIO;
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function getCpfCnpjObrigatorio() {
        if (defined("USUARIO_CPF_CNPJ_OBRIGATORIO")) {
            return USUARIO_CPF_CNPJ_OBRIGATORIO;
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function getCelularObrigatorio() {
        if (defined("USUARIO_CELULAR_OBRIGATORIO")) {
            return USUARIO_CELULAR_OBRIGATORIO;
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function getEnderecoObrigatorio() {
        if (defined("USUARIO_ENDERECO_OBRIGATORIO")) {
            return USUARIO_ENDERECO_OBRIGATORIO;
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function getLoginSimples() {
        if (defined("LOGIN_SIMPLES")) {
            return LOGIN_SIMPLES;
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function getCadastroSimples() {
        if (defined("CADASTRO_SIMPLES")) {
            return CADASTRO_SIMPLES;
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function getUfLivre() {
        if (defined("UF_LIVRE")) {
            return UF_LIVRE;
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function getCidadeLivre() {
        if (defined("CIDADE_LIVRE")) {
            return CIDADE_LIVRE;
        }
        return true;
    }

    /**
     * @return bool
     */
    public static function getBairroLivre() {
        if (defined("BAIRRO_LIVRE")) {
            return BAIRRO_LIVRE;
        }
        return true;
    }

	/**
	 * @return array<string,string>
	 */
	public function listarSituacao() {
		return array(
			UsuarioInfo::ATIVO => 'Ativo',
			UsuarioInfo::AGUARDANDO_VALIDACAO => 'Aguardando validação',
			UsuarioInfo::BLOQUEADO => 'Bloqueado',
			UsuarioInfo::INATIVO => 'Inativo'
		);
	}

	/**
     * @throws Exception
     * @param int $codSituacao
	 * @return UsuarioInfo[]
	 */
	public function listar($codSituacao = 0) {
		$dal = UsuarioDALFactory::create();
		return $dal->listar($codSituacao);
	}

    /**
     * @throws Exception
     * @param string $palavraChave
     * @return UsuarioInfo[]
     */
    public function buscaPorPalavra($palavraChave) {
        $dal = UsuarioDALFactory::create();
        return $dal->buscaPorPalavra($palavraChave);
    }


	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @return UsuarioInfo
	 */
	public function pegar($id_usuario) {
		$dal = UsuarioDALFactory::create();
		return $dal->pegar($id_usuario);
	}

    /**
     * @throws Exception
     * @param string $email
     * @return UsuarioInfo
     */
    public function pegarPorEmail($email) {
        $dal = UsuarioDALFactory::create();
        return $dal->pegarPorEmail($email);
    }

    /**
     * @throws Exception
     * @param string $slug
     * @return UsuarioInfo
     */
    public function pegarPorSlug($slug) {
        $dal = UsuarioDALFactory::create();
        return $dal->pegarPorSlug($slug);
    }

    /**
     * @throws Exception
     * @param array<string,string> $postData
     * @param UsuarioInfo|null $usuario
     * @return UsuarioInfo
     */
    public function pegarDoPost($postData, $usuario = null) {
        if (is_null($usuario)) {
            $usuario = new UsuarioInfo();
        }
        if (array_key_exists("email", $postData)) {
            $usuario->setEmail($postData["email"]);
        }
        if (array_key_exists("nome", $postData)) {
            $usuario->setNome($postData["nome"]);
        }
        if (array_key_exists("grupos", $postData)) {
            $grupos = array();
            $usuario->limparGrupo();
            $regraGrupo = GrupoFactory::create();
            foreach ($regraGrupo->listar() as $grupo) {
                $grupos[$grupo->getId()] = $grupo;
            }
            foreach ($postData["grupos"] as $id_grupo) {
                if (array_key_exists($id_grupo, $grupos)) {
                    $usuario->adicionarGrupo($grupos[$id_grupo]);
                }
            }
        }
        if (array_key_exists("telefone", $postData)) {
            $usuario->setTelefone($postData["telefone"]);
        }
        if (array_key_exists("cpf_cnpj", $postData)) {
            $usuario->setCpfCnpj($postData["cpf_cnpj"]);
        }
        if (array_key_exists("cod_situacao", $postData)) {
            $usuario->setCodSituacao($postData["cod_situacao"]);
        }
        return $usuario;
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param string $slug
     * @return string
     */
    public function slugValido($id_usuario, $slug)
    {
        $slug = strtolower($slug);
        $usuario = $this->pegarPorSlug($slug);
        if (!is_null($usuario) && $usuario->getId() != $id_usuario) {
            $out = array();
            preg_match_all("#(.*?)-(\d{1,2})#i", $slug, $out, PREG_PATTERN_ORDER);
            if (!isNullOrEmpty($out[1][0]) && is_numeric($out[2][0])) {
                $slug = $this->slugValido($id_usuario, $out[1][0] . "-" . (intval($out[2][0]) + 1));
            }
            else {
                $slug = $this->slugValido($id_usuario, $slug . '-2');
            }
        }
        return $slug;
    }

    /**
     * @throws Exception
     * @param string $token
     * @return UsuarioInfo|null
     */
    public function pegarPorToken($token) {
        $dalPreferencia = UsuarioPreferenciaDALFactory::create();
        $preferencia = $dalPreferencia->pegarPorValor(UsuarioBLL::TOKEN_VALIDACAO, $token);
        if (!is_null($preferencia)) {
            return $preferencia->getUsuario();
        }
        return null;
    }

    /**
     * @throws Exception
     * @param UsuarioInfo $usuario
     */
    protected function validarPreferencia(&$usuario) {
        foreach ($usuario->listarPreferencia() as $chave => $preferencia) {
            if (is_null($preferencia)) {
                throw new Exception('A preferência está nula.');
            }
            if (isNullOrEmpty($preferencia->getChave())) {
                throw new Exception('Foi informada uma preferência de usuário sem chave.');
            }
            if (isNullOrEmpty($preferencia->getValor())) {
                throw new Exception(sprintf('Nenhum valor para a chave %s.', $preferencia->getChave()));
            }
        }
    }

    /**
     * @throws Exception
     * @param UsuarioInfo $usuario
     */
    protected function validarEndereco(&$usuario)
    {
        if (count($usuario->listarEndereco()) <= 0) {
            throw new Exception('Nenhum endereço informado.');
        }
        foreach ($usuario->listarEndereco() as $endereco) {
            if (isNullOrEmpty($endereco->getLogradouro())) {
                throw new Exception('Preencha o logradouro.');
            }
            if (isNullOrEmpty($endereco->getBairro())) {
                throw new Exception('Selecione seu bairro.');
            }
            if (isNullOrEmpty($endereco->getCidade())) {
                throw new Exception('Selecione sua cidade.');
            }
            if (isNullOrEmpty($endereco->getUf())) {
                throw new Exception('Selecione seu estado.');
            }
        }
    }

	/**
	 * @throws Exception
	 * @param UsuarioInfo $usuario
	 */
	protected function validar(&$usuario) {
		if (!isNullOrEmpty($usuario->getEmail())) {
            if (!filter_var($usuario->getEmail(), FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email inválido.');
            }
        } else {
			throw new Exception('Preencha o seu Email.');
		}
		if (isNullOrEmpty($usuario->getNome())) {
			throw new Exception('Preencha o seu nome.');
		}
        if (UsuarioBLL::getCpfCnpjObrigatorio() == true) {
            if (isNullOrEmpty($usuario->getCpfCnpj())) {
                $validaCpfCnpj = new ValidaCpfCnpj($usuario->getCpfCnpj());
                if (!$validaCpfCnpj->validar()) {
                    throw new Exception('O CPF/CNPJ está inválido.');
                }
            }
        }
        if (UsuarioBLL::getTelefoneObrigatorio() == true) {
            if (isNullOrEmpty($usuario->getTelefone())) {
                throw new Exception('Preencha seu telefone.');
            }
        }
        $this->validarPreferencia($usuario);
        if (UsuarioBLL::getEnderecoObrigatorio() == true) {
            $this->validarEndereco($usuario);
        }
        if (!($usuario->getId() > 0)) {
            if (isNullOrEmpty($usuario->getSenha())) {
                throw new Exception('Preencha sua senha.');
            }
        }
        if (!($usuario->getCodSituacao() > 0)) {
            $codSituacao = (UsuarioBLL::getRequerValidacao() == true) ? UsuarioInfo::AGUARDANDO_VALIDACAO : UsuarioInfo::ATIVO;
            $usuario->setCodSituacao($codSituacao);
        }
        if (isNullOrEmpty($usuario->getSlug())) {
		    $slug = sanitize_slug(strtolower(trim($usuario->getNome())));
            $usuario->setSlug($slug);
        }
        $usuario->setSlug($this->slugValido($usuario->getId(), $usuario->getSlug()));
        if ($usuario->getCodSituacao() == UsuarioInfo::AGUARDANDO_VALIDACAO) {
		    $token = $usuario->getPreferencia(UsuarioBLL::TOKEN_VALIDACAO);
		    if (isNullOrEmpty($token)) {
                $token = md5(uniqid(rand(), true));
                $usuario->setPreferencia(UsuarioBLL::TOKEN_VALIDACAO, $token);
            }
        }
	}

	/**
	 * @throws Exception
	 * @param UsuarioInfo $usuario
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($usuario, $transaction = true) {
		$this->validar($usuario);
		$dal = UsuarioDALFactory::create();
		$dalPreferencia = UsuarioPreferenciaDALFactory::create();
		$dalEndereco = UsuarioEnderecoDALFactory::create();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$id_usuario = $dal->inserir($usuario);
            foreach ($usuario->listarEndereco() as $endereco) {
                $endereco->setIdUsuario($id_usuario);
                $dalEndereco->inserir($endereco);
            }
			foreach ($usuario->listarPreferencia() as $item) {
				$item->setIdUsuario($id_usuario);
                $dalPreferencia->inserir($item);
			}
			foreach ($usuario->listarGrupo() as $item) {
				$dal->adicionarGrupo($id_usuario, $item->getId());
			}
			if ($usuario->getCodSituacao() == UsuarioInfo::AGUARDANDO_VALIDACAO) {
                if ($this->getValidaEmail() == true) {
                    $this->enviarVerificacao($usuario);
                }
            }
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
		return $id_usuario;
	}

	/**
	 * @throws Exception
	 * @param UsuarioInfo $usuario
	 * @param bool $transaction
	 */
	public function alterar($usuario, $transaction = true) {
		$this->validar($usuario);
		if (!($usuario->getId() > 0)) {
            throw new Exception("Informe o ID do usuário.");
        }
		$dal = UsuarioDALFactory::create();
		$dalPreferencia = UsuarioPreferenciaDALFactory::create();
        $dalEndereco = UsuarioEnderecoDALFactory::create();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($usuario);
			$id_usuario = $usuario->getId();

            $usuario->listarEndereco();
            $dalEndereco->limpar($usuario->getId());
            foreach ($usuario->listarEndereco() as $endereco) {
                $endereco->setIdUsuario($id_usuario);
                $dalEndereco->inserir($endereco);
            }

			$usuario->listarPreferencia();
			$dalPreferencia->limpar($id_usuario);
			foreach ($usuario->listarPreferencia() as $chave => $item) {
				$item->setIdUsuario($id_usuario);
				$dalPreferencia->inserir($item);
			}

			$usuario->listarGrupo();
			$dal->limparGrupoPorIdUsuario($usuario->getId());
			foreach ($usuario->listarGrupo() as $item) {
				$dal->adicionarGrupo($id_usuario, $item->getId());
			}
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
     * @param string $id_usuario
     * @param string $fotoBase64
     */
	public function alterarFoto($id_usuario, $fotoBase64) {
	    $usuario = $this->pegar($id_usuario);
        $token = md5(uniqid(rand(), true));
        $usuarioDir = UPLOAD_PATH . '/usuario';
        if (!file_exists($usuarioDir)) {
            if (!is_writable($usuarioDir)) {
                throw new Exception(sprintf("Não tem permissão para criar o diretório %s.", $usuarioDir));
            }
            @mkdir($usuarioDir);
        }
        $arquivoFoto = UPLOAD_PATH . '/usuario/' . $token . '.png';
        //if (!is_writable($arquivoFoto)) {
        //    throw new Exception(sprintf("Não tem permissão para gravar o arquivo %s.", $arquivoFoto));
        //}
        $data = preg_replace('#^data:image/\w+;base64,#i', '', $fotoBase64);
        file_put_contents($arquivoFoto, base64_decode($data));

        $usuario->setFoto($token . ".png");
        $this->alterar($usuario);
    }

    /**
     * @param int $id_usuario
     * @param string $fotoStream
     * @param bool $transaction
     * @throws Exception
     */
    public function alterarFotoStream($id_usuario, $fotoStream, $transaction = true) {
        $usuario = $this->pegar($id_usuario);
        $token = md5(uniqid(rand(), true));
        $usuarioDir = UPLOAD_PATH . '/usuario';
        if (!file_exists($usuarioDir)) {
            if (!is_writable($usuarioDir)) {
                throw new Exception(sprintf("Não tem permissão para criar o diretório %s.", $usuarioDir));
            }
            @mkdir($usuarioDir);
        }
        $arquivoFoto = UPLOAD_PATH . '/usuario/' . $token . '.png';
        file_put_contents($arquivoFoto, $fotoStream);

        $usuario->setFoto($token . ".png");
        $this->alterar($usuario, $transaction);
    }

	/**
	 * @throws Exception
	 * @param int $id_usuario
	 * @param bool $transaction
	 */
	public function excluir($id_usuario, $transaction = true) {
		$dal = UsuarioDALFactory::create();
		$dalPreferencia = UsuarioPreferenciaDALFactory::create();
        $dalEndereco = UsuarioEnderecoDALFactory::create();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dalPreferencia->limpar($id_usuario);
            $dalEndereco->limpar($id_usuario);
			$dal->limparGrupoPorIdUsuario($id_usuario);
			$dal->excluir($id_usuario);
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
     * @param int $id_endereco
     * @throws Exception
     */
    public function excluirEndereco($id_endereco) {
        $dalEndereco = UsuarioEnderecoDALFactory::create();
        $dalEndereco->excluir($id_endereco);
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param string $senha
     */
    public function alterarSenha($id_usuario, $senha) {
        $dal = UsuarioDALFactory::create();
        $dal->alterarSenha($id_usuario, $senha);
    }

    /**
     * @param string $email
     * @param string $senha
     * @return int
     * @throws Exception
     */
    public function logar($email, $senha) {
        $dal = UsuarioDALFactory::create();
        $usuario = $dal->pegarPorLogin($email, $senha);
        if (is_null($usuario)) {
            throw new Exception("Email ou senha inválida.");
        }
        if ($usuario->getCodSituacao() == UsuarioInfo::AGUARDANDO_VALIDACAO) {
            throw new Exception("Usuário ainda não foi verificado.");
        }
        if ($usuario->getCodSituacao() == UsuarioInfo::BLOQUEADO) {
            throw new Exception("Usuário está bloqueado.");
        }
        if ($usuario->getCodSituacao() == UsuarioInfo::INATIVO) {
            throw new Exception("Usuário está inativo.");
        }
        $this->gravarCookie( $usuario->getId(), true );
        return $usuario->getId();
    }

    /**
     * @param int $id_usuario
     * @param bool $lembrar
     * @throws Exception
     */
    public function gravarCookie( $id_usuario, $lembrar = false ) {
        if ( $lembrar )
            $expiration = time() + (86400 * 30);
        else
            $expiration = time() + (86400 * 2);

        $key = hash_hmac( 'md5', $id_usuario . $expiration, UsuarioBLL::SITE_SECRET_KEY );
        $hash = hash_hmac( 'md5', $id_usuario . $expiration, $key );

        $cookie = $id_usuario . '|' . $expiration . '|' . $hash;

        $cookiedomain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        if ( !setcookie( UsuarioBLL::SITE_COOKIE_AUTH, $cookie, $expiration, UsuarioBLL::SITE_COOKIE_PATH, $cookiedomain, false, true ) )
            throw new Exception( "Não foi possível gravar o Cookie." );

    }

    /**
     * Altera a data de expiração do cookie de login
     */
    public function logout() {
        $cookiedomain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie( UsuarioBLL::SITE_COOKIE_AUTH, "", time() - 1209600, UsuarioBLL::SITE_COOKIE_PATH, $cookiedomain, false, true );
    }

    /**
     * @return bool
     */
    public function estaLogado() {
        if (empty($_COOKIE[UsuarioBLL::SITE_COOKIE_AUTH]))
            return false;

        list( $id_usuario, $expiration, $hmac ) = explode( '|', $_COOKIE[UsuarioBLL::SITE_COOKIE_AUTH] );

        if ( $expiration < time() )
            return false;

        $key = hash_hmac( 'md5', $id_usuario . $expiration, UsuarioBLL::SITE_SECRET_KEY );
        $hash = hash_hmac( 'md5', $id_usuario . $expiration, $key );

        if ( $hmac != $hash ) {
            return false;
        }

        return true;
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param int $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */
    function getGravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    /**
     * @throws Exception
     * @param UsuarioInfo $usuario
     */
    protected function enviarVerificacao($usuario) {
        $mailJet = new MailJetBLL();
        $token = $usuario->getPreferencia(UsuarioBLL::TOKEN_VALIDACAO);
        if (isNullOrEmpty($token)) {
            throw new Exception("Token de verificação não foi encontrado.");
        }
        $url = "http://" . $_SERVER["HTTP_HOST"] . TEMA_PATH . "/verificar/" . $token;
        $html = "";
        $html .= "<p>Seja bem-vindo(a) ao <strong>" . APP_NAME . "</strong></p>\n";
        $html .= "<p><strong>" . $usuario->getNome() . "</strong>, agradecemos por ter se registrado no <strong>" .
            APP_NAME . "</strong>!</p>\n";
        $html .= "<p>";
        $html .= "Para acessar a sua conta precisamos confirmar o seu usuário. Para realizar a validação, ";
        $html .= "<a href=\"" . $url . "\">clique aqui</a> ou acesse o link abaixo:<br />\n";
        $html .= "<a href=\"" . $url . "\">" . $url . "</a>\n";
        $html .= "</p>\n";
        $html .= "<p>Não se esqueça de completar os seus dados quando entrar.</p>\n";
        $html .= "<p>Atenciosamente,<br />" . NOME_REMETENTE . "</p>\n";
        $mailJet->send($usuario->getEmail(), "[" . APP_NAME . "] Validação de usuário", $html);
    }

    /**
     * @param UsuarioInfo $usuario
     */
    public function resetarSenha($usuario) {

    }

    /**
     * @return int
     */
    public static function pegarIdUsuarioAtual() {
        list( $id_usuario, $expiration, $hmac ) = explode( '|', $_COOKIE[UsuarioBLL::SITE_COOKIE_AUTH] );

        if ( $expiration < time() )
            return 0;

        $key = hash_hmac( 'md5', $id_usuario . $expiration, UsuarioBLL::SITE_SECRET_KEY );
        $hash = hash_hmac( 'md5', $id_usuario . $expiration, $key );

        if ( $hmac != $hash ) {
            return 0;
        }
        return $id_usuario;
    }

    /**
     * @throws Exception
     * @return UsuarioInfo|null
     */
    public static function pegarUsuarioAtual() {
        if (!isset($GLOBALS[UsuarioBLL::USUARIO_ATUAL])) {
            $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
            if ($id_usuario > 0) {
                $regraUsuario = UsuarioFactory::create();
                $usuario = $regraUsuario->pegar($id_usuario);
                if (!is_null($usuario)) {
                    $GLOBALS[UsuarioBLL::USUARIO_ATUAL] = $usuario;
                }
            }
        }
        return $GLOBALS[UsuarioBLL::USUARIO_ATUAL];
    }

    /**
     * @throws Exception
     * @return UsuarioInfo|null
     */
    public static function getUsuarioAtual() {
        return UsuarioBLL::pegarUsuarioAtual();
    }

    /**
     * Pegar URL completa da foto do usuário
     * @param string $foto Foto do usuário
     * @param int $width Largura da Foto
     * @param int $height Altura da Foto
     * @return string
     */
    public function getThumbnailUrl($foto, $width, $height) {
        $url = (eSSL() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
        if (defined("TEMA_PATH")) {
            $url .= TEMA_PATH;
        }
        if (!isNullOrEmpty($foto)) {
            $url .= '/usuarios/' . $width . 'x' . $height . '/' . $foto;
        }
        else {
            $url .= '/img/' . $width . 'x' . $height . '/anonimo.png';
        }
        return $url;
    }

    /**
     * @return bool
     */
    public static function usaCropperJs() {
        if (defined("USA_CROPPER_JS")) {
            return (USA_CROPPER_JS == true);
        }
        return true;
    }

    /**
     * @throws Exception
     * @param bool $value
     */
    public static function setCropperJs($value) {
        if (defined("USA_CROPPER_JS")) {
            $mensagem = sprintf( "USA_CROPPER_JS já está definido como %s.", USA_CROPPER_JS);
            throw new Exception($mensagem);
        }
        define("USA_CROPPER_JS", $value);
    }
}