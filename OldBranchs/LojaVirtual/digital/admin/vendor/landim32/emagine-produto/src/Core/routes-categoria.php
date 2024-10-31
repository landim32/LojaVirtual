<?php
namespace Emagine\Produto;

use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Exception;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\CategoriaBLL;
use Emagine\Produto\Model\CategoriaInfo;

$app = EmagineApp::getApp();

if (CategoriaBLL::usaCategoriaRoute() == true) {

    $app->get('/{loja}/categorias', function (Request $request, Response $response, $args) use ($app) {
        $queryParam = $request->getQueryParams();

        $regraLoja = new LojaBLL();
        $regraCategoria = new CategoriaBLL();

        $loja = $regraLoja->pegarPorSlug($args['loja']);
        $usuario = UsuarioBLL::pegarUsuarioAtual();
        $regraLoja->validarPermissao($loja, $usuario);

        $categorias = $regraCategoria->listar($loja->getId());

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/categorias");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
            $args['urlLoja'] = $app->getBaseUrl() . "/%s/categorias";
            $args['lojas'] = $regraLoja->listar();
        }

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = $usuario;
        $args['categorias'] = $categorias;
        $args['usuarioPerfil'] = $usuarioPerfil;
        if (array_key_exists("erro", $queryParam)) {
            $args['erro'] = $queryParam["erro"];
        }

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererLoja */
        $rendererLoja = $this->get('lojaView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererLoja->render($response, 'categorias.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->group("/{slug_loja}/categoria", function () use ($app) {

        $app->get('/nova', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraCategoria = new CategoriaBLL();

            $usuario = UsuarioBLL::pegarUsuarioAtual();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja, $usuario);
            $categorias = $regraCategoria->listar($loja->getId());

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/categorias");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
                $args['urlLoja'] = $app->getBaseUrl() . "/%s/categoria/nova";
                $args['lojas'] = $regraLoja->listar();
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['usuario'] = $usuario;
            $args['categoria'] = new CategoriaInfo();
            $args['categorias'] = $categorias;
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'categoria-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug}/alterar', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraCategoria = new CategoriaBLL();

            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $usuario = UsuarioBLL::pegarUsuarioAtual();
            $regraLoja->validarPermissao($loja, $usuario);

            $categorias = $regraCategoria->listar($loja->getId());

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/categorias");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
                $args['urlLoja'] = $app->getBaseUrl() . "/%s/categorias";
                $args['lojas'] = $regraLoja->listar();
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['usuario'] = $usuario;
            $args['categoria'] = $regraCategoria->pegarPorSlug($loja->getId(), $args['slug']);
            $args['categorias'] = $categorias;
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'categoria-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug}/excluir', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraCategoria = new CategoriaBLL();

            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);
            $categoria = $regraCategoria->pegarPorSlug($loja->getId(), $args['slug']);

            $args['app'] = $app;
            $args['loja'] = $loja;

            try {
                $regraCategoria->excluir($categoria->getId());
                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/categorias";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/categorias";
                $url .= "?erro=" . urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        });

        $app->get('/{slug}', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraCategoria = new CategoriaBLL();

            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $usuario = UsuarioBLL::pegarUsuarioAtual();
            $regraLoja->validarPermissao($loja, $usuario);

            $categoria = $regraCategoria->pegarPorSlug($loja->getId(), $args['slug']);
            if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
                $args['urlLoja'] = $app->getBaseUrl() . "/%s/categorias";
                $args['lojas'] = $regraLoja->listar();
            }

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/categorias");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['usuario'] = $usuario;
            $args['categoria'] = $categoria;
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'categoria.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->post('', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraCategoria = new CategoriaBLL();

            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $args['app'] = $app;
            $args['loja'] = $loja;

            $paramPost = $request->getParsedBody();

            $id_categoria = intval($paramPost['id_categoria']);
            $categoria = null;
            if ($id_categoria > 0) {
                $categoria = $regraCategoria->pegar($id_categoria);
            }

            $regraCategoria->pegarDoPost($paramPost, $categoria);
            $categoria->setIdLoja($loja->getId());

            $directory = UPLOAD_PATH . '/categoria';
            if (!file_exists($directory)) {
                @mkdir($directory, 755);
            }
            $uploadedFiles = $request->getUploadedFiles();

            // handle single input with single file upload
            $uploadedFile = $uploadedFiles['foto'];
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = moveUploadedFile($directory, $uploadedFile);
                $categoria->setFoto($filename);
            }

            if ($id_categoria > 0) {
                $regraCategoria->alterar($categoria);
            } else {
                $id_categoria = $regraCategoria->inserir($categoria);
            }
            $categoria = $regraCategoria->pegar($id_categoria);

            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/categoria/" . $categoria->getSlug();
            return $response->withStatus(302)->withHeader('Location', $url);
        });
    });

}