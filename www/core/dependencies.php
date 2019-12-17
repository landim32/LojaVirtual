<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\BLL\LojaBLL;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Body;
use Exception;

/**
 * @var EmagineApp $app;
 */

$app->addJavascriptUrl($app->getBaseUrl() . "/js/carrinho.js");
$app->addJavascriptUrl($app->getBaseUrl() . "/js/pedido.js");

/**
 * @param EmagineApp $app
 * @param Request $request
 * @param Response $response
 * @param array<string,string> $args
 * @return Response
 */
function exibirLogin(EmagineApp $app, Request $request, Response $response, $args) {
    $regraLoja = new LojaBLL();
    $regraUsuario = new UsuarioBLL();
    $loja = $regraLoja->pegarPorSlug($args['slug']);

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['urlResetar'] = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/resetar-senha";
    $args['urlCadastro'] = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/cadastro";

    /** @var PhpRenderer $rendererUsuario */
    $rendererUsuario = $app->getContainer()->get('loginView');
    /** @var PhpRenderer $rendererMain */
    $rendererMain = $app->getContainer()->get('view');

    $response = $rendererMain->render($response, 'header.php', $args);
    /** @var Body $body */
    $body = $response->getBody();
    $str = "<div class='container'>";
    $str .= "<ol class=\"breadcrumb\">";
    $url = $app->getBaseUrl() . "/site/" . $loja->getSlug();
    $str .= "<li><a href=\"" . $url . "\"><i class='fa fa-home'></i> Home</a></li>";
    $str .= "<li class=\"active\"><i class='fa fa-user-circle'></i> Login</li>";
    if ($regraUsuario->estaLogado()) {
        $str .= "<li><a href=\"" . $url . "/carrinho" . "\"><i class='fa fa-shopping-cart'></i> Finalizar Pedido</a></li>";
    }
    else {
        $str .= "<li><i class='fa fa-shopping-cart'></i> Finalizar Pedido</li>";
    }
    $str .= "</ol>";
    $str .= "</div>";
    $body->write($str);
    $response = $rendererUsuario->render($response, 'login-completo.php', $args);
    $response = $rendererMain->render($response, 'footer.php', $args);
    return $response;
}

/**
 * @param EmagineApp $app
 * @param Request $request
 * @param Response $response
 * @param array<string,string> $args
 * @param UsuarioInfo|null $usuario
 * @return Response
 */
function exibirUsuarioNovo(EmagineApp $app, Request $request, Response $response, $args, $usuario = null) {
    $regraLoja = new LojaBLL();

    $loja = $regraLoja->pegarPorSlug($args['slug']);
    if (is_null($usuario)) {
        $usuario = new UsuarioInfo();
    }
    $url = $app->getBaseUrl() . "/site/" . $loja->getSlug();

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['usuario'] = $usuario;
    $args['urlVoltar'] = $url;
    $args['textoVoltar'] = "<i class='fa fa-chevron-left'></i> Continuar Comprando";
    $args['textoGravar'] = "Finalizar Pedido <i class='fa fa-chevron-right'></i>";
    $args['fonteGrande'] = true;

    /** var PhpRenderer $rendererLoja */
    $rendererLoja = $app->getContainer()->get('view');
    $response = $rendererLoja->render($response, 'header.php', $args);
    $response = $rendererLoja->render($response, 'usuario-novo.php', $args);
    $response = $rendererLoja->render($response, 'footer.php', $args);
    return $response;
}

/**
 * @param EmagineApp $app
 * @param Request $request
 * @param Response $response
 * @param array<string,string> $args
 * @param UsuarioInfo|null $usuario
 * @return Response
 */
function exibirUsuarioAlterar(EmagineApp $app, Request $request, Response $response, $args, $usuario = null) {
    $regraLoja = new LojaBLL();
    $regraUsuario = new UsuarioBLL();

    $loja = $regraLoja->pegarPorSlug($args['slug']);
    if (is_null($usuario)) {
        $usuario = new UsuarioInfo();
    }
    $url = $app->getBaseUrl() . "/site/" . $loja->getSlug();

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['usuario'] = $usuario;
    $args['urlVoltar'] = $url;
    $args['textoVoltar'] = "<i class='fa fa-chevron-left'></i> Continuar Comprando";
    $args['textoGravar'] = "Gravar Dados <i class='fa fa-chevron-right'></i>";
    $args['fonteGrande'] = false;

    /** var PhpRenderer $rendererLoja */
    $rendererLoja = $app->getContainer()->get('view');
    $response = $rendererLoja->render($response, 'header.php', $args);
    $response = $rendererLoja->render($response, 'usuario-alterar.php', $args);
    $response = $rendererLoja->render($response, 'footer.php', $args);
    return $response;
}

/**
 * @param EmagineApp $app
 * @param Request $request
 * @param Response $response
 * @param array<string,string> $args
 * @return Response
 */
function gravarLogin(EmagineApp $app, Request $request, Response $response, $args) {
    $post = $request->getParsedBody();

    $regraLoja = new LojaBLL();
    $loja = $regraLoja->pegarPorSlug($args['slug']);

    $regraUsuario = new UsuarioBLL();
    $usuario = UsuarioBLL::pegarUsuarioAtual();
    $usuario = $regraUsuario->pegarDoPost($post, $usuario);

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['usuario'] = $usuario;

    if ($post['senha'] != $post['confirma']) {
        $args['error'] = "A senha não está batendo.";
        return exibirUsuarioAlterar($app, $request, $response, $args, $usuario);
    }
    $usuario->setSenha($post['senha']);

    try {
        $regraUsuario = new UsuarioBLL();
        if ($usuario->getId() > 0) {
            $regraUsuario->alterar($usuario);
            $id_usuario = $usuario->getId();
            $usuario = $regraUsuario->pegar($id_usuario);
            $args["usuario"] = $usuario;
        }
        else {
            $usuario->setCodSituacao(UsuarioInfo::ATIVO);
            $id_usuario = $regraUsuario->inserir($usuario);
            $usuario = $regraUsuario->pegar($id_usuario);
            $args["usuario"] = $usuario;
        }

        $regraUsuario->gravarCookie($usuario->getId());
        $urlPedido = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/carrinho";
        return $response->withStatus(302)->withHeader('Location', $urlPedido);
    }
    catch (Exception $e) {
        $args['error'] = $e->getMessage();
        return exibirUsuarioAlterar($app, $request, $response, $args, $usuario);
    }
}