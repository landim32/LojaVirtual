<?php

namespace Emagine\Pedido;

use Emagine\Pagamento\Factory\PagamentoFactory;
use Emagine\Pedido\BLL\PagamentoPedidoBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = EmagineApp::getApp();

if (ProdutoBLL::usaProdutoRoute()) {
    $app->get('/{slug_loja}/pagamentos', function (Request $request, Response $response, $args) use ($app) {
        $regraPagamento = new PagamentoPedidoBLL();
        $regraLoja = new LojaBLL();

        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $regraLoja->validarPermissao($loja);

        $pagamentos = $regraPagamento->listarPorLoja($loja->getId());

        $urlFormato = $app->getBaseUrl() . "/%s/pagamentos";

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['urlFormato'] = $urlFormato;
        $args['pagamentos'] = $pagamentos;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererPgto */
        $rendererPgto = $this->get('pagamento');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererPgto->render($response, 'pagamento-lista.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });
}