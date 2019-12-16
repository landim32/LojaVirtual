<?php

namespace Emagine\Produto;

use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Slim\Http\UploadedFile;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\LojaFreteBLL;
use Emagine\Endereco\Model\EnderecoInfo;
use Emagine\Produto\Model\LojaFreteInfo;
use Emagine\Endereco\Controls\EnderecoControl;
use Exception;

$app = EmagineApp::getApp();

if (LojaFreteBLL::usaFreteRoute() == true) {

    $app->get('/{loja}/fretes', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $regraFrete = new LojaFreteBLL();

        $loja = $regraLoja->pegarPorSlug($args['loja']);
        $fretes = $regraFrete->listar($loja->getId());

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/fretes");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['fretes'] = $fretes;
        $args['usuarioPerfil'] = $usuarioPerfil;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererLoja */
        $rendererLoja = $this->get('lojaView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererLoja->render($response, 'fretes.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/{slug_loja}/frete/inserir', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();

        $app->addJavascriptConteudo("$.cep.urlCep = '" . SITE_URL . "';\n");

        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/fretes");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['frete'] = new LojaFreteInfo();
        $args['usuarioPerfil'] = $usuarioPerfil;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererLoja */
        $rendererLoja = $this->get('lojaView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererLoja->render($response, 'frete-head.php', $args);
        $freteForm = new EnderecoControl($request, $response);
        $freteForm->setTemplate(EnderecoControl::TEMPLATE_BUSCA_HORIZONTAL);
        $freteForm->setFormGroupClasse(EnderecoControl::FORM_GROUP_LG);
        $freteForm->setExibeCep(false);
        $freteForm->setExibePosicao(false);
        $freteForm->setExibeComplemento(false);
        $response = $freteForm->render($args);
        $response = $rendererLoja->render($response, 'frete-foot.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/{slug_loja}/frete/{id_frete}', function (Request $request, Response $response, $args) use ($app) {

        $regraLoja = new LojaBLL();
        $regraFrete = new LojaFreteBLL();

        $app->addJavascriptConteudo("$.cep.urlCep = '" . SITE_URL . "';\n");

        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $frete = $regraFrete->pegar($args['id_frete']);

        $endereco = new EnderecoInfo();
        $endereco->setLogradouro($frete->getLogradouro());
        $endereco->setBairro($frete->getBairro());
        $endereco->setCidade($frete->getCidade());
        $endereco->setUf($frete->getUf());

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/categorias");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['frete'] = $frete;
        $args['usuarioPerfil'] = $usuarioPerfil;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererLoja */
        $rendererLoja = $this->get('lojaView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererLoja->render($response, 'frete-head.php', $args);
        $freteForm = new EnderecoControl($request, $response);
        $freteForm->setTemplate(EnderecoControl::TEMPLATE_BUSCA_HORIZONTAL);
        $freteForm->setFormGroupClasse(EnderecoControl::FORM_GROUP_LG);
        $freteForm->setExibeCep(false);
        $freteForm->setExibePosicao(false);
        $freteForm->setExibeComplemento(false);
        $freteForm->setEndereco($endereco);
        $response = $freteForm->render($args);
        $response = $rendererLoja->render($response, 'frete-foot.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/{slug_loja}/frete/excluir/{id_frete}', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        try {
            $regraFrete = new LojaFreteBLL();
            $regraFrete->excluir($args['id_frete']);
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/fretes";
            return $response->withStatus(302)->withHeader('Location', $url);
        } catch (Exception $e) {
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/fretes";
            $url .= "?erro=" . urlencode($e->getMessage());
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    });

    $app->post('/{slug_loja}/frete/gravar', function (Request $request, Response $response, $args) use ($app) {
        $frete = null;
        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);

        $args['app'] = $app;
        $args['loja'] = $loja;

        $regraFrete = new LojaFreteBLL();

        $postParam = $request->getParsedBody();
        $id_frete = intval($postParam['id_frete']);

        if ($id_frete > 0) {
            $frete = $regraFrete->pegar($id_frete);
        }
        $frete = $regraFrete->pegarDoPost($postParam, $frete);
        $frete->setIdLoja($loja->getId());
        try {

            if ($frete->getId() > 0) {
                $regraFrete->alterar($frete);
            } else {
                $regraFrete->inserir($frete);
            }

            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/fretes";
            return $response->withStatus(302)->withHeader('Location', $url);
        } catch (Exception $e) {

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/fretes");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['erro'] = $e->getMessage();
            $args['frete'] = $frete;
            $args['usuarioPerfil'] = $usuarioPerfil;

            $endereco = new EnderecoInfo();
            $endereco->setLogradouro($frete->getLogradouro());
            $endereco->setBairro($frete->getBairro());
            $endereco->setCidade($frete->getCidade());
            $endereco->setUf($frete->getUf());

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererLoja */
            $rendererLoja = $this->get('lojaView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererLoja->render($response, 'frete-head.php', $args);
            $freteForm = new EnderecoControl($request, $response);
            $freteForm->setTemplate(EnderecoControl::TEMPLATE_BUSCA_HORIZONTAL);
            $freteForm->setFormGroupClasse(EnderecoControl::FORM_GROUP_LG);
            $freteForm->setEndereco($endereco);
            $freteForm->setExibeCep(false);
            $freteForm->setExibePosicao(false);
            $freteForm->setExibeComplemento(false);
            $response = $freteForm->render($args);
            $response = $rendererLoja->render($response, 'frete-foot.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        }
    });
}