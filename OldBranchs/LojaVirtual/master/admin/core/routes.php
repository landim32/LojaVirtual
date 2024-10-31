<?php

namespace Emagine\Pedido;

use Exception;
use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\Model\LojaInfo;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = EmagineApp::getApp();

$app->get('/', function (Request $request, Response $response, $args) use($app) {
    $regraUsuario = new UsuarioBLL();
    if (!$regraUsuario->estaLogado()) {
        $url = $app->getBaseUrl() . "/login";
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    $usuario = UsuarioBLL::pegarUsuarioAtual();
    if (is_null($usuario)) {
        $url = $app->getBaseUrl() . "/login";
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
        $url = $app->getBaseUrl() . "/dashboard";
    }
    else {
        $regraLoja = new LojaBLL();
        $lojas = $regraLoja->listarPorUsuario($usuario->getId());
        if (count($lojas) > 0) {
            /** @var LojaInfo $loja */
            $loja = array_values($lojas)[0];
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/dashboard";
        }
        else {
            throw new Exception("Nenhuma loja ligada a esse usuário.");
        }
    }
    return $response->withStatus(302)->withHeader('Location', $url);
});
