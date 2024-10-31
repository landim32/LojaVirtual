<?php

namespace Emagine\Pedido;

use Emagine\Pedido\Model\PedidoSituacaoInfo;
use Exception;
use Emagine\Base\EmagineApp;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Pedido\Model\PedidoEnvioInfo;

$app = EmagineApp::getApp();

$app->group('/api/pedido', function() use ($app) {

    $app->get('/listar/{id_usuario}[/{cod_situacao}]', function (Request $request, Response $response, $args) {
        try {
            $id_usuario = intval($args["id_usuario"]);
            $cod_situacao = intval($args["cod_situacao"]);
            $id_usuario = ($id_usuario > 0) ? $id_usuario : null;
            $cod_situacao = ($cod_situacao > 0) ? $cod_situacao : null;

            $regraPedido = new PedidoBLL();
            $pedidos = $regraPedido->listarPorUsuario($id_usuario, $cod_situacao);
            return $response->withJson($pedidos);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/listar-entregando/{id_loja}', function (Request $request, Response $response, $args) {
        try {
            $id_loja = intval($args["id_loja"]);
            $regraPedido = new PedidoBLL();
            $pedidos = $regraPedido->listarEntregando($id_loja);
            return $response->withJson($pedidos);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/listar-retirada-mapeada/{id_loja}', function (Request $request, Response $response, $args) {
        try {
            $id_loja = intval($args["id_loja"]);
            $regraPedido = new PedidoBLL();
            $pedidos = $regraPedido->listarRetiradaMapeada($id_loja);
            return $response->withJson($pedidos);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/listar-avaliacao/{id_loja}[/{id_usuario}]', function (Request $request, Response $response, $args) {
        try {
            $id_loja = intval($args["id_loja"]);
            $id_usuario = intval($args["id_usuario"]);
            $regraPedido = new PedidoBLL();
            $pedidos = $regraPedido->listarAvaliacao($id_loja, $id_usuario);
            return $response->withJson($pedidos);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/pegar/{id_pedido}', function (Request $request, Response $response, $args) {
        try {
            $id_pedido = intval($args["id_pedido"]);

            $regraPedido = new PedidoBLL();
            $pedido = $regraPedido->pegar($id_pedido);
            return $response->withJson($pedido);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/inserir', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $pedido = PedidoInfo::fromJson($json);

            $regraPedido = new PedidoBLL();
            $id_pedido = $regraPedido->inserir($pedido);

            $body = $response->getBody();
            $body->write($id_pedido);

            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/alterar', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $pedido = PedidoInfo::fromJson($json);

            $regraPedido = new PedidoBLL();
            $regraPedido->alterar($pedido);

            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->group('/situacao', function() use ($app) {

        $app->put('', function (Request $request, Response $response, $args) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $situacao = PedidoSituacaoInfo::fromJson($json);
                $regraPedido = new PedidoBLL();
                $regraPedido->alterarSituacao($situacao);
                return $response->withStatus(200);
            }
            catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->group('/{id_pedido}', function() use ($app) {

            /*
            $app->get('/listar', function (Request $request, Response $response, $args) use ($app) {
                try {
                    $id_pedido = intval($args['id_pedido']);
                    $regraPedido = new PedidoBLL();
                    $mensagens = $regraPedido->listarMensagem($id_pedido);
                    return $response->withJson($mensagens);
                }
                catch (Exception $e) {
                    $body = $response->getBody();
                    $body->write($e->getMessage());
                    return $response->withStatus(500);
                }
            });
            */

            $app->get('/{cod_situacao}', function (Request $request, Response $response, $args) use ($app) {
                try {
                    $regraPedido = new PedidoBLL();
                    $id_pedido = intval($args["id_pedido"]);
                    $pedido = $regraPedido->pegar($id_pedido);
                    $regraPedido->alterarSituacao((new PedidoSituacaoInfo())
                        ->setIdPedido($pedido->getId())
                        ->setIdUsuario($pedido->getIdUsuario())
                        ->setCodSituacao(intval($args["cod_situacao"]))
                    );
                    return $response->withStatus(200);
                }
                catch (Exception $e) {
                    $body = $response->getBody();
                    $body->write($e->getMessage());
                    return $response->withStatus(500);
                }
            });
        });
    });

    $app->put('/atualizar', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $envio = PedidoEnvioInfo::fromJson($json);
            $regraPedido = new PedidoBLL();
            $retorno = $regraPedido->atualizar($envio);
            return $response->withJson($retorno);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });
});