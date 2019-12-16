<?php

namespace Emagine\Login;

use Emagine\Login\Factory\UsuarioFactory;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Login\Controls\UsuarioRouter;

$app = EmagineApp::getApp();

if (UsuarioBLL::usaLoginRoute()) {

    $app->get('/login', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $args['urlResetar'] = $app->getBaseUrl() . "/resetar-senha";
        $args['urlCadastro'] = $app->getBaseUrl() . "/cadastro";

        /** @var PhpRenderer $rendererUsuario */
        $rendererUsuario = $this->get('loginView');

        if (UsuarioBLL::getLoginSimples() == true) {
            $response = $rendererUsuario->render($response, 'login.php', $args);
        } else {
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererUsuario->render($response, 'login-completo.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
        }
        return $response;
    });
    $app->post('/login', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $post = $request->getParsedBody();

        try {
            $regraUsuario = UsuarioFactory::create();
            $regraUsuario->logar($post["email"], $post["senha"]);
            $url = $app->getBaseUrl() . "/";
            return $response->withStatus(302)->withHeader('Location', $url);
        } catch (Exception $e) {
            $args['error'] = $e->getMessage();
            /** @var PhpRenderer $renderer */
            $renderer = $this->get('loginView');
            $response = $renderer->render($response, 'login.php', $args);
            return $response;
        }
    });

    $app->get('/validacao', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        //$args['usuario'] = new UsuarioInfo();
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('loginView');
        $response = $renderer->render($response, 'validacao.php', $args);
        return $response;
    });

    $app->get('/cadastro', function (Request $request, Response $response, $args) use ($app) {
        $route = new UsuarioRouter($app, $request, $response);
        $route->setCadastroSimples(UsuarioBLL::getCadastroSimples());
        $route->setValidaEmail(UsuarioBLL::getValidaEmail());
        //$route->setUrlPosCadastro("");
        $usuario = UsuarioBLL::getUsuarioAtual();
        return $route->exibirCadastro($usuario, $args);
    });

    $app->post('/cadastro', function (Request $request, Response $response, $args) use ($app) {
        $route = new UsuarioRouter($app, $request, $response);
        $route->setCadastroSimples(UsuarioBLL::getCadastroSimples());
        $route->setValidaEmail(UsuarioBLL::getValidaEmail());
        return $route->gravar($args);
    });

    $app->get('/verificar/{token}', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;

        $regraUsuario = UsuarioFactory::create();
        $usuario = $regraUsuario->pegarPorToken($args['token']);
        if (is_null($usuario)) {
            throw new Exception("Nenhum usuário encontrado com esse token.");
        }
        $usuario->setCodSituacao(UsuarioInfo::ATIVO);
        $usuario->removerPreferencia(UsuarioBLL::TOKEN_VALIDACAO);
        $regraUsuario->alterar($usuario);

        $regraUsuario->gravarCookie($usuario->getId(), true);

        $url = $app->getBaseUrl() . "/?bemvindo=1";
        return $response->withStatus(302)->withHeader('Location', $url);
    });


    $app->get('/resetar-senha', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('loginView');
        $response = $renderer->render($response, 'resetar-senha.php', $args);
        return $response;
    });

    $app->get('/logoff', function (Request $request, Response $response, $args) use ($app) {
        $regraUsuario = UsuarioFactory::create();
        $regraUsuario->logout();
        $url = $app->getBaseUrl() . "/login";
        return $response->withStatus(302)->withHeader('Location', $url);
    });
}