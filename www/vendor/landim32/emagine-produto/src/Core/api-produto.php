<?php

namespace Emagine\Produto;

use Exception;
use Emagine\Base\EmagineApp;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Produto\Model\ProdutoFiltroInfo;
use Emagine\Produto\Model\ProdutoInfo;

$app = EmagineApp::getApp();

if (ProdutoBLL::usaProdutoAPI()) {

    $app->group('/api/produto', function () use ($app) {

        $app->put('/buscar', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $filtro = ProdutoFiltroInfo::fromJson($json);
                $regraProduto = new ProdutoBLL();
                $produtos = $regraProduto->buscar($filtro);
                return $response->withJson($produtos);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/listar/{id_loja}[/{id_categoria}]', function (Request $request, Response $response, $args) use ($app) {
            try {
                $id_loja = intval($args['id_loja']);
                $regraProduto = new ProdutoBLL();
                $filtro = (new ProdutoFiltroInfo())->setIdLoja($id_loja);
                if (array_key_exists('id_categoria', $args)) {
                    $filtro->setIdCategoria(intval($args['id_categoria']));
                }
                $produtos = $regraProduto->buscar($filtro);
                return $response->withJson($produtos);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/listar-destaque/{id_loja}', function (Request $request, Response $response, $args) use ($app) {
            try {
                $id_loja = intval($args['id_loja']);
                $regraProduto = new ProdutoBLL();
                $produtos = $regraProduto->listarDestaque($id_loja);
                return $response->withJson($produtos);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/buscar/{id_loja}', function (Request $request, Response $response, $args) use ($app) {
            try {
                $queryParam = $request->getQueryParams();
                $id_loja = intval($args['id_loja']);
                $palavraChave = $queryParam['p'];
                $regraProduto = new ProdutoBLL();
                $produtos = $regraProduto->buscarPorPalavra($id_loja, $palavraChave);
                return $response->withJson($produtos);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/buscar-original', function (Request $request, Response $response, $args) use ($app) {
            try {
                $queryParam = $request->getQueryParams();
                $palavraChave = $queryParam['p'];
                $regraProduto = new ProdutoBLL();
                $produtos = $regraProduto->buscarOriginal($palavraChave);
                return $response->withJson($produtos);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/listar-por-filtro', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $filtro = ProdutoFiltroInfo::fromJson($json);
                $regraProduto = new ProdutoBLL();
                $produtos = $regraProduto->listarPorFiltro($filtro);
                return $response->withJson($produtos);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/pegar/{id_produto}', function (Request $request, Response $response, $args) use ($app) {
            try {
                $id_produto = intval($args['id_produto']);
                $regraProduto = new ProdutoBLL();
                $produto = $regraProduto->pegar($id_produto);
                return $response->withJson($produto);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/{id_loja}/pegar-por-codigo/{codigo}', function (Request $request, Response $response, $args) use ($app) {
            try {
                $id_loja = intval($args['id_loja']);
                $codigo = $args['codigo'];
                $regraProduto = new ProdutoBLL();
                $produto = $regraProduto->pegarPorCodigo($id_loja, $codigo);
                return $response->withJson($produto);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/inserir', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $produto = ProdutoInfo::fromJson($json);
                $regraProduto = new ProdutoBLL();
                $id_produto = $regraProduto->inserir($produto);
                return $response->withJson($id_produto);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/alterar', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $produto = ProdutoInfo::fromJson($json);
                $regraProduto = new ProdutoBLL();
                $regraProduto->alterar($produto);
                return $response->withJson($produto->getId());
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });
    });
}