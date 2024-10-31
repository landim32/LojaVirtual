<?php

namespace Emagine\Pedido;

use Emagine\Base\EmagineApp;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Pedido\BLL\ClienteBLL;

$app = EmagineApp::getApp();

if (ProdutoBLL::usaProdutoRoute()) {

    $app->get('/{slug_loja}/clientes', function (Request $request, Response $response, $args) use ($app) {

        $regraLoja = new LojaBLL();
        $regraCliente = new ClienteBLL();

        $loja = $regraLoja->pegarPorSlug($args["slug_loja"]);
        $regraLoja->validarPermissao($loja);

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/clientes");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $clientes = $regraCliente->listarPorLoja($loja->getId());

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usaFoto'] = UsuarioBLL::usaFoto();
        $args['clientes'] = $clientes;
        $args['usuarioPerfil'] = $usuarioPerfil;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererPedido */
        $rendererPedido = $this->get('pedidoView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererPedido->render($response, 'clientes.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->group('/{slug_loja}/cliente', function () use ($app) {
        $app->get('/mapa', function (Request $request, Response $response, $args) use ($app) {
            $args['app'] = $app;

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['slug']);
            $regraLoja->validarPermissao($loja);

            $script = sprintf("\$.loja.id_loja = %s;\n", $loja->getId());
            $script .= sprintf("\$.pedido.js_path = '%s';\n", $app->getModuleUrl(dirname(__DIR__)));

            $app->addJavascriptConteudo($script);

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererPedido */
            $rendererPedido = $this->get('pedidoView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererPedido->render($response, 'mapa.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });
    });
}