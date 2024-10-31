<?php
namespace Emagine\Banner;

use Emagine\Banner\Model\BannerPecaInfo;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Emagine\Produto\Model\LojaInfo;
use Exception;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Banner\BLL\BannerBLL;
use Emagine\Banner\BLL\BannerPecaBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Banner\Model\BannerInfo;

$app = EmagineApp::getApp();

$app->group('/banner', function () use ($app) {
    $app->get('/listar', function (Request $request, Response $response, $args) use ($app) {

        $regraLoja = new LojaBLL();
        $regraBanner = new BannerBLL();

        $loja = null;
        $usuario = UsuarioBLL::pegarUsuarioAtual();
        if (!is_null($usuario)) {
            if ($usuario->temPermissao(BannerInfo::GERENCIAR_BANNER)) {
                $banners = $regraBanner->listar();
                $lojas = $regraLoja->listar();
            }
            else {
                $banners = $regraBanner->listar(BannerInfo::NORMAL);
                $lojas = $regraLoja->listarPorUsuario($usuario->getId());
            }
            if (count($lojas) > 0) {
                /** @var LojaInfo $loja */
                $loja = array_values($lojas)[0];
            }
        }
        else {
            $banners = $regraBanner->listar();
        }

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/banner/%s/listar");
        if (!is_null($loja) && $usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        if (count($banners) == 1) {
            /** @var BannerInfo $banner */
            $banner = array_values($banners)[0];
            $url = $app->getBaseUrl() . "/banner/" . $banner->getSlug();
            return $response->withStatus(302)->withHeader('Location', $url);
        }

        $podeEditarBanner = (!is_null($usuario) && $usuario->temPermissao(BannerInfo::GERENCIAR_BANNER));

        $args['app'] = $app;
        //$args["loja"] = $loja;
        $args['usuario'] = $usuario;
        $args['banners'] = $banners;
        $args['usuarioPerfil'] = $usuarioPerfil;
        $args['podeEditarBanner'] = $podeEditarBanner;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererBanner */
        $rendererBanner = $this->get('banner');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererBanner->render($response, 'banners.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/novo', function (Request $request, Response $response, $args) use ($app) {

        $regraLoja = new LojaBLL();
        //$loja = $regraLoja->pegarPorSlug($args["slug_loja"]);;

        $queryParam = $request->getQueryParams();

        $loja = null;
        $usuario = UsuarioBLL::pegarUsuarioAtual();
        if (!is_null($usuario)) {
            if ($usuario->temPermissao(BannerInfo::GERENCIAR_BANNER)) {
                $lojas = $regraLoja->listar();
            }
            else {
                $lojas = $regraLoja->listarPorUsuario($usuario->getId());
            }
            if (count($lojas) > 0) {
                /** @var LojaInfo $loja */
                $loja = array_values($lojas)[0];
            }
        }

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/banner/%s/listar");
        if (!is_null($loja) && $usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $banner = new BannerInfo();
        $banner->setId($args['id_banner']);

        $args['banner'] = $banner;
        $args['app'] = $app;
        //$args['usuario'] = $usuario;
        //$args["lojas"] = $lojas;
        if (array_key_exists("erro", $queryParam)) {
            $args['erro'] = $queryParam['erro'];
        }

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererBanner */
        $rendererBanner = $this->get('banner');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererBanner->render($response, 'banner-form.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->group('/{slug_banner}', function () use ($app) {

        $app->get('', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $regraLoja = new LojaBLL();
            $regraBanner = new BannerBLL();
            $regraPeca = new BannerPecaBLL();

            $banner = $regraBanner->pegarPorSlug($args['slug_banner']);

            $id_loja = 0;
            if (array_key_exists("loja", $queryParam)) {
                $id_loja = intval($queryParam['loja']);
            }
            $usuario = UsuarioBLL::pegarUsuarioAtual();
            if (!is_null($usuario)) {
                if ($usuario->temPermissao(BannerInfo::GERENCIAR_BANNER)) {
                    $lojas = $regraBanner->listarLojaPorBanner($banner->getId());
                    $pecas = $regraPeca->listar($banner->getId(), $id_loja);
                }
                else {
                    $lojas = $regraLoja->listarPorUsuario($usuario->getId());
                    /** @var LojaInfo $loja */
                    $loja = array_values($lojas)[0];
                    $pecas = $regraPeca->listar($banner->getId(), $loja->getId());
                }
            }
            else {
                throw new Exception("Acesso negado!");
            }

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/banner/%s/listar");
            if (isset($loja) && $usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            //$args["loja"] = $loja;
            $args["id_loja"] = $id_loja;
            $args["lojas"] = $lojas;
            $args['banner'] = $banner;
            $args['usuario'] = $usuario;
            $args['pecas'] = $pecas;
            $args['usuarioPerfil'] = $usuarioPerfil;
            //$args['eAdmin'] = $eAdmin;
            if (array_key_exists("sucesso", $queryParam)) {
                $args['sucesso'] = $queryParam['sucesso'];
            }
            if (array_key_exists("erro", $queryParam)) {
                $args['erro'] = $queryParam['erro'];
            }

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererBanner */
            $rendererBanner = $this->get('banner');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererBanner->render($response, 'banner.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/alterar', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $regraLoja = new LojaBLL();
            $regraBanner = new BannerBLL();

            $banner = $regraBanner->pegarPorSlug($args['slug_banner']);

            $loja = null;
            $usuario = UsuarioBLL::pegarUsuarioAtual();
            if (!is_null($usuario)) {
                if ($usuario->temPermissao(BannerInfo::GERENCIAR_BANNER)) {
                    $lojas = $regraLoja->listar();
                }
                else {
                    $lojas = $regraLoja->listarPorUsuario($usuario->getId());
                }
                if (count($lojas) > 0) {
                    /** @var LojaInfo $loja */
                    $loja = array_values($lojas)[0];
                }
            }

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/banner/%s/listar");
            if (!is_null($loja) && $usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['banner'] = $banner;
            $args['usuarioPerfil'] = $usuarioPerfil;
            if (array_key_exists("erro", $queryParam)) {
                $args['erro'] = $queryParam['erro'];
            }

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererBanner */
            $rendererBanner = $this->get('banner');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererBanner->render($response, 'banner-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/excluir', function (Request $request, Response $response, $args) use ($app) {
            //$regraLoja = new LojaBLL();
            //$loja = $regraLoja->pegarPorSlug($args["slug_loja"]);
            //$url = $app->getBaseUrl() . "/banner/" . $loja->getSlug() . "/listar";
            $url = $app->getBaseUrl() . "/banner/listar";

            try {
                $regraBanner = new BannerBLL();
                $banner = $regraBanner->pegarPorSlug($args["slug_banner"]);
                $regraBanner->excluir($banner->getId());
                $url .= "?sucesso=" . urlencode("Espaço excluído com sucesso.");
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $url .= "?erro=" . urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        });

        $app->get('/nova', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $regraLoja = new LojaBLL();
            $regraBanner = new BannerBLL();

            $loja = null;
            $lojas = array();
            $usuario = UsuarioBLL::pegarUsuarioAtual();
            if (!is_null($usuario)) {
                if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
                    $lojas = $regraLoja->listar();
                    $banners = $regraBanner->listar();
                }
                else {
                    $lojas = $regraLoja->listarPorUsuario($usuario->getId());
                }
                if (count($lojas) > 0) {
                    /** @var LojaInfo $loja */
                    $loja = array_values($lojas)[0];
                }
            }
            if (!isset($banners)) {
                $banners = $regraBanner->listar(BannerInfo::NORMAL);
            }

            $banner = null;
            $peca = new BannerPecaInfo();
            $peca->setId($args['id_peca']);

            //$loja = $regraLoja->pegarPorSlug($args["slug_loja"]);
            if (array_key_exists("banner", $queryParam)) {
                $idBanner = intval($queryParam["banner"]);
                $banner = $regraBanner->pegar($idBanner);
                $peca->setIdBanner($idBanner);
            }

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/banner/%s/listar");
            if (!is_null($loja) && $usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['usuario'] = $usuario;
            $args['usuarioPerfil'] = $usuarioPerfil;
            $args['lojas'] = $lojas;
            $args['banners'] = $banners;
            $args['banner'] = $banner;
            $args['peca'] = $peca;
            if (array_key_exists("erro", $queryParam)) {
                $args['erro'] = $queryParam['erro'];
            }

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererBanner */
            $rendererBanner = $this->get('banner');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererBanner->render($response, 'peca-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->post("", function (Request $request, Response $response, $args) use ($app) {
            $params = $request->getParsedBody();
            $regraBanner = new BannerBLL();
            $regraPeca = new BannerPecaBLL();
            $regraLoja = new LojaBLL();

            //$loja = $regraLoja->pegarPorSlug($args["slug_loja"]);

            $peca = null;
            $id_peca = intval($params['id_peca']);
            if ($id_peca > 0) {
                $peca = $regraPeca->pegar($id_peca);
            }
            if (is_null($peca)) {
                $peca = new BannerPecaInfo();
                //$peca->setIdLoja($loja->getId());
            }
            $peca = $regraPeca->pegarDoPost($params, $peca);
            $regraPeca->atualizarFoto($peca, $request);

            $banner = $regraBanner->pegar($peca->getIdBanner());

            try {
                if (!($id_peca > 0)) {
                    $id_peca = $regraPeca->inserir($peca);
                }
                else {
                    $regraPeca->alterar($peca);
                    $id_peca = $peca->getId();
                }
                $peca = $regraPeca->pegar($id_peca);
                //$banner = $regraBanner->pegar($peca->getIdBanner());
                $url = $app->getBaseUrl() . "/banner/" . $banner->getSlug() . "/" . $peca->getId();
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {

                $queryParam = $request->getQueryParams();

                $banner = null;
                if ($peca->getIdBanner() > 0) {
                    $banner = $regraBanner->pegar($peca->getIdBanner());
                }
                elseif (array_key_exists("banner", $queryParam)) {
                    $idBanner = intval($queryParam["banner"]);
                    $banner = $regraBanner->pegar($idBanner);
                }

                $loja = null;
                $lojas = array();
                $usuario = UsuarioBLL::pegarUsuarioAtual();
                if (!is_null($usuario)) {
                    if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
                        $lojas = $regraLoja->listar();
                        $banners = $regraBanner->listar();
                    }
                    else {
                        $lojas = $regraLoja->listarPorUsuario($usuario->getId());
                    }
                    if (count($lojas) > 0) {
                        /** @var LojaInfo $loja */
                        $loja = array_values($lojas)[0];
                    }
                }
                if (!isset($banners)) {
                    $banners = $regraBanner->listar(BannerInfo::NORMAL);
                }

                $usuarioPerfil = UsuarioPerfilFactory::create();
                $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/banner/%s/listar");
                if (!is_null($loja) && $usuarioPerfil instanceof LojaPerfilBLL) {
                    $usuarioPerfil->setLoja($loja);
                }

                $args['app'] = $app;
                $args['usuarioPerfil'] = $usuarioPerfil;
                $args['lojas'] = $lojas;
                $args['banners'] = $banners;
                $args['banner'] = $banner;
                $args['peca'] = $peca;
                $args['erro'] = $e->getMessage();

                /** @var PhpRenderer $rendererMain */
                $rendererMain = $this->get('view');
                /** @var PhpRenderer $rendererBanner */
                $rendererBanner = $this->get('banner');

                $response = $rendererMain->render($response, 'header.php', $args);
                $response = $rendererBanner->render($response, 'peca-form.php', $args);
                $response = $rendererMain->render($response, 'footer.php', $args);
                return $response;
            }
        });

        $app->group('/{id_peca}', function () use ($app) {

            $app->get('', function (Request $request, Response $response, $args) use ($app) {
                $regraLoja = new LojaBLL();
                $regraBanner = new BannerBLL();
                $regraPeca = new BannerPecaBLL();

                //$loja = $regraLoja->pegarPorSlug($args['slug_loja']);
                $banner = $regraBanner->pegarPorSlug($args["slug_banner"]);
                $peca = $regraPeca->pegar($args['id_peca']);

                $loja = null;
                $lojas = array();
                $usuario = UsuarioBLL::pegarUsuarioAtual();
                if (!is_null($usuario)) {
                    if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
                        $lojas = $regraLoja->listar();
                    }
                    else {
                        $lojas = $regraLoja->listarPorUsuario($usuario->getId());
                    }
                    if (count($lojas) > 0) {
                        /** @var LojaInfo $loja */
                        $loja = array_values($lojas)[0];
                    }
                }

                $usuarioPerfil = UsuarioPerfilFactory::create();
                $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/banner/%s/listar");
                if (!is_null($loja) && $usuarioPerfil instanceof LojaPerfilBLL) {
                    $usuarioPerfil->setLoja($loja);
                }

                $args['app'] = $app;
                $args['loja'] = $loja;
                $args['lojas'] = $lojas;
                $args['banner'] = $banner;
                $args['peca'] = $peca;
                $args['usuario'] = $usuario;
                $args['usuarioPerfil'] = $usuarioPerfil;

                /** @var PhpRenderer $rendererMain */
                $rendererMain = $this->get('view');
                /** @var PhpRenderer $rendererBanner */
                $rendererBanner = $this->get('banner');

                $response = $rendererMain->render($response, 'header.php', $args);
                $response = $rendererBanner->render($response, 'peca.php', $args);
                $response = $rendererMain->render($response, 'footer.php', $args);
                return $response;
            });

            $app->get('/alterar', function (Request $request, Response $response, $args) use ($app) {
                $queryParam = $request->getQueryParams();

                $regraLoja = new LojaBLL();
                $regraBanner = new BannerBLL();

                //$banner = null;
                //$loja = $regraLoja->pegarPorSlug($args["slug_loja"]);
                $banner = $regraBanner->pegarPorSlug($args["slug_banner"]);

                $loja = null;
                $lojas = array();
                $usuario = UsuarioBLL::pegarUsuarioAtual();
                if (!is_null($usuario)) {
                    if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
                        $lojas = $regraLoja->listar();
                        $banners = $regraBanner->listar();
                    }
                    else {
                        $lojas = $regraLoja->listarPorUsuario($usuario->getId());
                    }
                    if (count($lojas) > 0) {
                        /** @var LojaInfo $loja */
                        $loja = array_values($lojas)[0];
                    }
                }
                if (!isset($banners)) {
                    $banners = $regraBanner->listar(BannerInfo::NORMAL);
                }

                $usuarioPerfil = UsuarioPerfilFactory::create();
                $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/banner/%s/listar");
                if (!is_null($loja) && $usuarioPerfil instanceof LojaPerfilBLL) {
                    $usuarioPerfil->setLoja($loja);
                }

                $regraPeca = new BannerPecaBLL();
                $peca = $regraPeca->pegar($args['id_peca']);

                $args['app'] = $app;
                $args['usuario'] = $usuario;
                $args['usuarioPerfil'] = $usuarioPerfil;
                $args['lojas'] = $lojas;
                $args['banners'] = $banners;
                $args['banner'] = $banner;
                $args['peca'] = $peca;
                if (array_key_exists("erro", $queryParam)) {
                    $args['erro'] = $queryParam['erro'];
                }

                /** @var PhpRenderer $rendererMain */
                $rendererMain = $this->get('view');
                /** @var PhpRenderer $rendererBanner */
                $rendererBanner = $this->get('banner');

                $response = $rendererMain->render($response, 'header.php', $args);
                $response = $rendererBanner->render($response, 'peca-form.php', $args);
                $response = $rendererMain->render($response, 'footer.php', $args);
                return $response;
            });

            $app->get('/excluir', function (Request $request, Response $response, $args) use ($app) {
                //$regraLoja = new LojaBLL();
                $regraBanner = new BannerBLL();
                $regraPeca = new BannerPecaBLL();

                //$loja = $regraLoja->pegarPorSlug($args['slug_loja']);
                $banner = $regraBanner->pegarPorSlug($args["slug_banner"]);

                $url = $app->getBaseUrl() . "/banner/" . $banner->getSlug();
                try {
                    $regraPeca->excluir($args['id_peca']);
                    $url .= "?sucesso=" . urlencode("Banner excluído com sucesso!");
                    return $response->withStatus(302)->withHeader('Location', $url);
                }
                catch (Exception $e) {
                    $url .= "?erro=" . urlencode($e->getMessage());
                    return $response->withStatus(302)->withHeader('Location', $url);
                }
            });

            $app->get('/mover-acima', function (Request $request, Response $response, $args) use ($app) {
                //$regraLoja = new LojaBLL();
                $regraBanner = new BannerBLL();
                $regraPeca = new BannerPecaBLL();

                //$loja = $regraLoja->pegarPorSlug($args['slug_loja']);
                $banner = $regraBanner->pegarPorSlug($args["slug_banner"]);

                $url = $app->getBaseUrl() . "/banner/" . $banner->getSlug();
                try {
                    $regraPeca->moverAcima(intval($args['id_peca']));
                    $url .= "?sucesso=" . urlencode("Banner movido acima com sucesso!");
                    return $response->withStatus(302)->withHeader('Location', $url);
                }
                catch (Exception $e) {
                    $url .= "?erro=" . urlencode($e->getMessage());
                    return $response->withStatus(302)->withHeader('Location', $url);
                }
            });

            $app->get('/mover-abaixo', function (Request $request, Response $response, $args) use ($app) {
                $regraLoja = new LojaBLL();
                $regraBanner = new BannerBLL();
                $regraPeca = new BannerPecaBLL();

                //$loja = $regraLoja->pegarPorSlug($args['slug_loja']);
                $banner = $regraBanner->pegarPorSlug($args["slug_banner"]);

                $url = $app->getBaseUrl() . "/banner/" . $banner->getSlug();
                try {
                    $regraPeca->moverAbaixo(intval($args['id_peca']));
                    $url .= "?sucesso=" . urlencode("Banner movido acima com sucesso!");
                    return $response->withStatus(302)->withHeader('Location', $url);
                }
                catch (Exception $e) {
                    $url .= "?erro=" . urlencode($e->getMessage());
                    return $response->withStatus(302)->withHeader('Location', $url);
                }
            });
        });
    });

    $app->post("", function (Request $request, Response $response, $args) use ($app) {
        $queryParam = $request->getParsedBody();
        $regraBanner = new BannerBLL();
        $regraLoja = new LojaBLL();

        $id_banner = intval($queryParam['id_banner']);

        $banner = null;
        if ($id_banner > 0) {
            $banner = $regraBanner->pegar($id_banner);
        }
        if (is_null($banner)) {
            $banner = new BannerInfo();
        }
        $banner = $regraBanner->pegarDoPost($queryParam, $banner);

        try {
            if (!($id_banner > 0)) {
                $id_banner = $regraBanner->inserir($banner);
            }
            else {
                $regraBanner->alterar($banner);
                $id_banner = $banner->getId();
            }
            $banner = $regraBanner->pegar($id_banner);
            $url = $app->getBaseUrl() . "/banner/" . $banner->getSlug();
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        catch (Exception $e) {

            $loja = null;
            $usuario = UsuarioBLL::pegarUsuarioAtual();
            if (!is_null($usuario)) {
                if ($usuario->temPermissao(BannerInfo::GERENCIAR_BANNER)) {
                    $lojas = $regraLoja->listar();
                }
                else {
                    $lojas = $regraLoja->listarPorUsuario($usuario->getId());
                }
                if (count($lojas) > 0) {
                    /** @var LojaInfo $loja */
                    $loja = array_values($lojas)[0];
                }
            }

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/banner/%s/listar");
            if (!is_null($loja) && $usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['erro'] = $e->getMessage();
            $args['banner'] = $banner;
            $args['usuarioPerfil'] = $usuarioPerfil;

            if (array_key_exists("id_loja", $queryParam)) {
                $regraLoja = new LojaBLL();
                $loja = $regraLoja->pegar($queryParam["id_loja"]);
                $args['loja'] = $loja;
            }

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererBanner */
            $rendererBanner = $this->get('banner');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererBanner->render($response, 'banner-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);

            return $response;
        }
    });
});
