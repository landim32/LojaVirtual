<?php

namespace Emagine\Login;

use Emagine\Login\Controls\UsuarioFormView;
use Emagine\Login\Factory\UsuarioFactory;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;

$app = EmagineApp::getApp();

if (UsuarioBLL::usaLoginRoute()) {

    $app->group('/usuario', function () use ($app) {

        $app->get('/{slug}/perfil', function (Request $request, Response $response, $args) use ($app) {
            $regraUsuario = UsuarioFactory::create();
            $usuario = $regraUsuario->pegarPorSlug($args['slug']);

            $usuarioLogado = UsuarioBLL::pegarUsuarioAtual();
            $eAdmin = $usuarioLogado->temPermissao(UsuarioInfo::ADMIN);
            $podeExcluir =  ($usuario->getId() != $usuarioLogado->getId() && $eAdmin == true);

            $queryParam = $request->getQueryParams();
            $args['app'] = $app;
            $args['usuario'] = $usuario;
            $args['eAdmin'] = $eAdmin;
            $args['podeExcluir'] = $podeExcluir;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();
            if (array_key_exists("erro", $queryParam)) {
                $args['erro'] = $queryParam["erro"];
            }

            /** @var PhpRenderer $rendererLogin */
            $rendererLogin = $this->get('loginView');
            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLogin->render($response, 'usuario.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/listar', function (Request $request, Response $response, $args) use ($app) {
            $regraUsuario = UsuarioFactory::create();
            $usuarios = $regraUsuario->listar();

            $usuarioLogado = UsuarioBLL::pegarUsuarioAtual();
            $eAdmin = $usuarioLogado->temPermissao(UsuarioInfo::ADMIN);

            $args['app'] = $app;
            $args['usaFoto'] = UsuarioBLL::usaFoto();
            $args['eAdmin'] = $eAdmin;
            $args['usuarioLogado'] = $usuarioLogado;
            $args['usuarioLista'] = $usuarios;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererLogin */
            $rendererLogin = $this->get('loginView');
            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLogin->render($response, 'usuarios.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/ver/{slug}', function (Request $request, Response $response, $args) use ($app) {
            $regraUsuario = UsuarioFactory::create();

            $args['app'] = $app;
            $args['usuario'] = $regraUsuario->pegar($args['id_usuario']);
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererLogin */
            $rendererLogin = $this->get('loginView');
            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLogin->render($response, 'usuario.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/novo', function (Request $request, Response $response, $args) use ($app) {
            ///$regraUsuario = new UsuarioBLL();
            $usuario = new UsuarioInfo();

            $usuarioLogado = UsuarioBLL::pegarUsuarioAtual();
            $eAdmin = $usuarioLogado->temPermissao(UsuarioInfo::ADMIN);

            $args['app'] = $app;
            $args['usuario'] = $usuario;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

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
                $args['app'] = $app;
                $args['usuarioPerfil'] = UsuarioPerfilFactory::create();
                $args['erro'] = $e->getMessage();

                /** @var PhpRenderer $rendererMain */
                $rendererMain = $this->get('view');
                $response = $rendererMain->render($response, 'header.php', $args);
                $usuarioForm->render($args);
                $response = $rendererMain->render($response, 'footer.php', $args);
                return $response;
            }
        });

        $app->post('/alterar/foto', function (Request $request, Response $response, $args) use ($app) {
            $args['app'] = $app;
            $regraUsuario = UsuarioFactory::create();
            $args['usuario'] = $regraUsuario->pegar($args['id_usuario']);
            /** @var PhpRenderer $rendererLogin */
            $rendererLogin = $this->get('loginView');
            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLogin->render($response, 'usuario-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug}/trocar-senha', function (Request $request, Response $response, $args) use ($app) {
            $regraUsuario = UsuarioFactory::create();
            $usuario = $regraUsuario->pegarPorSlug($args['slug']);

            $args['app'] = $app;
            $args['usuario'] = $usuario;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererLogin */
            $rendererLogin = $this->get('loginView');
            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLogin->render($response, 'trocar-senha.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->post('/{slug}/trocar-senha', function (Request $request, Response $response, $args) use ($app) {
            $regraUsuario = UsuarioFactory::create();
            $usuario = $regraUsuario->pegarPorSlug($args['slug']);

            try {
                $args['app'] = $app;
                $params = $request->getParsedBody();
                if ($params['senha'] != $params['confirma']) {
                    throw new Exception("A senha não está conferindo");
                }
                $regraUsuario->alterarSenha($usuario->getId(), $params['senha']);

                $url = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil";
                return $response->withStatus(302)->withHeader('Location', $url);
            } catch (Exception $e) {

                $args['app'] = $app;
                $args['usuario'] = $usuario;
                $args['usuarioPerfil'] = UsuarioPerfilFactory::create();
                $args['erro'] = $e->getMessage();

                /** @var PhpRenderer $rendererLogin */
                $rendererLogin = $this->get('loginView');
                /** @var PhpRenderer $rendererMain */
                $rendererMain = $this->get('view');

                $response = $rendererMain->render($response, 'header.php', $args);
                $response = $rendererLogin->render($response, 'trocar-senha.php', $args);
                $response = $rendererMain->render($response, 'footer.php', $args);
                return $response;
            }
        });

        $app->get('/{slug}/excluir-endereco/{id_endereco}', function (Request $request, Response $response, $args) use ($app) {
            $regraUsuario = UsuarioFactory::create();
            $usuario = $regraUsuario->pegarPorSlug($args['slug']);
            $regraUsuario->excluirEndereco($args['id_endereco']);
            $url = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil";
            return $response->withStatus(302)->withHeader('Location', $url);
        });

        $app->get('/{slug}/excluir-preferencia/{chave}', function (Request $request, Response $response, $args) use ($app) {
            $regraUsuario = UsuarioFactory::create();
            $usuario = $regraUsuario->pegarPorSlug($args['slug']);
            $usuario->removerPreferencia($args['chave']);
            $regraUsuario->alterar($usuario);
            $url = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil";
            return $response->withStatus(302)->withHeader('Location', $url);
        });

        $app->get('/{slug}/alterar', function (Request $request, Response $response, $args) use ($app) {
            $regraUsuario = UsuarioFactory::create();
            $usuario = $regraUsuario->pegarPorSlug($args['slug']);

            $usuarioLogado = UsuarioBLL::pegarUsuarioAtual();
            $eAdmin = $usuarioLogado->temPermissao(UsuarioInfo::ADMIN);

            $args['app'] = $app;
            $args['usuario'] = $usuario;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');

            $response = $rendererMain->render($response, 'header.php', $args);
            $usuarioForm = new UsuarioFormView($request, $response);
            $usuarioForm->setGrupoExibe($eAdmin);
            $usuarioForm->setGravarCookie(false);
            $usuarioForm->setSituacaoExibe($eAdmin);
            $usuarioForm->setValidaEmail(false);
            $usuarioForm->setTemplate(UsuarioFormView::TEMPLATE_USUARIO_CADASTRO);
            $usuarioForm->setUsuario($usuario);
            $response = $usuarioForm->render($args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->post('/{slug}/alterar', function (Request $request, Response $response, $args) use ($app) {
            $regraUsuario = UsuarioFactory::create();
            $usuario = $regraUsuario->pegarPorSlug($args['slug']);

            $usuarioLogado = UsuarioBLL::pegarUsuarioAtual();
            $eAdmin = $usuarioLogado->temPermissao(UsuarioInfo::ADMIN);

            $usuarioForm = new UsuarioFormView($request, $response);
            $usuarioForm->setGrupoExibe($eAdmin);
            $usuarioForm->setGravarCookie(false);
            $usuarioForm->setSituacaoExibe($eAdmin);
            $usuarioForm->setValidaEmail(false);
            $usuarioForm->setTemplate(UsuarioFormView::TEMPLATE_USUARIO_CADASTRO);
            $usuarioForm->setUsuario($usuario);
            try {
                $usuarioForm->gravar();
                $usuario = $usuarioForm->getUsuario();
                $url = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $args['app'] = $app;
                $args['usuarioPerfil'] = UsuarioPerfilFactory::create();
                $args['erro'] = $e->getMessage();

                /** @var PhpRenderer $rendererMain */
                $rendererMain = $this->get('view');
                $response = $rendererMain->render($response, 'header.php', $args);
                $usuarioForm->render($args);
                $response = $rendererMain->render($response, 'footer.php', $args);
                return $response;
            }
        });

        $app->get('/{slug}/excluir', function (Request $request, Response $response, $args) use ($app) {
            $regraUsuario = UsuarioFactory::create();
            $usuario = $regraUsuario->pegarPorSlug($args['slug']);
            try {
                $regraUsuario->excluir($usuario->getId());
                $url = $app->getBaseUrl() . "/usuario/listar?sucesso=1";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $url = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil";
                $url .= "?erro=" . urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        });

    })->add(function (Request $request, Response $response, callable $next) {
        $regraUsuario = UsuarioFactory::create();
        if (!$regraUsuario->estaLogado()) {
            throw new Exception("Acesso negado!");
        }
        $response = $next($request, $response);
        return $response;
    });
}