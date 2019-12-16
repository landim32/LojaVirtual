<?php

namespace Emagine\Produto;

use Emagine\Login\Controls\UsuarioFormView;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;

$app = EmagineApp::getApp();

$app->group('/{slug_loja}/usuario', function () use ($app) {

    $app->get('/listar', function (Request $request, Response $response, $args) use ($app) {

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $usuarioLogado = UsuarioBLL::pegarUsuarioAtual();
        $regraLoja->validarPermissao($loja, $usuarioLogado);

        //$regraUsuario = new UsuarioBLL();
        $usuarios = array();
        foreach ($loja->listarUsuario() as $usuarioLoja) {
            $usuarios[] = $usuarioLoja->getUsuario();
        }
        //$usuarios = $loja->listarUsuario();

        $eAdmin = $usuarioLogado->temPermissao(UsuarioInfo::ADMIN);

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/usuario/listar");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $args['app'] = $app;
        $args['usaFoto'] = UsuarioBLL::usaFoto();
        $args['eAdmin'] = $eAdmin;
        $args['usuarioLogado'] = $usuarioLogado;
        $args['usuarioLista'] = $usuarios;
        $args['usuarioPerfil'] = $usuarioPerfil;

        /** @var PhpRenderer $rendererLogin */
        $rendererLogin = $this->get('loginView');
        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererLogin->render($response, 'usuario-lista.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/novo', function (Request $request, Response $response, $args) use ($app) {
        ///$regraUsuario = new UsuarioBLL();
        $usuario = new UsuarioInfo();
        $usuarioLogado = UsuarioBLL::pegarUsuarioAtual();
        $eAdmin = $usuarioLogado->temPermissao(UsuarioInfo::ADMIN);

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $regraLoja->validarPermissao($loja, $usuarioLogado);

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/usuario/listar");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $args['app'] = $app;
        $args['usuario'] = $usuario;
        $args['usuarioPerfil'] = $usuarioPerfil;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');

        $response = $rendererMain->render($response, 'header.php', $args);
        $usuarioForm = new UsuarioFormView($request, $response);
        $usuarioForm->setGrupoExibe($eAdmin);
        $usuarioForm->setGravarCookie(false);
        $usuarioForm->setSituacaoExibe($eAdmin);
        $usuarioForm->setTemplate(UsuarioFormView::TEMPLATE_USUARIO_CADASTRO);
        $usuarioForm->setUsuario($usuario);
        $response = $usuarioForm->render($args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->post('/novo', function (Request $request, Response $response, $args) use ($app) {
        //$regraUsuario = new UsuarioBLL();
        $usuario = new UsuarioInfo();

        $usuarioLogado = UsuarioBLL::pegarUsuarioAtual();
        $eAdmin = $usuarioLogado->temPermissao(UsuarioInfo::ADMIN);

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $regraLoja->validarPermissao($loja, $usuarioLogado);

        $usuarioForm = new UsuarioFormView($request, $response);
        $usuarioForm->setGrupoExibe($eAdmin);
        $usuarioForm->setGravarCookie(false);
        $usuarioForm->setSituacaoExibe($eAdmin);
        $usuarioForm->setTemplate(UsuarioFormView::TEMPLATE_USUARIO_CADASTRO);
        $usuarioForm->setUsuario($usuario);
        try {
            $usuarioForm->gravar();
            $usuario = $usuarioForm->getUsuario();
            $url = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        catch (Exception $e) {

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/usuario/listar");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['erro'] = $e->getMessage();
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            $response = $rendererMain->render($response, 'header.php', $args);
            $usuarioForm->render($args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        }
    });

    $app->get('/{slug}/alterar', function (Request $request, Response $response, $args) use ($app) {
        $regraUsuario = new UsuarioBLL();
        $usuario = $regraUsuario->pegarPorSlug($args['slug']);

        $usuarioLogado = UsuarioBLL::pegarUsuarioAtual();
        $eAdmin = $usuarioLogado->temPermissao(UsuarioInfo::ADMIN);

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $regraLoja->validarPermissao($loja, $usuarioLogado);

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/usuario/listar");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $args['app'] = $app;
        $args['usuario'] = $usuario;
        $args['usuarioPerfil'] = $usuarioPerfil;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');

        $response = $rendererMain->render($response, 'header.php', $args);
        $usuarioForm = new UsuarioFormView($request, $response);
        $usuarioForm->setGrupoExibe(false);
        $usuarioForm->setGravarCookie(false);
        $usuarioForm->setSituacaoExibe($eAdmin);
        $usuarioForm->setTemplate(UsuarioFormView::TEMPLATE_USUARIO_CADASTRO);
        $usuarioForm->setUsuario($usuario);
        $response = $usuarioForm->render($args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->post('/{slug}/alterar', function (Request $request, Response $response, $args) use ($app) {
        $regraUsuario = new UsuarioBLL();
        $usuario = $regraUsuario->pegarPorSlug($args['slug']);

        $usuarioLogado = UsuarioBLL::pegarUsuarioAtual();
        $eAdmin = $usuarioLogado->temPermissao(UsuarioInfo::ADMIN);

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $regraLoja->validarPermissao($loja, $usuarioLogado);

        $usuarioForm = new UsuarioFormView($request, $response);
        $usuarioForm->setGrupoExibe(false);
        $usuarioForm->setGravarCookie(false);
        $usuarioForm->setSituacaoExibe($eAdmin);
        $usuarioForm->setTemplate(UsuarioFormView::TEMPLATE_USUARIO_CADASTRO);
        $usuarioForm->setUsuario($usuario);
        try {
            $usuarioForm->gravar();
            $usuario = $usuarioForm->getUsuario();
            $url = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        catch (Exception $e) {

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/usuario/listar");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['erro'] = $e->getMessage();
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            $response = $rendererMain->render($response, 'header.php', $args);
            $usuarioForm->render($args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        }
    });

})->add(function (Request $request, Response $response, callable $next) {
    $regraUsuario = new UsuarioBLL();
    if (!$regraUsuario->estaLogado()) {
        throw new Exception("Acesso negado!");
    }
    $response = $next($request, $response);
    return $response;
});