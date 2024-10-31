<?php
namespace Emagine\Login\Controls;

use Emagine\Login\BLL\GrupoBLL;
use Emagine\Login\Factory\GrupoFactory;
use Emagine\Login\Factory\UsuarioFactory;
use Emagine\Login\Model\GrupoInfo;
use Exception;
use Emagine\Base\Controls\ContentView;
use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class UsuarioFormView extends ContentView
{
    const TEMPLATE_CADASTRO_SIMPLES = "cadastro.php";
    const TEMPLATE_CADASTRO_COMPLETO = "cadastro-completo.php";
    const TEMPLATE_USUARIO_FORM = "usuario-form.php";
    const TEMPLATE_USUARIO_CADASTRO = "usuario-cadastro.php";

    private $grupoExibe = true;
    private $situacaoExibe = true;
    private $validaEmail = true;
    private $gravarCookie = true;
    private $urlPosCadastro = "";
    private $urlVoltar = "";
    private $textoVoltar = "<i class='fa fa-chevron-left'></i> Voltar";
    private $textoGravar = "<i class='fa fa-check'></i> Gravar";
    private $usuario = null;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->setView("loginView");
        $this->setTemplate(UsuarioFormView::TEMPLATE_CADASTRO_SIMPLES);
    }

    /**
     * @return bool
     */
    public function getGrupoExibe() {
        return $this->grupoExibe;
    }

    /**
     * @param bool $value
     */
    public function setGrupoExibe($value) {
        $this->grupoExibe = $value;
    }

    /**
     * @return bool
     */
    public function getSituacaoExibe() {
        return $this->situacaoExibe;
    }

    /**
     * @param bool $value
     */
    public function setSituacaoExibe($value) {
        $this->situacaoExibe = $value;
    }

    /**
     * @return bool
     */
    public function getValidaEmail() {
        return $this->validaEmail;
    }

    /**
     * @param bool $value
     */
    public function setValidaEmail($value) {
        $this->validaEmail = $value;
    }

    /**
     * @return bool
     */
    public function getGravarCookie() {
        return $this->gravarCookie;
    }

    /**
     * @param bool $value
     */
    public function setGravarCookie($value) {
        $this->gravarCookie = $value;
    }

    /**
     * @return string
     */
    public function getUrlPosCadastro() {
        return $this->urlPosCadastro;
    }

    /**
     * @param string $value
     */
    public function setUrlPosCadastro($value) {
        $this->urlPosCadastro = $value;
    }

    /**
     * @return string
     */
    public function getUrlVoltar() {
        return $this->urlVoltar;
    }

    /**
     * @param string $value
     */
    public function setUrlVoltar($value) {
        $this->urlVoltar = $value;
    }

    /**
     * @return string
     */
    public function getTextoVoltar() {
        return $this->textoVoltar;
    }

    /**
     * @param string $value
     */
    public function setTextoVoltar($value) {
        $this->textoVoltar = $value;
    }

    /**
     * @return string
     */
    public function getTextoGravar() {
        return $this->textoGravar;
    }

    /**
     * @param string $value
     */
    public function setTextoGravar($value) {
        $this->textoGravar = $value;
    }

    /**
     * @return UsuarioInfo
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * @param UsuarioInfo $value
     */
    public function setUsuario($value) {
        $this->usuario = $value;
    }

    /**
     * @throws Exception
     * @param string[] $args
     */
    protected function loadArgs(&$args)
    {
        $usuario = $this->getUsuario();
        if (is_null($usuario)) {
            $usuario = new UsuarioInfo();
        }

        $grupos = array();
        if ($this->getGrupoExibe() == true) {
            $logado = UsuarioBLL::pegarUsuarioAtual();
            if (!is_null($logado)) {
                if ($logado->temPermissao(UsuarioInfo::ADMIN)) {
                    $regraGrupo = GrupoFactory::create();
                    $grupos = $regraGrupo->listar();
                }
                else {
                    $grupos = $logado->listarGrupo();
                }
            }
        }

        if ($this->getSituacaoExibe() == true) {
            $regraUsuario = UsuarioFactory::create();
            $situacoes = $regraUsuario->listarSituacao();
        }
        else {
            $situacoes = array();
        }

        $args['usuario'] = $usuario;
        $args['grupos'] = $grupos;
        $args['situacoes'] = $situacoes;
        $args['urlVoltar'] = $this->getUrlVoltar();
        $args['textoVoltar'] = $this->getTextoVoltar();
        $args['textoGravar'] = $this->getTextoGravar();
    }

    /**
     * @throws Exception
     */
    public function gravar() {
        $request = $this->getRequest();
        $postData = $request->getParsedBody();

        $regraUsuario = UsuarioFactory::create();
        $usuario = $regraUsuario->pegarDoPost($postData, $this->getUsuario());
        if (!($usuario->getId() > 0)) {
            if ($this->getValidaEmail() == true) {
                if (UsuarioBLL::getRequerValidacao() == true) {
                    $usuario->setCodSituacao(UsuarioInfo::AGUARDANDO_VALIDACAO);
                }
                else {
                    $usuario->setCodSituacao(UsuarioInfo::ATIVO);
                }
            }
            else {
                $usuario->setCodSituacao(UsuarioInfo::ATIVO);
            }
        }

        if ($postData['senha'] != $postData['confirma']) {
            throw new Exception("A senha não está batendo.");
        }
        $usuario->setSenha($postData['senha']);

        $regraUsuario = UsuarioFactory::create();
        if ($usuario->getId() > 0) {
            $regraUsuario->alterar($usuario);
            $id_usuario = $usuario->getId();
        }
        else {
            $id_usuario = $regraUsuario->inserir($usuario);
        }
        $usuario = $regraUsuario->pegar($id_usuario);
        $this->setUsuario($usuario);
        if ($this->getGravarCookie() == true) {
            $regraUsuario->gravarCookie($usuario->getId());
        }

        /*
        if ($this->getValidaEmail() == true) {
            $args["usuario"] = $usuario;
            $renderer = $app->getContainer()->get($this->getView());
            $response = $renderer->render($response, 'validacao.php', $args);
            return $response;
        }
        else {
            $regraUsuario->gravarCookie($usuario->getId());
            $urlPosCadastro = $this->getUrlPosCadastro();
            if (isNullOrEmpty($urlPosCadastro)) {
                $urlPosCadastro = $app->getBaseUrl() . "/";
            }
            return $response->withStatus(302)->withHeader('Location', $urlPosCadastro);
        }
        */
    }
}