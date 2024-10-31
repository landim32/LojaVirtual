<?php
namespace Emagine\Produto;

use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Exception;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\UnidadeBLL;
use Emagine\Produto\Model\UnidadeInfo;

$app = EmagineApp::getApp();

if (UnidadeBLL::usaUnidadeRoute() == true) {

    $app->get('/{loja}/unidades', function (Request $request, Response $response, $args) use ($app) {
        $queryParam = $request->getQueryParams();

        $regraLoja = new LojaBLL();
        $regraUnidade = new UnidadeBLL();

        $loja = $regraLoja->pegarPorSlug($args['loja']);
        $regraLoja->validarPermissao($loja);

        $unidades = $regraUnidade->listar($loja->getId());

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/unidades");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['unidades'] = $unidades;
        $args['usuarioPerfil'] = $usuarioPerfil;
        if (array_key_exists("erro", $queryParam)) {
            $args['erro'] = $queryParam["erro"];
        }

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererLoja */
        $rendererLoja = $this->get('lojaView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererLoja->render($response, 'unidades.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->group("/{slug_loja}/unidade", function () use ($app) {

        $app->get('/nova', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraUnidade = new UnidadeBLL();

            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $unidades = $regraUnidade->listar($loja->getId());

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/unidades");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['unidade'] = new UnidadeInfo();
            $args['unidades'] = $unidades;
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'unidade-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug}/alterar', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraUnidade = new UnidadeBLL();

            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $unidades = $regraUnidade->listar($loja->getId());
            $unidade = $regraUnidade->pegarPorSlug($loja->getId(), $args['slug']);

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/unidades");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['unidade'] = $unidade;
            $args['unidades'] = $unidades;
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'unidade-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug}/excluir', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraUnidade = new UnidadeBLL();

            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $unidade = $regraUnidade->pegarPorSlug($loja->getId(), $args['slug']);

            $args['app'] = $app;
            $args['loja'] = $loja;

            try {
                $regraUnidade->excluir($unidade->getId());
                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidades";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidades";
                $url .= "?erro=" . urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        });

        $app->get('/{slug}', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraUnidade = new UnidadeBLL();

            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $unidade = $regraUnidade->pegarPorSlug($loja->getId(), $args['slug']);

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/unidades");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['unidade'] = $unidade;
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'unidade.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->post('', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraUnidade = new UnidadeBLL();

            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $args['app'] = $app;
            $args['loja'] = $loja;

            $paramPost = $request->getParsedBody();

            $id_unidade = intval($paramPost['id_unidade']);
            $unidade = null;
            if ($id_unidade > 0) {
                $unidade = $regraUnidade->pegar($id_unidade);
            }

            $regraUnidade->pegarDoPost($paramPost, $unidade);
            $unidade->setIdLoja($loja->getId());

            if ($id_unidade > 0) {
                $regraUnidade->alterar($unidade);
            } else {
                $id_unidade = $regraUnidade->inserir($unidade);
            }
            $unidade = $regraUnidade->pegar($id_unidade);

            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidade/" . $unidade->getSlug();
            return $response->withStatus(302)->withHeader('Location', $url);
        });
    });

}