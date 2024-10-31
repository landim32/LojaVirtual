<?php

namespace Emagine\Social;

use Emagine\Social\Factory\MensagemFactory;
use Exception;
use stdClass;
use Emagine\Base\EmagineApp;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Social\BLL\MensagemBLL;

$app = EmagineApp::getApp();

$app->get('/api/aviso/{id_usuario}', function (Request $request, Response $response, $args) {
    try {
        $id_usuario = intval($args["id_usuario"]);
        $regraUsuario = MensagemFactory::create();
        $mensagens = $regraUsuario->listarMeu($id_usuario, false);
        $regraUsuario->marcarLido($id_usuario);
        return $response->withJson($mensagens);;
    } catch (Exception $e) {
        $body = $response->getBody();
        $body->write($e->getMessage());
        return $response->withStatus(500);
    }
});

$app->group('/api/mensagem', function () use ($app) {

    $app->get('/listar/{id_usuario}', function (Request $request, Response $response, $args) {
        try {
            $id_usuario = intval($args["id_usuario"]);
            $regraUsuario = MensagemFactory::create();
            $mensagens = $regraUsuario->listarMeu($id_usuario, false);
            return $response->withJson($mensagens);;
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->post('/enviar', function (Request $request, Response $response, $args) {
        try {
            $params = $request->getParsedBody();
            $bll = MensagemFactory::create();
            $bll->enviarMensagem($params["id_usuario"], $params["mensagem"]);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/excluir/{id_mensagem}', function (Request $request, Response $response, $args) {
        try {
            $bll = MensagemFactory::create();
            $bll->excluir($args["id_mensagem"]);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

})->add(function (Request $request, Response $response, callable $next) {
    try {
        $regraUsuario = new UsuarioBLL();
        if (!$regraUsuario->estaLogado()) {
            throw new Exception("Acesso negado!");
        }
        $response = $next($request, $response);
        return $response;
    }
    catch (Exception $e) {
        $body = $response->getBody();
        $body->write($e->getMessage());
        return $response->withStatus(500);
    }
});