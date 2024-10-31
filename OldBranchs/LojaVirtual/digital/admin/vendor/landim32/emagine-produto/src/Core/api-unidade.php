<?php

namespace Emagine\Produto;

use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Produto\BLL\UnidadeBLL;
use Emagine\Produto\Model\UnidadeInfo;

$app = EmagineApp::getApp();

if (ProdutoBLL::usaProdutoAPI()) {

    $app->group('/api/unidade', function () use ($app) {

        $app->get('/listar/{id_loja}', function (Request $request, Response $response, $args) use ($app) {
            try {
                $id_loja = intval($args['id_loja']);
                $regraUnidade = new UnidadeBLL();
                $unidades = $regraUnidade->listar($id_loja);
                return $response->withJson($unidades);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/{id_loja}/pegar-por-nome', function (Request $request, Response $response, $args) use ($app) {
            try {
                $queryParam = $request->getQueryParams();
                $palavraChave = $queryParam['p'];
                $id_loja = intval($args['id_loja']);

                $regraUnidade = new UnidadeBLL();
                $unidade = $regraUnidade->pegarPorNome($id_loja, $palavraChave);
                return $response->withJson($unidade);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/inserir', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $unidade = UnidadeInfo::fromJson($json);
                $regraUnidade = new UnidadeBLL();
                $id_unidade = $regraUnidade->inserir($unidade);
                return $response->withJson($id_unidade);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/alterar', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $unidade = UnidadeInfo::fromJson($json);
                $regraUnidade = new UnidadeBLL();
                $regraUnidade->alterar($unidade);
                return $response->withJson($unidade->getId());
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });
    });
}