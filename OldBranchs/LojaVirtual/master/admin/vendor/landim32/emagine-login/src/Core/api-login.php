<?php
namespace Emagine\Login;

use Emagine\Login\Controls\UsuarioFormView;
use Emagine\Login\Factory\GrupoFactory;
use Emagine\Login\Factory\PermissaoFactory;
use Emagine\Login\Factory\UsuarioFactory;
use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\BLL\PermissaoBLL;
use Emagine\Login\BLL\GrupoBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Login\Model\GrupoInfo;
use Emagine\Login\Model\PermissaoInfo;
use Emagine\Login\Controls\UsuarioRouter;

$app = EmagineApp::getApp();

if (UsuarioBLL::usaLoginRoute()) {

    $app->group('/api/usuario', function () use ($app) {

        $app->get('/listar[/{cod_situacao}]', function (Request $request, Response $response, $args) {
            $codSituacao = intval($args["cod_situacao"]);
            try {
                $regraUsuario = UsuarioFactory::create();
                $usuarios = $regraUsuario->listar($codSituacao);
                return $response->withStatus(200)->withJson($usuarios);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/pegar/{id_usuario}', function (Request $request, Response $response, $args) {
            try {
                $regraUsuario = UsuarioFactory::create();
                $usuario = $regraUsuario->pegar($args['id_usuario']);
                if (is_null($usuario)) {
                    throw new Exception("Usuário não encontrado.");
                }
                return $response->withStatus(200)->withJson($usuario);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/logar', function (Request $request, Response $response, $args) {
            try {
                $json = json_decode($request->getBody()->getContents());

                $regraUsuario = UsuarioFactory::create();
                $id_usuario = $regraUsuario->logar($json->email, $json->senha);
                $body = $response->getBody();
                $body->write($id_usuario);
                return $response->withStatus(200);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/inserir', function (Request $request, Response $response, $args) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $usuario = UsuarioInfo::fromJson($json);
                $regraUsuario = UsuarioFactory::create();
                $id_usuario = $regraUsuario->inserir($usuario);
                $body = $response->getBody();
                $body->write($id_usuario);
                return $response->withStatus(200);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/alterar', function (Request $request, Response $response, $args) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $usuario = UsuarioInfo::fromJson($json);
                $regraUsuario = UsuarioFactory::create();
                $regraUsuario->alterar($usuario);
                return $response->withStatus(200);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/resetar-senha', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $regraUsuario = UsuarioFactory::create();
                $usuario = $regraUsuario->pegarPorEmail($json->email);
                if (is_null($usuario)) {
                    throw new Exception("Email não encontrado.");
                }
                $regraUsuario->resetarSenha($usuario);
                return $response->withStatus(200);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });
        $app->put('/trocar-senha', function (Request $request, Response $response, $args) use ($app) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $regraUsuario = UsuarioFactory::create();
                $usuario = $regraUsuario->pegar($json->id_usuario);
                if ($usuario->getSenha() != $json->senha_antiga) {
                    throw new Exception("A senha antiga não está batendo.");
                }
                $regraUsuario->alterarSenha($json->id_usuario, $json->senha);
                return $response->withStatus(200);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->post('/alterar/foto', function (Request $request, Response $response, $args) use ($app) {
            try {
                $param = $request->getParsedBody();
                $args['app'] = $app;
                $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
                $regraUsuario = UsuarioFactory::create();
                //var_dump($param);
                $regraUsuario->alterarFoto($id_usuario, $param['foto']);
                return $response->withStatus(200);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });
    });

    $app->group('/api/permissao', function () use ($app) {

        $app->get('/listar', function (Request $request, Response $response, $args) {
            $regraPermissao = PermissaoFactory::create();
            $permissao_lista = $regraPermissao->listar();
            return $response->withJson($permissao_lista);
        });

        $app->put('/inserir', function (Request $request, Response $response, $args) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $permissao = PermissaoInfo::fromJson($json);
                $regraPermissao = PermissaoFactory::create();
                $regraPermissao->inserir($permissao);
                return $response->withStatus(200);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/alterar', function (Request $request, Response $response, $args) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $permissao = PermissaoInfo::fromJson($json);
                $regraPermissao = PermissaoFactory::create();
                $regraPermissao->alterar($permissao);
                return $response->withStatus(200);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/excluir/{slug}', function (Request $request, Response $response, $args) {
            $regraPermissao = PermissaoFactory::create();
            $regraPermissao->excluir($args['slug']);
            return $response->withStatus(200);
        });
    })->add(function (Request $request, Response $response, callable $next) {
        $regraUsuario = UsuarioFactory::create();
        if (!$regraUsuario->estaLogado()) {
            throw new Exception("Acesso negado!");
        }
        $response = $next($request, $response);
        return $response;
    });

    $app->group('/api/grupo', function () use ($app) {

        $app->get('/listar', function (Request $request, Response $response, $args) {
            try {
                $regraGrupo = GrupoFactory::create();
                $grupo_lista = $regraGrupo->listar();
                return $response->withJson($grupo_lista);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/pegar/{id_grupo}', function (Request $request, Response $response, $args) {
            try {
                $regraGrupo = GrupoFactory::create();
                $grupo = $regraGrupo->pegar($args['id_grupo']);
                return $response->withJson($grupo);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/inserir', function (Request $request, Response $response, $args) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $grupo = GrupoInfo::fromJson($json);
                $regraGrupo = GrupoFactory::create();
                $id_grupo = $regraGrupo->inserir($grupo);
                return $response->withJson($id_grupo);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->put('/alterar', function (Request $request, Response $response, $args) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $grupo = GrupoInfo::fromJson($json);
                $regraGrupo = GrupoFactory::create();
                $regraGrupo->alterar($grupo);
                return $response->withStatus(200);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/excluir/{id_grupo}', function (Request $request, Response $response, $args) {
            try {
                $regraGrupo = GrupoFactory::create();
                $regraGrupo->excluir($args['id_grupo']);
                return $response->withStatus(200);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });
    });
}