<?php
namespace Emagine\Produto;

use Emagine\Login\Factory\UsuarioPerfilFactory;
use Exception;
use Slim\Http\UploadedFile;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\SeguimentoBLL;
use Emagine\Produto\Model\SeguimentoInfo;

$app = EmagineApp::getApp();

if (SeguimentoBLL::usaSeguimentoRoute() == true) {

    $app->get('/seguimentos', function (Request $request, Response $response, $args) use ($app) {
        $queryParam = $request->getQueryParams();

        $regraSeguimento = new SeguimentoBLL();
        $seguimentos = $regraSeguimento->listar();

        $args['app'] = $app;
        $args['seguimentos'] = $seguimentos;
        $args['usuarioPerfil'] = UsuarioPerfilFactory::create();
        if (array_key_exists("erro", $queryParam)) {
            $args['erro'] = $queryParam["erro"];
        }

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererLoja */
        $rendererLoja = $this->get('lojaView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererLoja->render($response, 'seguimentos.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->group("/seguimento", function () use ($app) {

        $app->get('/nova', function (Request $request, Response $response, $args) use ($app) {
            $regraSeguimento = new SeguimentoBLL();
            $seguimentos = $regraSeguimento->listar();

            $args['app'] = $app;
            $args['seguimento'] = new SeguimentoInfo();
            $args['seguimentos'] = $seguimentos;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'seguimento-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug}/alterar', function (Request $request, Response $response, $args) use ($app) {
            $regraSeguimento = new SeguimentoBLL();

            $seguimentos = $regraSeguimento->listar();
            $seguimento = $regraSeguimento->pegarPorSlug($args['slug']);

            $args['app'] = $app;
            $args['seguimento'] = $seguimento;
            $args['seguimentos'] = $seguimentos;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'seguimento-form.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/{slug}/excluir', function (Request $request, Response $response, $args) use ($app) {
            $regraSeguimento = new SeguimentoBLL();
            $seguimento = $regraSeguimento->pegarPorSlug($args['slug']);

            $args['app'] = $app;

            try {
                $regraSeguimento->excluir($seguimento->getId());
                $url = $app->getBaseUrl() . "/seguimentos";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $url = $app->getBaseUrl() . "/seguimentos";
                $url .= "?erro=" . urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        });

        $app->get('/{slug}', function (Request $request, Response $response, $args) use ($app) {
            $regraSeguimento = new SeguimentoBLL();
            $seguimento = $regraSeguimento->pegarPorSlug($args['slug']);

            $args['app'] = $app;
            $args['seguimento'] = $seguimento;
            $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'seguimento.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->post('', function (Request $request, Response $response, $args) use ($app) {
            $regraSeguimento = new SeguimentoBLL();

            $args['app'] = $app;

            $paramPost = $request->getParsedBody();

            $id_seguimento = intval($paramPost['id_seguimento']);
            $seguimento = null;
            if ($id_seguimento > 0) {
                $seguimento = $regraSeguimento->pegar($id_seguimento);
            }

            $regraSeguimento->pegarDoPost($paramPost, $seguimento);

            $directory = UPLOAD_PATH . '/seguimento';
            if (!file_exists($directory)) {
                @mkdir($directory, 755);
            }
            $uploadedFiles = $request->getUploadedFiles();

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $uploadedFiles['icone'];
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = moveUploadedFile($directory, $uploadedFile);
                $seguimento->setIcone($filename);
            }

            if ($id_seguimento > 0) {
                $regraSeguimento->alterar($seguimento);
            } else {
                $id_seguimento = $regraSeguimento->inserir($seguimento);
            }
            $seguimento = $regraSeguimento->pegar($id_seguimento);

            $url = $app->getBaseUrl() . "/seguimento/" . $seguimento->getSlug();
            return $response->withStatus(302)->withHeader('Location', $url);
        });
    });

}