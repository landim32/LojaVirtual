<?php

namespace Emagine\Login\Controls;

use Emagine\Base\EmagineApp;
use Emagine\Login\Factory\UsuarioFactory;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Exception;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;

class UsuarioRouter
{
    private $app = null;
    private $request = null;
    private $response = null;
    private $cadastroSimples = true;
    private $validaEmail = true;
    private $urlPosCadastro = "";
    private $urlVoltar = "";
    private $textoVoltar = "<i class='fa fa-chevron-left'></i> Voltar";
    private $textoGravar = "<i class='fa fa-check'></i> Gravar";

    /**
     * @param EmagineApp $app
     * @param Request $request
     * @param Response $response
     */
    public function __construct(EmagineApp $app, Request $request, Response $response)
    {
        $this->app = $app;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return EmagineApp
     */
    public function getApp() {
        return $this->app;
    }

    /**
     * @return Request
     */
    public function getRequest() {
        return $this->request;
    }

    public function getResponse() {
        return $this->response;
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
    public function getCadastroSimples() {
        return $this->cadastroSimples;
    }

    /**
     * @param bool $value
     */
    public function setCadastroSimples($value) {
        $this->cadastroSimples = $value;
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
     * @param Response $response
     * @param array<string,string> $args
     * @return Response
     */
    protected function renderCadastroIni(Response $response, $args) {
        return $response;
    }

    /**
     * @param Response $response
     * @param array<string,string> $args
     * @return Response
     */
    protected function renderCadastroFim(Response $response, $args) {
        return $response;
    }

    /**
     * @param UsuarioInfo $usuario
     * @param array<string,string> $args
     * @return Response
     */
    public function exibirCadastro($usuario, $args) {
        if (is_null($usuario)) {
            $usuario = new UsuarioInfo();
        }
        $app = $this->getApp();
        $response = $this->getResponse();

        $args['app'] = $app;
        $args['usuario'] = $usuario;
        $args['urlVoltar'] = $this->getUrlVoltar();
        $args['textoVoltar'] = $this->getTextoVoltar();
        $args['textoGravar'] = $this->getTextoGravar();

        /** @var PhpRenderer $rendererUsuario */
        $rendererUsuario = $app->getContainer()->get('loginView');
        /** @var PhpRenderer $rendererMain */
        $rendererMain = $app->getContainer()->get('view');

        if ($this->getCadastroSimples() == true) {
            $response = $rendererUsuario->render($response, 'cadastro.php', $args);
        }
        else {
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $this->renderCadastroIni($response, $args);
            $response = $rendererUsuario->render($response, 'cadastro-completo.php', $args);
            $response = $this->renderCadastroFim($response, $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
        }
        return $response;
    }

    /**
     * @param string[] $args
     * @throws Exception
     * @return Response
     */
    public function gravar($args) {
        $app = $this->getApp();
        $response = $this->getResponse();
        $request = $this->getRequest();

        $args['app'] = $app;
        $post = $request->getParsedBody();
        $regraUsuario = UsuarioFactory::create();
        $usuario = $regraUsuario->pegarDoPost($post);
        if ($this->getValidaEmail() == true) {
            $usuario->setCodSituacao(UsuarioInfo::AGUARDANDO_VALIDACAO);
        }
        else {
            $usuario->setCodSituacao(UsuarioInfo::ATIVO);
        }
        $args['usuario'] = $usuario;

        if ($post['senha'] != $post['confirma']) {
            $args['error'] = "A senha não está batendo.";
            return $this->exibirCadastro($usuario, $args);
        }
        $usuario->setSenha($post['senha']);

        try {
            $regraUsuario = UsuarioFactory::create();
            $id_usuario = $regraUsuario->inserir($usuario);
            $usuario = $regraUsuario->pegar($id_usuario);
            $args["usuario"] = $usuario;

            if ($this->getValidaEmail() == true) {
                /** @var PhpRenderer $rendererUsuario */
                $rendererUsuario = $app->getContainer()->get('loginView');
                $response = $rendererUsuario->render($response, 'validacao.php', $args);
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
        }
        catch (Exception $e) {
            $args['error'] = $e->getMessage();
            return $this->exibirCadastro($usuario, $args);
        }
    }
}