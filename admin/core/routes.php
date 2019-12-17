<?php

namespace Emagine\Pedido;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\Model\LojaInfo;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = EmagineApp::getApp();

$app->get('/', function (Request $request, Response $response, $args) use($app) {
    $regraUsuario = new UsuarioBLL();
    if (!$regraUsuario->estaLogado()) {
        $url = $app->getBaseUrl() . "/login";
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    $regraLoja = new LojaBLL();
    $lojas = $regraLoja->listar();
    /** @var LojaInfo $loja */
    $loja = array_values($lojas)[0];
    $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedido/mapa";
    return $response->withStatus(302)->withHeader('Location', $url);
    /*
    $args["app"] = $app;
    //@var PhpRenderer $renderer
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header.php', $args);
    $response = $renderer->render($response, 'home.php', $args);
    $response = $renderer->render($response, 'footer.php', $args);
    return $response;
    */
    ///pedido/engenho-madalena/mapa
});
