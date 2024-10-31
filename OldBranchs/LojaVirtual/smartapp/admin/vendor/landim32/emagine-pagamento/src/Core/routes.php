<?php

namespace Emagine\Pagamento;

use Emagine\Pagamento\Controls\PagamentoForm;
use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Pagamento\Factory\PagamentoFactory;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pagamento\Model\PagamentoItemInfo;

$app = EmagineApp::getApp();

$app->get('/pagamentos', function (Request $request, Response $response, $args) use ($app) {
    $regraPagamento = PagamentoFactory::create();
    $args['app'] = $app;
    $args['pagamentos'] = $regraPagamento->listar();
    $args['urlFormato'] = null;

    /** @var PhpRenderer $rendererMain */
    $rendererMain = $this->get('view');
    /** @var PhpRenderer $rendererPgto */
    $rendererPgto = $this->get('pagamento');

    $response = $rendererMain->render($response, 'header.php', $args);
    $response = $rendererPgto->render($response, 'pagamento-lista.php', $args);
    $response = $rendererMain->render($response, 'footer.php', $args);
    return $response;
});

$app->group('/pagamento', function() use ($app) {

    $app->get('/pagar', function (Request $request, Response $response, $args) use ($app) {
        $queryParam = $request->getQueryParams();

        $regraPagamento = PagamentoFactory::create();
        $pagamento = new PagamentoInfo();
        $pagamento->setCodTipo(PagamentoInfo::DEPOSITO_BANCARIO);

        $pagamentoForm = new PagamentoForm();
        $pagamentoForm->setPagamento($pagamento);
        $pagamentoForm->setAceitaBoleto(false);
        $pagamentoForm->setAceitaCreditoOnline(false);
        $pagamentoForm->setAceitaDebitoOnline(false);
        $pagamentoForm->setAceitaDinheiro(false);
        $pagamentoForm->setAceitaCartaoOffline(false);
        $conteudo = $pagamentoForm->renderForm();

        $args['app'] = $app;
        $args['pagamento'] = $pagamento;
        $args['bandeiras'] = $regraPagamento->listarBandeira();
        $args['conteudo'] = $conteudo;
        if (array_key_exists('erro', $queryParam)) {
            $args['erro'] = $queryParam['erro'];
        }


        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererPgto */
        $rendererPgto = $this->get('pagamento');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererMain->render($response, 'content-header.php', $args);
        $response = $rendererPgto->render($response, 'pagamento.php', $args);
        $response = $rendererMain->render($response, 'content-footer.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->post('/pagar', function (Request $request, Response $response, $args) use ($app) {
        $regraPagamento = PagamentoFactory::create();
        $bandeiras = $regraPagamento->listarBandeira();

        $postData = $request->getParsedBody();

        $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        //$usuario = UsuarioBLL::pegarUsuarioAtual();

        $pagamento = new PagamentoInfo();
        $pagamento->setCodTipo(PagamentoInfo::DEPOSITO_BANCARIO);
        $pagamento->setIdUsuario($id_usuario);
        $pagamento->setDataVencimento(date("Y-m-d", strtotime('+3 days')));
        $pagamento = $regraPagamento->pegarDoPost($postData, $pagamento);

        $item = new PagamentoItemInfo();
        $item->setDescricao("Teste");
        $item->setValor(1);
        $item->setQuantidade(1);

        $pagamento->adicionarItem($item);

        try {

            $id_pagamento = $regraPagamento->inserir($pagamento);
            $pagamento->setId($id_pagamento);

            ob_start();
            $regraPagamento->pagar($pagamento);
            $content = ob_get_contents();
            ob_end_clean();
            $body = $response->getBody();
            $body->write("<pre>" . $content . "</pre>");
            return $response->withStatus(200);

            //$url = $app->getBaseUrl() . "/pagamento/sucesso/" . $id_pagamento;
            //return $response->withStatus(302)->withHeader('Location', $url);
        }
        catch (Exception $e) {

            $pagamentoForm = new PagamentoForm();
            $pagamentoForm->setPagamento($pagamento);
            $pagamentoForm->setAceitaBoleto(false);
            $pagamentoForm->setAceitaCreditoOnline(false);
            $pagamentoForm->setAceitaDebitoOnline(false);
            $pagamentoForm->setAceitaDinheiro(false);
            $pagamentoForm->setAceitaCartaoOffline(false);
            $conteudo = $pagamentoForm->renderForm();

            $args['app'] = $app;
            $args['pagamento'] = $pagamento;
            $args['bandeiras'] = $bandeiras;
            $args['conteudo'] = $conteudo;
            $args['erro'] = $e->getMessage();

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererPgto */
            $rendererPgto = $this->get('pagamento');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererMain->render($response, 'content-header.php', $args);
            $response = $rendererPgto->render($response, 'pagamento.php', $args);
            $response = $rendererMain->render($response, 'content-footer.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);

            return $response;
        }
    });

    $app->get('/sucesso/{id_pagamento}', function (Request $request, Response $response, $args) use ($app) {

        $regraPagamento = PagamentoFactory::create();
        $id_pagamento = intval($args['id_pagamento']);
        $pagamento = $regraPagamento->pegar($id_pagamento);
        $args['app'] = $app;
        $args['pagamento'] = $pagamento;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererPgto */
        $rendererPgto = $this->get('pagamento');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererMain->render($response, 'content-header.php', $args);
        $response = $rendererPgto->render($response, 'sucesso.php', $args);
        $response = $rendererMain->render($response, 'content-footer.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/email/{id_pagamento}', function (Request $request, Response $response, $args) use ($app) {

        $regraPagamento = PagamentoFactory::create();
        $id_pagamento = intval($args['id_pagamento']);
        $pagamento = $regraPagamento->pegar($id_pagamento);

        $args['app'] = $app;
        $args['assunto'] = $regraPagamento->gerarAssunto($pagamento);
        $args['pagamento'] = $pagamento;

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('pagamento');
        $response = $renderer->render($response, 'pagamento-email.php', $args);
        return $response;
    });

    $app->get('/excluir/{id_pagamento}', function (Request $request, Response $response, $args) use ($app) {
        try {
            $regraPagamento = PagamentoFactory::create();
            $regraPagamento->excluir($args["id_pagamento"]);

            $url = $app->getBaseUrl() . "/pagamento/listar";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/{id_pagamento}', function (Request $request, Response $response, $args) use ($app) {

        $regraPagamento = PagamentoFactory::create();
        $id_pagamento = intval($args['id_pagamento']);
        $pagamento = $regraPagamento->pegar($id_pagamento);

        if (is_null($pagamento)) {
            throw new Exception(sprintf("Nenhum pagamento encontrado com o ID %s.", $id_pagamento));
        }

        $args['app'] = $app;
        $args['pagamento'] = $pagamento;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererPgto */
        $rendererPgto = $this->get('pagamento');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererPgto->render($response, 'pagamento-item.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });
});