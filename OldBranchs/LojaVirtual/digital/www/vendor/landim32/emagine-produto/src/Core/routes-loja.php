<?php
namespace Emagine\Produto;

use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Produto\BLL\SeguimentoBLL;
use Exception;
use Slim\Http\UploadedFile;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\UsuarioLojaInfo;

if (LojaBLL::usaLojaRoute() == true) {

    $app = EmagineApp::getApp();

    $app->get('/lojas', function (Request $request, Response $response, $args) use ($app) {

        $queryParam = $request->getQueryParams();

        $regraLoja = new LojaBLL();
        $lojas = $regraLoja->listar();

        $args['app'] = $app;
        $args['lojas'] = $lojas;
        $args['usuarioPerfil'] = UsuarioPerfilFactory::create();
        if (array_key_exists("erro", $queryParam)) {
            $args['erro'] = $queryParam['erro'];
        }

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererLoja */
        $rendererLoja = $this->get('lojaView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererLoja->render($response, 'lojas.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->group('/loja', function () use ($app) {

        $app->get('/seleciona', function (Request $request, Response $response, $args) use ($app) {

            $queryParam = $request->getQueryParams();
            if (!array_key_exists("callback", $queryParam)) {
                throw new Exception("Url de 'callback' não informada.");
            }

            $usuario = UsuarioBLL::pegarUsuarioAtual();

            $args['app'] = $app;
            $args['callback'] = $queryParam['callback'];
            if (array_key_exists("erro", $queryParam)) {
                $args['erro'] = $queryParam['erro'];
            }
            $regraLoja = new LojaBLL();
            if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
                $lojas = $regraLoja->listar();
            }
            else {
                $lojas = $regraLoja->listarPorUsuario($usuario->getId());
            }
            $args['lojas'] = $lojas;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'loja-seleciona.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug_loja}/mudar', function (Request $request, Response $response, $args) use ($app) {
            $queryParam = $request->getQueryParams();
            $regraLoja = new LojaBLL();

            if (!array_key_exists("callback", $queryParam)) {
                throw new Exception("Url de 'callback' não informada.");
            }
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);

            $regraLoja->gravarAtual($loja);

            $url = sprintf($queryParam["callback"], $loja->getSlug());
            return $response->withStatus(302)->withHeader('Location', $url);

        });

        $app->get('/nova', function (Request $request, Response $response, $args) use ($app) {
            $regraSeguimento = new SeguimentoBLL();
            $seguimentos = $regraSeguimento->listar();

            $args['app'] = $app;
            $args['loja'] = new LojaInfo();
            $args['seguimentos'] = $seguimentos;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            $js = "$.cep.urlCep = '" . SITE_URL . "';\n";
            $app->addJavascriptConteudo($js);

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'loja-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug}/alterar', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $regraSeguimento = new SeguimentoBLL();

            $loja = $regraLoja->pegarPorSlug($args['slug']);
            $seguimentos = $regraSeguimento->listar();

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/loja/%s");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['seguimentos'] = $seguimentos;
            $args['usuarioPerfil'] = $usuarioPerfil;

            $js = "$.cep.urlCep = '" . SITE_URL . "';\n";
            $app->addJavascriptConteudo($js);

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'loja-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->post("", function (Request $request, Response $response, $args) use ($app) {
            $args['app'] = $app;

            $bll = new LojaBLL();

            $paramPost = $request->getParsedBody();

            $id_loja = intval($paramPost['id_loja']);
            $loja = null;
            if ($id_loja > 0) {
                $loja = $bll->pegar($id_loja);
            }

            $bll->pegarDoPost($paramPost, $loja);

            $directory = UPLOAD_PATH . '/loja';
            if (!file_exists($directory)) {
                @mkdir($directory, 755);
            }
            $uploadedFiles = $request->getUploadedFiles();

            // handle single input with single file upload
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $uploadedFiles['foto'];
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = moveUploadedFile($directory, $uploadedFile);
                $loja->setFoto($filename);
            }


            try {
                if ($id_loja > 0) {
                    $bll->alterar($loja);
                } else {
                    $id_loja = $bll->inserir($loja);
                }
                $loja = $bll->pegar($id_loja);

                $url = $app->getBaseUrl() . "/loja/" . $loja->getSlug();
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $regraSeguimento = new SeguimentoBLL();
                $seguimentos = $regraSeguimento->listar();

                $usuarioPerfil = UsuarioPerfilFactory::create();
                $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/loja/%s");
                if ($usuarioPerfil instanceof LojaPerfilBLL) {
                    $usuarioPerfil->setLoja($loja);
                }

                $args['erro'] = $e->getMessage();
                $args['loja'] = $loja;
                $args['seguimentos'] = $seguimentos;
                $args['usuarioPerfil'] = $usuarioPerfil;

                /** @var PhpRenderer $rendererMain */
                $rendererMain = $this->get('view');
                /** @var PhpRenderer $rendererLoja */
                $rendererLoja = $this->get('lojaView');

                $response = $rendererMain->render($response, 'header.php', $args);
                $response = $rendererLoja->render($response, 'loja-form.php', $args);
                $response = $rendererMain->render($response, 'footer.php', $args);
                return $response;
            }
        });

        $app->get('/{slug}', function (Request $request, Response $response, $args) use ($app) {

            $queryParam = $request->getQueryParams();

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['slug']);
            $lojasCopia = array();
            foreach ($regraLoja->listar() as $lojaCopia) {
                if ($loja->getId() != $lojaCopia->getId()) {
                    $lojasCopia[] = $lojaCopia;
                }
            }

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/loja/%s");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['usuario'] = UsuarioBLL::pegarUsuarioAtual();
            $args['loja'] = $loja;
            $args['lojasCopia'] = $lojasCopia;
            $args['usuarioPerfil'] = $usuarioPerfil;
            if (array_key_exists("erro", $queryParam)) {
                $args['erro'] = $queryParam['erro'];
            }
            if (array_key_exists("sucesso", $queryParam)) {
                $args['sucesso'] = $queryParam['sucesso'];
            }

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'loja.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug}/excluir', function (Request $request, Response $response, $args) use ($app) {
            $args['app'] = $app;
            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['slug']);
            try {
                $regraLoja->excluir($loja->getId());
                $url = $app->getBaseUrl() . "/lojas";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $url = $app->getBaseUrl() . "/loja/" . $loja->getSlug() .
                    "?erro=" . urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        });

        $app->post('/usuario/adicionar', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();

            $postParam = $request->getParsedBody();
            $id_loja = intval($postParam["id_loja"]);
            $id_usuario = intval($postParam["id_usuario"]);

            $loja = $regraLoja->pegar($id_loja);

            $usuario = new UsuarioLojaInfo();
            $usuario->setIdLoja($id_loja);
            $usuario->setIdUsuario($id_usuario);
            $loja->adicionarUsuario($usuario);

            try {
                $regraLoja->alterar($loja);
                $url = $app->getBaseUrl() . "/loja/" . $loja->getSlug();
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $url = $app->getBaseUrl() . "/loja/" . $loja->getSlug() . "?erro=". urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        });

        $app->get('/{loja}/usuario/excluir/{id_usuario}', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();

            $loja = $regraLoja->pegarPorSlug($args['loja']);
            $id_usuario = intval($args['id_usuario']);
            $loja->removerUsuario($id_usuario);

            $url = $app->getBaseUrl() . "/loja/" . $loja->getSlug();
            try {
                $regraLoja->alterar($loja);
                $url .= "?sucesso=" . urlencode("Usuário removido com sucesso.");
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $url .= "?erro=". urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        });

        $app->post('/copiar', function (Request $request, Response $response, $args) use ($app) {
            $postData = $request->getParsedBody();
            $id_origem = intval($postData['id_origem']);
            $id_destino = intval($postData['id_destino']);

            $regraLoja = new LojaBLL();
            $regraProduto = new ProdutoBLL();

            $origem = $regraLoja->pegar($id_origem);
            if (is_null($origem)) {
                throw new Exception("Loja de origem não encontrada.");
            }

            $args['app'] = $app;
            try {
                $destino = $regraLoja->pegar($id_destino);
                if (is_null($destino)) {
                    throw new Exception("Loja de destino não encontrada.");
                }
                $regraProduto->copiar($origem, $destino);
                $url = $app->getBaseUrl() . "/" . $origem->getSlug() . "/produtos";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $url = $app->getBaseUrl() . "/loja/" . $origem->getSlug();
                $url .= "?erro=" . urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        });
    });
}