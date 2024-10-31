<?php

namespace Emagine\Produto;

use Emagine\Produto\BLL\ProdutoBLL;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\SeguimentoBLL;
use Exception;

if (ProdutoBLL::usaProdutoAPI()) {

    $app = EmagineApp::getApp();

    $app->group('/api/seguimento', function () use ($app) {

        $app->get('/listar', function (Request $request, Response $response, $args) use ($app) {
            try {
                $regraSeguimento = new SeguimentoBLL();
                $seguimentos = $regraSeguimento->listar();
                return $response->withJson($seguimentos);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/listar-com-loja', function (Request $request, Response $response, $args) use ($app) {
            try {
                $regraSeguimento = new SeguimentoBLL();
                $seguimentos = $regraSeguimento->listarComLoja();
                return $response->withJson($seguimentos);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/buscar', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $regraSeguimento = new SeguimentoBLL();
                $seguimentos = $regraSeguimento->buscarPorPosicao($json->latitude, $json->longitude, $json->raio);
                return $response->withJson($seguimentos);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

    });
}