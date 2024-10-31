<?php

namespace Emagine\Produto;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Exception;

$app = EmagineApp::getApp();

if (ProdutoBLL::usaProdutoAPI()) {

    $app->group('/api/loja', function () use ($app) {

        $app->get('/listar[/{id_usuario}]', function (Request $request, Response $response, $args) use ($app) {
            try {
                $regraLoja = new LojaBLL();
                if (array_key_exists("id_usuario", $args)) {
                    $id_usuario = intval($args["id_usuario"]);
                    if ($id_usuario > 0) {
                        $lojas = $regraLoja->listarPorUsuario($id_usuario);
                    } else {
                        $lojas = $regraLoja->listar();
                    }
                } else {
                    $lojas = $regraLoja->listar();
                }
                return $response->withJson($lojas);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/pegar/{id_loja}', function (Request $request, Response $response, $args) use ($app) {
            try {
                $regraLoja = new LojaBLL();
                $loja = $regraLoja->pegar($args['id_loja']);
                return $response->withJson($loja);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/buscar', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $regraLoja = new LojaBLL();
                $lojas = $regraLoja->buscarPorPosicao($json->latitude, $json->longitude, $json->raio, $json->id_seguimento);
                return $response->withJson($lojas);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

    });
}