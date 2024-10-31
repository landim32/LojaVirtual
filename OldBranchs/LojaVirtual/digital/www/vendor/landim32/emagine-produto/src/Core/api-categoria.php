<?php

namespace Emagine\Produto;

use Emagine\Produto\BLL\UnidadeBLL;
use Emagine\Produto\Model\CategoriaInfo;
use Emagine\Produto\Model\ProdutoFiltroInfo;
use Emagine\Produto\Model\ProdutoInfo;
use Emagine\Produto\Model\UnidadeInfo;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\CategoriaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Exception;

$app = EmagineApp::getApp();

if (ProdutoBLL::usaProdutoAPI()) {

    $app->group('/api/categoria', function () use ($app) {
        $app->get('/listar/{id_loja}', function (Request $request, Response $response, $args) use ($app) {
            try {
                $id_loja = intval($args['id_loja']);
                $regraCategoria = new CategoriaBLL();
                $categorias = $regraCategoria->listar($id_loja);
                return $response->withJson($categorias);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/listar-filho/{id_loja}[/{id_categoria}]', function (Request $request, Response $response, $args) use ($app) {
            try {
                $id_loja = intval($args['id_loja']);
                $regraCategoria = new CategoriaBLL();
                if (array_key_exists('id_categoria', $args)) {
                    $id_categoria = intval($args['id_categoria']);
                    $categorias = $regraCategoria->listarFilho($id_loja, $id_categoria);
                } else {
                    $categorias = $regraCategoria->listarPai($id_loja);
                }
                return $response->withJson($categorias);
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

                $regraCategoria = new CategoriaBLL();
                $categoria = $regraCategoria->pegarPorNome($id_loja, $palavraChave);
                return $response->withJson($categoria);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/inserir', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $categoria = CategoriaInfo::fromJson($json);
                $regraCategoria = new CategoriaBLL();
                $id_categoria = $regraCategoria->inserir($categoria);
                return $response->withJson($id_categoria);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/alterar', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $categoria = CategoriaInfo::fromJson($json);
                $regraCategoria = new CategoriaBLL();
                $regraCategoria->alterar($categoria);
                return $response->withJson($categoria->getId());
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });
    });
}