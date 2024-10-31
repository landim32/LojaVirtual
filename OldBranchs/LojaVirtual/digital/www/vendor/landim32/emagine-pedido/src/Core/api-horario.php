<?php
namespace Emagine\Pedido;

use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Pedido\BLL\PedidoHorarioBLL;
use Emagine\Pedido\Model\PedidoHorarioInfo;

$app = EmagineApp::getApp();

$app->group('/api/horario', function () use ($app) {

    $app->get('/listar/{id_loja}', function (Request $request, Response $response, $args) {
        try {
            $id_loja = intval($args['id_loja']);
            $regraHorario = new PedidoHorarioBLL();
            $horarios = $regraHorario->listar($id_loja);
            return $response->withJson($horarios);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/pegar/{id_horario}', function (Request $request, Response $response, $args) {
        try {
            $id_horario = intval($args['id_horario']);
            $regraHorario = new PedidoHorarioBLL();
            $horario = $regraHorario->pegar($id_horario);
            return $response->withJson($horario);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/inserir', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $horario = PedidoHorarioInfo::fromJson($json);
            $regraHorario = new PedidoHorarioBLL();
            $id_horario = $regraHorario->inserir($horario);
            return $response->withJson($id_horario);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/alterar', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $horario = PedidoHorarioInfo::fromJson($json);
            $regraHorario = new PedidoHorarioBLL();
            $regraHorario->alterar($horario);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/excluir/{id_horario}', function (Request $request, Response $response, $args) {
        try {
            $id_horario = intval($args['id_horario']);
            $regraHorario = new PedidoHorarioBLL();
            $regraHorario->excluir($id_horario);
            return $response->withStatus(200);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

});

