<?php

namespace Emagine\Login;

use Emagine\Login\Factory\PermissaoFactory;
use Emagine\Login\Factory\UsuarioFactory;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Login\BLL\UsuarioBLL;

$app = EmagineApp::getApp();

if (UsuarioBLL::usaLoginRoute()) {

    $app->group('/permissao', function () use ($app) {

        $app->get('/listar', function (Request $request, Response $response, $args) use ($app) {
            $regraPermissao = PermissaoFactory::create();
            $permissoes = $regraPermissao->listar();

            $args['app'] = $app;
            $args['permissoes'] = $permissoes;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererLogin */
            $rendererLogin = $this->get('loginView');
            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLogin->render($response, 'permissoes.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

    })->add(function (Request $request, Response $response, callable $next) {
        $regraUsuario = UsuarioFactory::create();
        if (!$regraUsuario->estaLogado()) {
            throw new Exception("Acesso negado!");
        }
        $response = $next($request, $response);
        return $response;
    });
}