<?php

namespace Emagine\Produto;

use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoFiltroInfo;
use Slim\Http\UploadedFile;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\CategoriaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Produto\Model\ProdutoInfo;
use Emagine\Produto\Controls\ProdutoFormControl;
use Exception;

$app = EmagineApp::getApp();

/**
 * @param string $directory
 * @param UploadedFile $uploadedFile
 * @return string
 * @throws Exception
 */
function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

if (ProdutoBLL::usaProdutoRoute() == true) {

    $app->group("/ajax/produto", function () use ($app) {
        $app->get('/buscar', function (Request $request, Response $response, $args) use ($app) {
            try {
                $queryParam = $request->getQueryParams();

                $regraProduto = new ProdutoBLL();
                $palavraChave = $queryParam['p'];
                $filtro = (new ProdutoFiltroInfo())
                    ->setPalavraChave($palavraChave)
                    ->setPagina(1)
                    ->setTamanhoPagina(10)
                    ->setCondicao(false);
                $regraLoja = new LojaBLL();
                $loja = $regraLoja->pegarPorUsuarioComAcesso();
                $filtro->setIdLoja($loja->getId());
                /*
                $usuario = UsuarioBLL::pegarUsuarioAtual();
                if (!($usuario->temPermissao(UsuarioInfo::ADMIN))) {
                    $regraLoja = new LojaBLL();
                    $lojas = $regraLoja->listarPorUsuario($usuario->getId());
                    if (!(count($lojas) > 0)) {
                        throw new Exception("Nenhuma loja vinculada ao seu usuário.");
                    }
                    #@var LojaInfo $loja
                    $loja = array_values($lojas)[0];
                    $filtro->setIdLoja($loja->getId());
                }
                */
                $retorno = $regraProduto->buscar($filtro);
                return $response->withJson($retorno->getProdutos());
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/buscar-original', function (Request $request, Response $response, $args) use ($app) {
            try {
                $queryParam = $request->getQueryParams();
                $palavraChave = $queryParam['p'];
                $regraProduto = new ProdutoBLL();
                $produtos = $regraProduto->buscarOriginal($palavraChave);
                return $response->withJson($produtos);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });
    });

    $app->get('/{slug_loja}/produtos', function (Request $request, Response $response, $args) use ($app) {
        $queryParam = $request->getQueryParams();
        $palavraChave = $queryParam['p'];
        $pg = intval($queryParam["pg"]);
        if ($pg <= 0) $pg = 1;

        $regraLoja = new LojaBLL();
        $regraProduto = new ProdutoBLL();

        $usuario = UsuarioBLL::pegarUsuarioAtual();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $regraLoja->validarPermissao($loja, $usuario);
        $produtos = $regraProduto->buscar(
            (new ProdutoFiltroInfo())
                ->setTamanhoPagina(10)
                ->setPagina($pg)
                ->setIdLoja($loja->getId())
                ->setPalavraChave($palavraChave)
        );

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/produtos");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = $usuario;
        $args['produtos'] = $produtos;
        $args['palavraChave'] = $palavraChave;
        $args['usuarioPerfil'] = $usuarioPerfil;
        if (array_key_exists("erro", $queryParam)) {
            $args['erro'] = $queryParam['erro'];
        }

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererLoja */
        $rendererLoja = $this->get('lojaView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererLoja->render($response, 'produtos.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->group("/{slug_loja}/produto", function () use ($app) {

        $app->get('/buscar', function (Request $request, Response $response, $args) use ($app) {

            $regraLoja = new LojaBLL();
            $regraCategoria = new CategoriaBLL();

            $usuario = UsuarioBLL::pegarUsuarioAtual();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $categorias = $regraCategoria->listar($loja->getId());
            $regraLoja->validarPermissao($loja, $usuario);

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/produtos");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['usuario'] = $usuario;
            $args['produto'] = new ProdutoInfo();
            $args['categorias'] = $categorias;
            $args['usuarioPerfil'] = $usuarioPerfil;

            /** var PhpRenderer $rendererMain */
            $rendererMain = $app->getContainer()->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'produto-busca.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->post('/buscar', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraProduto = new ProdutoBLL();

            $usuario = UsuarioBLL::pegarUsuarioAtual();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja, $usuario);

            $args['app'] = $app;
            $args['loja'] = $loja;

            $paramPost = $request->getParsedBody();

            $id_produto = intval($paramPost['id_origem']);
            if (!($id_produto > 0)) {
                throw new Exception("Produto não informado!");
            }
            $produtoOrig = $regraProduto->pegar($id_produto);

            $produto = $regraProduto->pegarPorCodigo($loja->getId(), $produtoOrig->getCodigo());
            if (is_null($produto)) {
                $produto = $produtoOrig;
                $produto->setId(0);
                $produto->setIdOrigem($id_produto);
                $produto->setIdUsuario(UsuarioBLL::pegarIdUsuarioAtual());
                $produto->setIdLoja($loja->getId());
            }
            $regraProduto->pegarDoPost($paramPost, $produto);

            try {
                if ($produto->getId() > 0) {
                    $regraProduto->alterar($produto);
                    $id_produto = $produto->getId();
                }
                else {
                    $id_produto = $regraProduto->inserir($produto);
                }
                $produto = $regraProduto->pegar($id_produto);

                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/" . $produto->getSlug();
                return $response->withStatus(302)->withHeader('Location', $url);
            } catch (Exception $e) {
                $regraCategoria = new CategoriaBLL();
                $categorias = $regraCategoria->listar($loja->getId());

                $usuarioPerfil = UsuarioPerfilFactory::create();
                $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/produtos");
                if ($usuarioPerfil instanceof LojaPerfilBLL) {
                    $usuarioPerfil->setLoja($loja);
                }

                $args['usuario'] = $usuario;
                $args['erro'] = $e->getMessage();
                $args['produto'] = $produto;
                $args['categorias'] = $categorias;
                $args['usuarioPerfil'] = $usuarioPerfil;

                /** var PhpRenderer $rendererMain */
                $rendererMain = $app->getContainer()->get('view');
                /** @var PhpRenderer $rendererLoja */
                $rendererLoja = $this->get('lojaView');

                $response = $rendererMain->render($response, 'header.php', $args);
                $response = $rendererLoja->render($response, 'produto-busca.php', $args);
                $response = $rendererMain->render($response, 'footer.php', $args);
                return $response;
            }
        });

        $app->get('/novo', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();
            $regraLoja = new LojaBLL();

            $usuario = UsuarioBLL::pegarUsuarioAtual();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja, $usuario);

            $args['app'] = $app;
            $args['usuario'] = $usuario;
            $args['loja'] = $loja;
            if (array_key_exists("sucesso", $queryParam)) {
                $args['sucesso'] = $queryParam["sucesso"];
            }
            if (array_key_exists("erro", $queryParam)) {
                $args['erro'] = $queryParam["erro"];
            }

            /** var PhpRenderer $rendererMain */
            $rendererMain = $app->getContainer()->get('view');

            $response = $rendererMain->render($response, 'header.php', $args);
            $produtoForm = new ProdutoFormControl($request, $response);
            $produtoForm->setProduto(new ProdutoInfo());
            $response = $produtoForm->render($args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug}/alterar', function (Request $request, Response $response, $args) use ($app) {

            $regraLoja = new LojaBLL();
            $regraProduto = new ProdutoBLL();

            $usuario = UsuarioBLL::pegarUsuarioAtual();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja, $usuario);

            $produto = $regraProduto->pegarPorSlug($loja->getId(), $args['slug']);

            $args['app'] = $app;
            $args['usuario'] = $usuario;

            /** var PhpRenderer $rendererMain */
            $rendererMain = $app->getContainer()->get('view');

            $response = $rendererMain->render($response, 'header.php', $args);
            $produtoForm = new ProdutoFormControl($request, $response);
            $produtoForm->setProduto($produto);
            $response = $produtoForm->render($args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug}/excluir', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraProduto = new ProdutoBLL();

            $args['app'] = $app;

            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $produto = $regraProduto->pegarPorSlug($loja->getId(), $args['slug']);

            try {
                $regraProduto->excluir($produto->getId());

                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produtos";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produtos";
                $url .= "?erro=" . urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        });

        $app->get("/{slug}", function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();

            $regraLoja = new LojaBLL();
            $regraProduto = new ProdutoBLL();

            $usuario = UsuarioBLL::pegarUsuarioAtual();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja, $usuario);

            $produto = $regraProduto->pegarPorSlug($loja->getId(), $args['slug']);

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/produtos");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['produto'] = $produto;
            $args['usuario'] = $usuario;
            $args['usuarioPerfil'] = $usuarioPerfil;
            if (array_key_exists("erro", $queryParam)) {
                $args['erro'] = $queryParam['erro'];
            }

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'produto.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->post("", function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraProduto = new ProdutoBLL();

            $usuario = UsuarioBLL::pegarUsuarioAtual();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja, $usuario);

            $args['app'] = $app;
            $args['loja'] = $loja;

            $paramPost = $request->getParsedBody();

            $id_produto = intval($paramPost['id_produto']);
            $produto = null;
            if ($id_produto > 0) {
                $produto = $regraProduto->pegar($id_produto);
            }

            $regraProduto->pegarDoPost($paramPost, $produto);

            try {
                $directory = UPLOAD_PATH . '/produto';
                if (!file_exists($directory)) {
                    @mkdir($directory, 755);
                }
                $uploadedFiles = $request->getUploadedFiles();

                // handle single input with single file upload
                $uploadedFile = $uploadedFiles['foto'];
                if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                    $filename = moveUploadedFile($directory, $uploadedFile);
                    $produto->setFoto($filename);
                }

                if ($id_produto > 0) {
                    $regraProduto->alterar($produto);
                } else {
                    $produto->setIdUsuario(UsuarioBLL::pegarIdUsuarioAtual());
                    $produto->setIdLoja($loja->getId());
                    $id_produto = $regraProduto->inserir($produto);
                }
                $produto = $regraProduto->pegar($id_produto);

                if (array_key_exists("acao", $paramPost) && $paramPost["acao"] == "gravar-e-adicionar") {
                    $sucesso = "Produto adicionado com sucesso!";
                    $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/novo?sucesso=" . urlencode($sucesso);
                    return $response->withStatus(302)->withHeader('Location', $url);
                }

                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/" . $produto->getSlug();
                return $response->withStatus(302)->withHeader('Location', $url);

            } catch (Exception $e) {
                $args['erro'] = $e->getMessage();
                /** var PhpRenderer $rendererMain */
                $rendererMain = $app->getContainer()->get('view');
                $response = $rendererMain->render($response, 'header.php', $args);
                $produtoForm = new ProdutoFormControl($request, $response);
                $produtoForm->setProduto($produto);
                $response = $produtoForm->render($args);
                $response = $rendererMain->render($response, 'footer.php', $args);
                return $response;
            }
        });
    });
}