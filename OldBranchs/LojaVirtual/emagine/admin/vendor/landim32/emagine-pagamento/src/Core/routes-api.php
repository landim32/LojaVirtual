<?php

namespace Emagine\Pagamento;

use Exception;
use Emagine\Base\EmagineApp;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Pagamento\Factory\PagamentoFactory;
use Emagine\Pagamento\BLL\CartaoBLL;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pagamento\Model\PagamentoRetornoInfo;

$app = EmagineApp::getApp();

$app->group('/api/pagamento', function() use ($app) {

    $app->get('/listar[/{id_usuario}[/{cod_situacao}]]', function (Request $request, Response $response, $args) {
        try {
            $id_usuario = intval($args["id_usuario"]);
            $cod_situacao = intval($args["cod_situacao"]);
            $id_usuario = ($id_usuario > 0) ? $id_usuario : null;
            $cod_situacao = ($cod_situacao > 0) ? $cod_situacao : null;

            $regraPagamento = PagamentoFactory::create();
            $pagamentos = $regraPagamento->listar($id_usuario, $cod_situacao);
            return $response->withJson($pagamentos);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/pegar/{id_pagamento}', function (Request $request, Response $response, $args) {
        try {
            $id_pagamento = intval($args["id_pagamento"]);

            $regraPagamento = PagamentoFactory::create();
            $pagamento = $regraPagamento->pegar($id_pagamento);
            return $response->withJson($pagamento);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/pagar', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());

            $regraPagamento = PagamentoFactory::create();
            $pagamento = PagamentoInfo::fromJson($json);

            $id_pagamento = $regraPagamento->inserir($pagamento);
            $pagamento->setId($id_pagamento);
            if ($pagamento->getCodTipo() != PagamentoInfo::DINHEIRO) {
                $regraPagamento->pagar($pagamento);
            }
            $pagamento = $regraPagamento->pegar($id_pagamento);

            $retorno = new PagamentoRetornoInfo();
            $retorno->setIdPagamento($pagamento->getId());
            $retorno->setCodSituacao($pagamento->getCodSituacao());
            $retorno->setMensagem($pagamento->getMensagem());
            $retorno->setBoletoUrl($pagamento->getBoletoUrl());
            $retorno->setAutenticacaoUrl($pagamento->getAutenticacaoUrl());

            return $response->withJson($retorno);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/pagar-com-token', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $pagamento = PagamentoInfo::fromJson($json);

            $regraPagamento = PagamentoFactory::create();
            $id_pagamento = $regraPagamento->inserir($pagamento);
            $pagamento->setId($id_pagamento);
            $regraPagamento->pagarComToken($pagamento);
            $pagamento = $regraPagamento->pegar($id_pagamento);

            $retorno = new PagamentoRetornoInfo();
            $retorno->setIdPagamento($pagamento->getId());
            $retorno->setCodSituacao($pagamento->getCodSituacao());
            $retorno->setMensagem($pagamento->getMensagem());

            return $response->withJson($retorno);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/retorno', function (Request $request, Response $response, $args) {
        try {
            ob_start();
            print_r($request->getQueryParams());
            $content = ob_get_contents();
            ob_end_clean();

            $body = $response->getBody();
            $body->write("<pre>" . $content . "</pre>");
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->post('/retorno', function (Request $request, Response $response, $args) {
        try {
            ob_start();
            print_r($request->getParsedBody());
            $content = ob_get_contents();
            ob_end_clean();

            $body = $response->getBody();
            $body->write("<pre>" . $content . "</pre>");
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/retorno', function (Request $request, Response $response, $args) {
        try {
            $body = $response->getBody();
            $body->write($request->getBody()->getContents());
            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/excluir/{id_pagamento}', function (Request $request, Response $response, $args) {
        try {
            $regraPagamento = PagamentoFactory::create();
            $regraPagamento->excluir($args["id_pagamento"]);

            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });
});

$app->group('/api/cartao', function() use ($app) {

    $app->get('/listar/{id_usuario}', function (Request $request, Response $response, $args) {
        try {
            $id_usuario = intval($args["id_usuario"]);

            $regraCartao = new CartaoBLL();
            $cartoes = $regraCartao->listar($id_usuario);
            return $response->withJson($cartoes);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/excluir/{id_cartao}', function (Request $request, Response $response, $args) {
        try {
            $regraCartao = new CartaoBLL();
            $regraCartao->excluir($args["id_cartao"]);

            return $response->withStatus(200);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });
});