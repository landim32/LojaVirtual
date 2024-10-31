<?php

namespace Emagine\Pagamento;

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

$app->group('/pagamento/teste', function() use ($app) {

    $app->get('/{forma_pgto}', function (Request $request, Response $response, $args) use ($app) {
        $regraPagamento = PagamentoFactory::create();
        $regraPagamento->setDebug(true);

        $usuario = UsuarioBLL::pegarUsuarioAtual();

        $pagamento = new PagamentoInfo();
        $pagamento->setIdUsuario($usuario->getId());

        /*
        $pagamento->setCodTipo(PagamentoInfo::TIPO_BOLETO);
        $pagamento->setCep("76600000");
        $pagamento->setLogradouro("Rua Joaquim Rodrigues");
        $pagamento->setNumero("4");
        $pagamento->setBairro("Centro");
        $pagamento->setCidade("Goiás");
        $pagamento->setUf("GO");
        $pagamento->setCpf("00000000000");
        */

        switch ($args['forma_pgto']) {
            case "cartao-de-credito":
                $pagamento->setCodTipo(PagamentoInfo::CREDITO_ONLINE);
                //$pagamento->setCodTipo(PagamentoInfo::DEBITO_ONLINE);
                $pagamento->setCodBandeira(PagamentoInfo::VISA);
                $pagamento->setNumeroCartao("4203760000000006");
                $pagamento->setNomeCartao($usuario->getNome());
                $pagamento->setDataExpiracao("2018-12-01");
                $pagamento->setCVV("555");
                break;
            case "cartao-de-debito":
                //$pagamento->setCodTipo(PagamentoInfo::CREDITO);
                $pagamento->setCodTipo(PagamentoInfo::DEBITO_ONLINE);
                $pagamento->setCodBandeira(PagamentoInfo::VISA);
                $pagamento->setNumeroCartao("4203760000000006");
                //$pagamento->setNumeroCartao("4012001038166662");
                $pagamento->setNomeCartao($usuario->getNome());
                $pagamento->setDataExpiracao("2018-12-01");
                $pagamento->setCVV("555");
                break;
        }

        $pagamento->setDataVencimento(date("Y-m-d"));
        $pagamento->setDataPagamento(date("Y-m-d"));
        $pagamento->setObservacao("teste");

        $item = new PagamentoItemInfo();
        $item->setDescricao("Teste");
        $item->setValor(1);
        $item->setQuantidade(1);

        $pagamento->adicionarItem($item);

        $id_pagamento = $regraPagamento->inserir($pagamento);
        $pagamento->setId($id_pagamento);


        try {
            ob_start();
            $regraPagamento->pagar($pagamento);
            $content = ob_get_contents();
            ob_end_clean();
        }
        catch (Exception $e) {
            $content = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
        }

        $args['app'] = $app;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererMain->render($response, 'content-header.php', $args);

        $body = $response->getBody();
        $body->write("<pre>" . $content . "</pre>");

        $response = $rendererMain->render($response, 'content-footer.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });
});