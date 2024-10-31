<?php
namespace Emagine\Loja;

use Emagine\Banner\BLL\BannerBLL;
use Emagine\Banner\BLL\BannerPecaBLL;
use Emagine\Banner\Model\BannerFiltroInfo;
use Emagine\Pagamento\Factory\PagamentoFactory;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pagamento\Model\PagamentoItemInfo;
use Emagine\Pedido\BLL\PedidoHorarioBLL;
use Emagine\Pedido\Model\PedidoItemInfo;
use Emagine\Produto\BLL\SeguimentoBLL;
use Slim\Views\Twig;
use stdClass;
use Emagine\Base\EmagineApp;
use Emagine\Endereco\BLL\BairroBLL;
use Emagine\Endereco\BLL\CepBLL;
use Emagine\Endereco\BLL\EnderecoBLL;
use Emagine\Endereco\Controls\EnderecoControl;
use Emagine\Endereco\Model\EnderecoInfo;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Controls\UsuarioFormView;
use Emagine\Login\Model\UsuarioEnderecoInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\BLL\CategoriaBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Produto\Model\LojaInfo;
use Exception;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = EmagineApp::getApp();

$app->get('/', function (Request $request, Response $response, $args) use($app) {

    $regraLoja = new LojaBLL();
    $regraEndereco = new EnderecoBLL();
    $regraBanner = new BannerBLL();
    $regraPeca = new BannerPecaBLL();
    $regraSeguimento = new SeguimentoBLL();

    $raio = $regraLoja->getRaio();
    if (!($raio > 0)) {
        $raio = 100;
    }
    $banner = $regraBanner->pegarPorSlug("tela-inicial");

    $usuario = UsuarioBLL::pegarUsuarioAtual();
    $enderecoAtual = $regraEndereco->pegarAtual();
    if (!is_null($enderecoAtual)) {
        $urlSeguimento = $app->getBaseUrl() . "/%s/loja/busca";
    }
    else if (!is_null($usuario)) {
        if (count($usuario->listarEndereco()) > 0) {
            if (count($usuario->listarEndereco()) > 1) {
                $urlSeguimento = $app->getBaseUrl() . "/endereco/seleciona";
            }
            else {
                /** @var UsuarioEnderecoInfo $enderecoAtual */
                $enderecoAtual = array_values($usuario->listarEndereco())[0];
                $urlSeguimento = $app->getBaseUrl() . "/%s/loja/busca/" . $enderecoAtual->getId();
            }
        }
        else {
            //$urlSeguimento = $app->getBaseUrl() . "/%s/loja/busca-por-cep";
            $urlSeguimento = $app->getBaseUrl() . "/%s/busca-por-cep";
        }
    }
    else {
        //$urlSeguimento = $app->getBaseUrl() . "/%s/loja/busca-por-cep";
        $urlSeguimento = $app->getBaseUrl() . "/%s/busca-por-cep";
    }

    if (!is_null($banner)) {
        $filtro = new BannerFiltroInfo();
        $filtro->setIdBanner($banner->getId());
        $filtro->setOrdem(BannerFiltroInfo::ALEATORIO);

        if (!is_null($enderecoAtual)) {
            $filtro->setLatitude($enderecoAtual->getLatitude());
            $filtro->setLongitude($enderecoAtual->getLongitude());
            $filtro->setRaio($raio);
        }
        $pecas = $regraPeca->gerar($filtro);
    }
    if (!is_null($enderecoAtual)) {
        $seguimentos = $regraSeguimento->buscarPorPosicao($enderecoAtual->getLatitude(), $enderecoAtual->getLongitude(), $raio);
    }
    else {
        $seguimentos = $regraSeguimento->listar();
    }

    //$urlSeguimento = $app->getBaseUrl() . "/s/%s";

    $args['app'] = $app;
    $args['banner'] = $banner;
    if (isset($pecas)) {
        $args['pecas'] = $pecas;
    }
    $args['seguimentos'] = $seguimentos;
    $args['urlSeguimento'] = $urlSeguimento;
    $args['endereco'] = $enderecoAtual;
    $args['raio'] = $raio;

    /*
    if ($app->getTemaTipo() == EmagineApp::TWIG) {
        #@var Twig $renderer
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'seguimentos.html', $args);
        return $response;
    }
    */
    /** @var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header-simples.php', $args);
    $response = $renderer->render($response, 'seguimentos.php', $args);
    $response = $renderer->render($response, 'footer-simples.php', $args);
    return $response;
});

$app->post('/', function (Request $request, Response $response, $args) use($app) {
    $postParam = $request->getParsedBody();

    $regraLoja = new LojaBLL();

    $raio = intval($postParam['raio']);
    $regraLoja->setRaio($raio);

    $args['app'] = $app;

    $regraEndereco = new EnderecoBLL();
    $endereco = $regraEndereco->pegarAtual();
    if (!is_null($endereco)) {
        $url = $app->getBaseUrl();
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    else {
        $usuario = UsuarioBLL::pegarUsuarioAtual();
        if (!is_null($usuario)) {
            if (count($usuario->listarEndereco()) > 0) {
                if (count($usuario->listarEndereco()) > 1) {
                    $url = $app->getBaseUrl() . "/endereco/seleciona";
                    return $response->withStatus(302)->withHeader('Location', $url);
                }
                else {
                    /** @var UsuarioEnderecoInfo $endereco */
                    $endereco = array_values($usuario->listarEndereco())[0];
                    $url = $app->getBaseUrl() . "/loja/busca/" . $endereco->getId();
                    return $response->withStatus(302)->withHeader('Location', $url);
                }
            }
            else {
                //$url = $app->getBaseUrl() . "/loja/busca-por-cep";
                $url = $app->getBaseUrl() . "/busca-por-cep";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        }
        else {
            //$url = $app->getBaseUrl() . "/loja/busca-por-cep";
            $url = $app->getBaseUrl() . "/busca-por-cep";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    }
});

$app->get('/selecione-endereco', function (Request $request, Response $response, $args) use($app) {
    $args['app'] = $app;

    $usuario = UsuarioBLL::pegarUsuarioAtual();
    if (!is_null($usuario)) {
        if (count($usuario->listarEndereco()) > 0) {
            if (count($usuario->listarEndereco()) > 1) {
                $url = $app->getBaseUrl() . "/endereco/seleciona";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            else {
                /** @var UsuarioEnderecoInfo $endereco */
                $endereco = array_values($usuario->listarEndereco())[0];
                $url = $app->getBaseUrl() . "/loja/busca/" . $endereco->getId();
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        }
        else {
            //$url = $app->getBaseUrl() . "/loja/busca-por-cep";
            $url = $app->getBaseUrl() . "/busca-por-cep";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    }
    else {
        if (LojaBLL::usaBuscaLojaPorCep() == true) {

            $regraEndereco = new EnderecoBLL();
            $endereco = $regraEndereco->pegarAtual();
            if (!is_null($endereco)) {
                $url = $app->getBaseUrl() . "/loja/busca";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            else {
                //$url = $app->getBaseUrl() . "/loja/busca-por-cep";
                $url = $app->getBaseUrl() . "/busca-por-cep";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        } else {
            $regraLoja = new LojaBLL();
            $lojas = $regraLoja->listar();
            if (count($lojas) <= 0) {
                throw new Exception("Nenhuma loja cadastrada.");
            }
            if (count($lojas) > 1) {
                if (LojaBLL::usaLojaUnica() == true) {
                    /** @var LojaInfo $loja */
                    $loja = array_values($lojas)[0];
                    $url = $app->getBaseUrl() . "/" . $loja->getSlug();
                    return $response->withStatus(302)->withHeader('Location', $url);
                } else {
                    $url = $app->getBaseUrl() . "/loja/lista";
                    return $response->withStatus(302)->withHeader('Location', $url);
                }
            } else {
                /** @var LojaInfo $loja */
                $loja = array_values($lojas)[0];
                $url = $app->getBaseUrl() . "/" . $loja->getSlug();
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        }
    }
});

$app->group('/{slug_seguimento}/loja', function () use ($app) {

    /*
    $app->get('/lista', function (Request $request, Response $response, $args) use($app) {
        $regraLoja = new LojaBLL();
        $lojas = $regraLoja->listar();

        $args['app'] = $app;
        $args['lojas'] = $lojas;

        #@var PhpRenderer $renderer
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header-simples.php', $args);
        $response = $renderer->render($response, 'loja-lista.php', $args);
        $response = $renderer->render($response, 'footer-simples.php', $args);
        return $response;
    });
    */

    $app->get('/busca[/{id_endereco}]', function (Request $request, Response $response, $args) use($app) {
        $id_endereco = intval($args['id_endereco']);

        $regraBanner = new BannerBLL();
        $regraPeca = new BannerPecaBLL();
        $regraSeguimento = new SeguimentoBLL();
        $regraLoja = new LojaBLL();
        $regraEndereco = new EnderecoBLL();

        $endereco = $regraEndereco->pegarAtual();

        if ($id_endereco > 0) {
            $usuario = UsuarioBLL::pegarUsuarioAtual();
            if (!is_null($usuario)) {
                foreach ($usuario->listarEndereco() as $usuarioEndereco) {
                    if ($id_endereco == $usuarioEndereco->getId()) {
                        $endereco = $usuarioEndereco;
                        $regraEndereco->gravarAtual($endereco);
                        break;
                    }
                }
            }
        }
        if (is_null($endereco)) {
            $url = $app->getBaseUrl();
            return $response->withStatus(302)->withHeader('Location', $url);
        }

        $seguimento = $regraSeguimento->pegarPorSlug($args['slug_seguimento']);

        $banner = $regraBanner->pegarPorSlug("tela-inicial");
        //$usuario = UsuarioBLL::pegarUsuarioAtual();

        if (!is_null($banner)) {
            $filtro = new BannerFiltroInfo();
            $filtro->setIdBanner($banner->getId());
            $filtro->setOrdem(BannerFiltroInfo::ALEATORIO);
            $filtro->setLatitude($endereco->getLatitude());
            $filtro->setLongitude($endereco->getLongitude());
            $filtro->setRaio($regraLoja->getRaio());
            $pecas = $regraPeca->gerar($filtro);
        }
        $lojas = $regraLoja->buscarPorPosicao($endereco->getLatitude(), $endereco->getLongitude(), $regraLoja->getRaio(), $seguimento->getId());

        $args['app'] = $app;
        $args['endereco'] = $endereco;
        $args['lojas'] = $lojas;
        $args['banner'] = $banner;
        if (isset($pecas)) {
            $args['pecas'] = $pecas;
        }

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header-simples.php', $args);
        $response = $renderer->render($response, 'loja-busca.php', $args);
        $response = $renderer->render($response, 'footer-simples.php', $args);
        return $response;
    });
});

$app->group('/endereco', function () use ($app) {
    $app->get('/seleciona', function (Request $request, Response $response, $args) use($app) {

        $regraEndereco = new EnderecoBLL();
        $regraEndereco->limparAtual();

        $usuario = UsuarioBLL::pegarUsuarioAtual();
        if (!is_null($usuario)) {
            $args['app'] = $app;
            $args['usuario'] = $usuario;

            /** @var PhpRenderer $renderer */
            $renderer = $this->get('view');
            $response = $renderer->render($response, 'header-simples.php', $args);
            $response = $renderer->render($response, 'endereco-seleciona.php', $args);
            $response = $renderer->render($response, 'footer-simples.php', $args);
            return $response;
        }
        else {
            $url = $app->getBaseUrl();
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    });

    $app->get('/busca/{id_endereco}', function (Request $request, Response $response, $args) use($app) {

        $regraLoja = new LojaBLL();
        $regraBanner = new BannerBLL();
        $regraPeca = new BannerPecaBLL();
        $regraEndereco = new EnderecoBLL();

        $usuario = UsuarioBLL::pegarUsuarioAtual();
        if (is_null($usuario)) {
            $url = $app->getBaseUrl();
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        $id_endereco = intval($args['id_endereco']);
        $endereco = null;
        foreach ($usuario->listarEndereco() as $usuarioEndereco) {
            if ($id_endereco == $usuarioEndereco->getId()) {
                $endereco = $usuarioEndereco;
                $regraEndereco->gravarAtual($endereco);
                //var_dump($endereco);
                break;
            }
        }

        $banner = $regraBanner->pegarPorSlug("tela-inicial");
        //$usuario = UsuarioBLL::pegarUsuarioAtual();

        if (!is_null($banner)) {
            $filtro = new BannerFiltroInfo();
            $filtro->setIdBanner($banner->getId());
            $filtro->setOrdem(BannerFiltroInfo::ALEATORIO);
            $filtro->setLatitude($endereco->getLatitude());
            $filtro->setLongitude($endereco->getLongitude());
            $filtro->setRaio($regraLoja->getRaio());

            $pecas = $regraPeca->gerar($filtro);
        }

        $lojas = $regraLoja->buscarPorPosicao($endereco->getLatitude(), $endereco->getLongitude(), $regraLoja->getRaio());

        $args['app'] = $app;
        $args['endereco'] = $endereco;
        $args['lojas'] = $lojas;
        $args['banner'] = $banner;
        if (isset($pecas)) {
            $args['pecas'] = $pecas;
        }

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header-simples.php', $args);
        $response = $renderer->render($response, 'loja-busca.php', $args);
        $response = $renderer->render($response, 'footer-simples.php', $args);
        return $response;
    });
});

$app->get('/busca-por-cep', function (Request $request, Response $response, $args) use($app) {
    $args['app'] = $app;
    /* @var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header-simples.php', $args);
    $response = $renderer->render($response, 'cep-busca.php', $args);
    $response = $renderer->render($response, 'footer-simples.php', $args);
    return $response;
});

$app->post('/busca-por-cep', function (Request $request, Response $response, $args) use($app) {
    $postParam = $request->getParsedBody();

    $args['app'] = $app;
    $args['cep'] = $postParam['cep'];

    $endereco = null;
    if (array_key_exists("cep", $postParam)) {
        $regraCep = new CepBLL();
        $endereco = $regraCep->pegarPorCep($postParam["cep"]);
        $regraEndereco = new EnderecoBLL();
        $regraEndereco->gravarAtual($endereco);
    }

    $app->addJavascriptConteudo("$.cep.urlCep = '" . SITE_URL . "';\n");

    /** @var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header-simples.php', $args);
    $response = $renderer->render($response, 'endereco-head.php', $args);

    $enderecoCtrl = new EnderecoControl($request, $response);
    $enderecoCtrl->setFormGroupClasse(EnderecoControl::FORM_GROUP_LG);
    $enderecoCtrl->setTemplate(EnderecoControl::TEMPLATE_BUSCA_HORIZONTAL);
    $enderecoCtrl->setExibeCep(false);
    $enderecoCtrl->setExibePosicao(false);
    $enderecoCtrl->setLogradouroEditavel(false);
    $enderecoCtrl->setBairroEditavel(false);
    $enderecoCtrl->setCidadeEditavel(false);
    $enderecoCtrl->setUfEditavel(false);
    $enderecoCtrl->setPosicaoEditavel(false);
    $enderecoCtrl->setEndereco($endereco);
    $response = $enderecoCtrl->render($args);

    $response = $renderer->render($response, 'endereco-foot.php', $args);
    $response = $renderer->render($response, 'footer-simples.php', $args);
    return $response;
});

$app->get('/busca-por-endereco', function (Request $request, Response $response, $args) use($app) {
    $args['app'] = $app;

    $app->addJavascriptConteudo("$.cep.urlCep = '" . SITE_URL . "';\n");

    /** @var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header-simples.php', $args);
    $response = $renderer->render($response, 'endereco-head.php', $args);

    $enderecoCtrl = new EnderecoControl($request, $response);
    $enderecoCtrl->setFormGroupClasse(EnderecoControl::FORM_GROUP_LG);
    $enderecoCtrl->setTemplate(EnderecoControl::TEMPLATE_BUSCA_HORIZONTAL);
    $enderecoCtrl->setExibeCep(false);
    $enderecoCtrl->setExibePosicao(false);
    $enderecoCtrl->setLogradouroEditavel(true);
    $enderecoCtrl->setBairroEditavel(true);
    $enderecoCtrl->setCidadeEditavel(true);
    $enderecoCtrl->setUfEditavel(true);
    $response = $enderecoCtrl->render($args);

    $response = $renderer->render($response, 'endereco-foot.php', $args);
    $response = $renderer->render($response, 'footer-simples.php', $args);
    return $response;
});

$app->post('/busca-por-endereco', function (Request $request, Response $response, $args) use($app) {

    $postParam = $request->getParsedBody();

    if (!array_key_exists("cep", $postParam)) {
        throw new Exception("CEP não informado.");
    }

    $args['app'] = $app;

    $regraCep = new CepBLL();
    $endereco = $regraCep->pegarPorCep($postParam["cep"]);
    $endereco->setComplemento($postParam['complemento']);
    $endereco->setNumero($postParam['numero']);

    $regraUsuario = new UsuarioBLL();
    if ($regraUsuario->estaLogado()) {
        $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        $usuario = $regraUsuario->pegar($id_usuario);
        $usuarioEndereco = new UsuarioEnderecoInfo();
        $usuarioEndereco->clonarDe($endereco);
        $usuario->adicionarEndereco($usuarioEndereco);
        $regraUsuario->alterar($usuario);
    }

    //$args['endereco'] = $endereco;

    $regraEndereco = new EnderecoBLL();
    $regraEndereco->gravarAtual($endereco);

    /*
    $regraLoja = new LojaBLL();
    $lojas = $regraLoja->buscarPorPosicao($endereco->getLatitude(), $endereco->getLongitude());
    $args['lojas'] = $lojas;
    */

    /** @var PhpRenderer $renderer */
    /*
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header-simples.php', $args);
    $response = $renderer->render($response, 'loja-busca.php', $args);
    $response = $renderer->render($response, 'footer-simples.php', $args);
    return $response;
    */
    $url = $app->getBaseUrl();
    return $response->withStatus(302)->withHeader('Location', $url);
});

$app->group('/{loja_slug}', function () use ($app) {

    $app->get('', function (Request $request, Response $response, $args) use ($app) {

        $regraBanner = new BannerBLL();
        $regraPeca = new BannerPecaBLL();
        $regraLoja = new LojaBLL();
        $regraProduto = new ProdutoBLL();

        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
        $usuario = UsuarioBLL::getUsuarioAtual();
        $produtos = $regraProduto->listar($loja->getId());

        $queryParams = $request->getQueryParams();
        $pg = intval($queryParams['pg']);
        if (!($pg > 0)) $pg = 1;
        $produtosResultado = array_slice($produtos, ($pg - 1) * MAX_PAGE_COUNT, MAX_PAGE_COUNT);

        $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "?pg=%s";
        $paginacao = admin_pagination((count($produtos) / MAX_PAGE_COUNT), $url, $pg);

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        $banner = $regraBanner->pegarPorSlug("tela-inicial");

        if (isset($banner)) {
            $filtro = new BannerFiltroInfo();
            $filtro->setIdBanner($banner->getId());
            $filtro->setOrdem(BannerFiltroInfo::ALEATORIO);
            $filtro->setLatitude($endereco->getLatitude());
            $filtro->setLongitude($endereco->getLongitude());
            $filtro->setRaio($regraLoja->getRaio());

            $pecas = $regraPeca->gerar($filtro);
        }

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = $usuario;
        $args['produtos'] = $produtosResultado;
        $args['endereco'] = $endereco;
        $args['banner'] = $banner;
        if (isset($pecas)) {
            $args['pecas'] = $pecas;
        }
        $args['paginacao'] = $paginacao;

        $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
        $app->addJavascriptConteudo($str);

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header.php', $args);
        $response = $renderer->render($response, 'home.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/enderecos', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);

        $queryParam = $request->getQueryParams();

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = UsuarioBLL::pegarUsuarioAtual();
        $args['endereco'] = $endereco;
        if (array_key_exists("erro", $queryParam)) {
            $args['erro'] = $queryParam['erro'];
        }

        $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
        $app->addJavascriptConteudo($str);

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');

        $response = $renderer->render($response, 'header.php', $args);
        $response = $renderer->render($response, 'endereco-lista.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/endereco/novo', function (Request $request, Response $response, $args) use ($app) {

        $app->addJavascriptConteudo("$.cep.urlCep = '" . SITE_URL . "';\n");

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = UsuarioBLL::pegarUsuarioAtual();
        $args['endereco'] = $endereco;

        $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
        $app->addJavascriptConteudo($str);

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header.php', $args);
        $response = $renderer->render($response, 'endereco-novo-head.php', $args);

        $enderecoCtrl = new EnderecoControl($request, $response);
        $enderecoCtrl->setFormGroupClasse(EnderecoControl::FORM_GROUP_LG);
        $enderecoCtrl->setTemplate(EnderecoControl::TEMPLATE_FORM_HORIZONTAL);
        $enderecoCtrl->setExibeCep(true);
        $enderecoCtrl->setExibePosicao(true);
        $enderecoCtrl->setLogradouroEditavel(false);
        $enderecoCtrl->setBairroEditavel(false);
        $enderecoCtrl->setCidadeEditavel(false);
        $enderecoCtrl->setUfEditavel(false);
        $enderecoCtrl->setPosicaoEditavel(false);

        $response = $enderecoCtrl->render($args);
        $response = $renderer->render($response, 'endereco-novo-foot.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });

    $app->post('/endereco/novo', function (Request $request, Response $response, $args) use ($app) {

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);

        try {
            $postData = $request->getParsedBody();

            $regraUsuario = new UsuarioBLL();
            $regraCep = new CepBLL();

            $endereco = $regraCep->pegarPorCep($postData['cep']);
            $endereco->setComplemento($postData['complemento']);
            $endereco->setNumero($postData['numero']);
            $usuarioEndereco = new UsuarioEnderecoInfo();
            $usuarioEndereco->clonarDe($endereco);

            $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
            $usuario = $regraUsuario->pegar($id_usuario);
            $usuario->adicionarEndereco($usuarioEndereco);

            $regraUsuario->alterar($usuario);

            $regraEndereco = new EnderecoBLL();
            $regraEndereco->gravarAtual($endereco);

            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/enderecos";
            return $response->withStatus(302)->withHeader('Location', $url);
        } catch (Exception $e) {
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/enderecos";
            $url .= "?erro=" . urlencode($e->getMessage());
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    });

    $app->get('/endereco/excluir/{id_endereco}', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
        try {
            $regraUsuario = new UsuarioBLL();
            $regraUsuario->excluirEndereco($args['id_endereco']);
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/enderecos";
            return $response->withStatus(302)->withHeader('Location', $url);
        } catch (Exception $e) {
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/enderecos";
            $url .= "?erro=" . urlencode($e->getMessage());
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    });

    $app->get('/carrinho', function (Request $request, Response $response, $args) use ($app) {

        $regraLoja = new LojaBLL();
        //$regraBairro = new BairroBLL();

        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
        $usuario = UsuarioBLL::getUsuarioAtual();
        if (is_null($usuario)) {
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
            return $response->withStatus(302)->withHeader('Location', $url);
        }

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        /*
        $bairro = $regraBairro->pegarPorNome($usuario->getUf(), $usuario->getCidade(), $usuario->getBairro());
        if (!is_null($bairro)) {
            $localidade = array();
            $localidade[] = $usuario->getLogradouro();
            if (!isNullOrEmpty($usuario->getComplemento())) {
                $localidade[] = $usuario->getComplemento();
            }
            if (!isNullOrEmpty($usuario->getNumero())) {
                $localidade[] = $usuario->getNumero();
            }
            $localidade[] = $usuario->getBairro();
            $localidade[] = $usuario->getCidade();
            $localidade[] = $usuario->getUf();
            $args['enderecoEntrega'] = implode(", ", $localidade);
            $args['valorFrete'] = $bairro->getValorFrete();

            $str  = "$.carrinho.valorFrete = " . number_format($bairro->getValorFrete(), 2, ".", "") . ";\n";
            $str .= "$.carrinho.idLoja = " . $loja->getId() . ";\n";
            $app->addJavascriptConteudo($str);

        }
        else {
            $args["erro"] = "Endereço não pode ser encontrado.";
        }
        */

        //$args['valorFrete'] = $bairro->getValorFrete();

        //$str  = "$.carrinho.valorFrete = " . number_format($bairro->getValorFrete(), 2, ".", "") . ";\n";
        $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
        $str .= "$.carrinho.valorMinimo = " . $loja->getValorMinimo() . ";\n";
        $str .= "$.carrinho.valorMinimoStr = '" . $loja->getValorMinimoStr() . "';\n";
        $app->addJavascriptConteudo($str);

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = $usuario;
        $args['endereco'] = $endereco;

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header.php', $args);
        //$response = $renderer->render($response, 'carrinho.php', $args);
        $response = $renderer->render($response, 'pedido-carrinho.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });

    $app->group('/pedido', function () use ($app) {
        $app->get('/entrega[/{id_endereco}]', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
            $usuario = UsuarioBLL::getUsuarioAtual();
            if (is_null($usuario)) {
                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
            $app->addJavascriptConteudo($str);

            $regraEndereco = new EnderecoBLL();
            $id_endereco = intval($args['id_endereco']);
            $endereco = null;
            if ($id_endereco > 0) {
                foreach ($usuario->listarEndereco() as $end) {
                    if ($end->getId() == $id_endereco) {
                        $regraEndereco->gravarAtual($end);
                        $endereco = $end;
                        break;
                    }
                }
            }
            if (is_null($endereco)) {
                $endereco = $regraEndereco->pegarAtual();
            }
            //var_dump($endereco);

            $regraHorario = new PedidoHorarioBLL();
            $horarios = $regraHorario->listar($loja->getId());

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['usuario'] = $usuario;
            $args['endereco'] = $endereco;
            $args['horarios'] = $horarios;

            if (count($usuario->listarEndereco()) > 0) {
                /** @var EnderecoInfo $endereco */
                //$endereco = array_values($usuario->listarEndereco())[0];
                $args['endereco'] = $endereco;
                $args['enderecos'] = $usuario->listarEndereco();

                /** @var PhpRenderer $renderer */
                $renderer = $this->get('view');
                $response = $renderer->render($response, 'header.php', $args);
                $response = $renderer->render($response, 'pedido-entrega.php', $args);
                $response = $renderer->render($response, 'footer.php', $args);
                return $response;
            }
            else {
                /** @var PhpRenderer $renderer */
                $renderer = $this->get('view');
                $response = $renderer->render($response, 'header.php', $args);
                $response = $renderer->render($response, 'pedido-endereco-head.php', $args);

                $enderecoCtrl = new EnderecoControl($request, $response);
                $enderecoCtrl->setFormGroupClasse(EnderecoControl::FORM_GROUP_LG);
                $enderecoCtrl->setTemplate(EnderecoControl::TEMPLATE_FORM_HORIZONTAL);
                $enderecoCtrl->setExibeCep(true);
                $enderecoCtrl->setExibePosicao(true);
                $enderecoCtrl->setLogradouroEditavel(false);
                $enderecoCtrl->setBairroEditavel(false);
                $enderecoCtrl->setCidadeEditavel(false);
                $enderecoCtrl->setUfEditavel(false);
                $enderecoCtrl->setPosicaoEditavel(false);

                $response = $enderecoCtrl->render($args);

                $response = $renderer->render($response, 'pedido-endereco-foot.php', $args);
                $response = $renderer->render($response, 'footer.php', $args);
                return $response;
            }
        });

        $app->post('/entrega', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['loja_slug']);

            $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
            $app->addJavascriptConteudo($str);

            $usuario = UsuarioBLL::getUsuarioAtual();
            if (is_null($usuario)) {
                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
                return $response->withStatus(302)->withHeader('Location', $url);
            }

            $postData = $request->getParsedBody();

            $regraCep = new CepBLL();
            $endereco = $regraCep->pegarPorCep($postData['cep']);
            $endereco->setComplemento($postData['complemento']);
            $endereco->setNumero($postData['numero']);

            try {
                $usuarioEndereco = new UsuarioEnderecoInfo();
                $usuarioEndereco->clonarDe($endereco);
                $usuario->adicionarEndereco($usuarioEndereco);

                $regraUsuario = new UsuarioBLL();
                $regraUsuario->alterar($usuario);

                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedido/pagamento/entrega";
                return $response->withStatus(302)->withHeader('Location', $url);
            } catch (Exception $e) {

                $args['app'] = $app;
                $args['loja'] = $loja;
                $args['usuario'] = $usuario;
                $args['endereco'] = $endereco;
                $args['erro'] = $e->getMessage();

                /** @var PhpRenderer $renderer */
                $renderer = $this->get('view');
                $response = $renderer->render($response, 'header.php', $args);
                $response = $renderer->render($response, 'pedido-endereco-head.php', $args);

                $enderecoCtrl = new EnderecoControl($request, $response);
                $enderecoCtrl->setFormGroupClasse(EnderecoControl::FORM_GROUP_LG);
                $enderecoCtrl->setTemplate(EnderecoControl::TEMPLATE_FORM_HORIZONTAL);
                $enderecoCtrl->setExibeCep(true);
                $enderecoCtrl->setExibePosicao(true);
                $enderecoCtrl->setLogradouroEditavel(false);
                $enderecoCtrl->setBairroEditavel(false);
                $enderecoCtrl->setCidadeEditavel(false);
                $enderecoCtrl->setUfEditavel(false);
                $enderecoCtrl->setPosicaoEditavel(false);
                $enderecoCtrl->setEndereco($endereco);

                $response = $enderecoCtrl->render($args);

                $response = $renderer->render($response, 'pedido-endereco-foot.php', $args);
                $response = $renderer->render($response, 'footer.php', $args);
                return $response;
            }
        });

        $app->get('/retirada', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['loja_slug']);

            $usuario = UsuarioBLL::getUsuarioAtual();
            if (is_null($usuario)) {
                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
                return $response->withStatus(302)->withHeader('Location', $url);
            }

            $regraEndereco = new EnderecoBLL();
            $endereco = $regraEndereco->pegarAtual();

            $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
            $app->addJavascriptConteudo($str);

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['usuario'] = $usuario;
            $args['endereco'] = $endereco;

            /** @var PhpRenderer $renderer */
            $renderer = $this->get('view');
            $response = $renderer->render($response, 'header.php', $args);
            $response = $renderer->render($response, 'pedido-retirada.php', $args);
            $response = $renderer->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/pagamento[/{metodo}]', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
            $usuario = UsuarioBLL::getUsuarioAtual();
            if (is_null($usuario)) {
                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
            $app->addJavascriptConteudo($str);

            $regraEndereco = new EnderecoBLL();
            $endereco = $regraEndereco->pegarAtual();

            $pagamento = new PagamentoInfo();
            if ($loja->getAceitaCreditoOnline() == true) {
                $pagamento->setCodTipo(PagamentoInfo::CREDITO_ONLINE);
            }
            elseif ($loja->getAceitaDebitoOnline() == true) {
                $pagamento->setCodTipo(PagamentoInfo::DEBITO_ONLINE);
            }
            elseif ($loja->getAceitaDinheiro() == true) {
                $pagamento->setCodTipo(PagamentoInfo::DINHEIRO);
            }
            elseif ($loja->getAceitaCartaoOffline() == true) {
                $pagamento->setCodTipo(PagamentoInfo::CARTAO_OFFLINE);
            }
            elseif ($loja->getAceitaBoleto() == true) {
                $pagamento->setCodTipo(PagamentoInfo::BOLETO);
            }
            else {
                throw new Exception("Nenhuma forma de pagamento disponível nessa loja.");
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['usuario'] = $usuario;
            $args['pagamento'] = $pagamento;
            $args['endereco'] = $endereco;

            /** @var PhpRenderer $renderer */
            $renderer = $this->get('view');
            $response = $renderer->render($response, 'header.php', $args);
            $response = $renderer->render($response, 'pedido-pagamento.php', $args);
            $response = $renderer->render($response, 'footer.php', $args);
            return $response;
        });

        $app->post('/pagamento[/{metodo}]', function (Request $request, Response $response, $args) use ($app) {
            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
            $usuario = UsuarioBLL::getUsuarioAtual();
            if (is_null($usuario)) {
                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
                return $response->withStatus(302)->withHeader('Location', $url);
            }

            $postData = $request->getParsedBody();

            $pedido = new PedidoInfo();
            $pedido->setIdLoja($loja->getId());
            $pedido->setIdUsuario($usuario->getId());
            if ($args['metodo'] == "entrega") {
                $pedido->setCodEntrega(PedidoInfo::ENTREGAR);
            }
            else {
                $pedido->setCodEntrega(PedidoInfo::RETIRAR_NA_LOJA);
            }
            $produtos = json_decode($postData['pedido']);
            foreach ($produtos as $produto) {
                $item = new PedidoItemInfo();
                $item->setIdProduto($produto->id);
                $item->setQuantidade($produto->quantidade);
                $pedido->adicionarItem($item);
            }

            $pagamento = new PagamentoInfo();
            $pagamento->setIdUsuario($usuario->getId());
            $pagamento->setDataVencimento(date("Y-m-d", strtotime('+3 days')));
            $regraPagamento = PagamentoFactory::create();
            $pagamento = $regraPagamento->pegarDoPost($postData, $pagamento);
            $pedido->setTrocoPara($pagamento->getTrocoPara());

            if ($pedido->getCodEntrega() == PedidoInfo::ENTREGAR) {
                $endereco = null;
                if (count($usuario->listarEndereco()) == 1) {
                    /** @var UsuarioEnderecoInfo $endereco */
                    $endereco = array_values($usuario->listarEndereco())[0];
                }
                elseif (count($usuario->listarEndereco()) > 1) {
                    $id_endereco = intval($postData["id_endereco"]);
                    foreach ($usuario->listarEndereco() as $enderecoInfo) {
                        if ($enderecoInfo->getId() == $id_endereco) {
                            $endereco = $enderecoInfo;
                            break;
                        }
                    }
                }
                if (is_null($endereco)) {
                    throw new Exception("Nenhum endereço informado para entrega.");
                }
                $pedido->setCep($endereco->getCep());
                $pedido->setLogradouro($endereco->getLogradouro());
                $pedido->setComplemento($endereco->getComplemento());
                $pedido->setNumero($endereco->getNumero());
                $pedido->setBairro($endereco->getBairro());
                $pedido->setCidade($endereco->getCidade());
                $pedido->setUf($endereco->getUf());
                $pedido->setLatitude($endereco->getLatitude());
                $pedido->setLongitude($endereco->getLongitude());
            }

            foreach ($produtos as $produto) {
                $valor = floatval($produto->valor);
                $valorPromocao = floatval($produto->valor_promocao);
                if ($valorPromocao > 0 && $valorPromocao < $valor) {
                    $valor = $valorPromocao;
                }
                $item = new PagamentoItemInfo();
                $item->setDescricao($produto->nome);
                $item->setValor($valor);
                $item->setQuantidade(intval($produto->quantidade));
                $pagamento->adicionarItem($item);
            }

            $id_pagamento = $regraPagamento->inserir($pagamento);
            $pagamento->setId($id_pagamento);
            $regraPagamento->pagar($pagamento);

            try {

                $pagamento = $regraPagamento->pegar($id_pagamento);
                $pedido->setIdPagamento($pagamento->getId());
                switch ($pagamento->getCodTipo()) {
                    case PagamentoInfo::CREDITO_ONLINE:
                        $pedido->setCodPagamento(PedidoInfo::CREDITO_ONLINE);
                        break;
                    case PagamentoInfo::DEBITO_ONLINE:
                        $pedido->setCodPagamento(PedidoInfo::DEBITO_ONLINE);
                        break;
                    case PagamentoInfo::CARTAO_OFFLINE:
                        $pedido->setCodPagamento(PedidoInfo::CARTAO_OFFLINE);
                        break;
                    case PagamentoInfo::BOLETO:
                        $pedido->setCodPagamento(PedidoInfo::BOLETO);
                        break;
                    case PagamentoInfo::DINHEIRO:
                        $pedido->setCodPagamento(PedidoInfo::DINHEIRO);
                        break;
                }

                if ($pagamento->getCodSituacao() == PagamentoInfo::SITUACAO_PAGO) {
                    $pedido->setCodSituacao(PedidoInfo::PENDENTE);
                }
                else {
                    $pedido->setCodSituacao(PedidoInfo::AGUARDANDO_PAGAMENTO);
                }

                $regraPedido = new PedidoBLL();
                $id_pedido = $regraPedido->inserir($pedido);

                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedido/sucesso/" . $id_pedido;
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $args['app'] = $app;
                $args['loja'] = $loja;
                $args['usuario'] = $usuario;
                $args['pagamento'] = $pagamento;
                $args['bandeiras'] = $regraPagamento->listarBandeira();
                $args['erro'] = $e->getMessage();

                $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
                $app->addJavascriptConteudo($str);

                /** @var PhpRenderer $renderer */
                $renderer = $this->get('view');
                $response = $renderer->render($response, 'header.php', $args);
                $response = $renderer->render($response, 'pedido-pagamento.php', $args);
                $response = $renderer->render($response, 'footer.php', $args);
                return $response;
            }
        });

        $app->get('/sucesso/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['loja_slug']);

            $usuario = UsuarioBLL::getUsuarioAtual();
            if (is_null($usuario)) {
                $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
                return $response->withStatus(302)->withHeader('Location', $url);
            }

            $regraPedido = new PedidoBLL();
            $pedido = $regraPedido->pegar(intval($args["id_pedido"]));

            $regraPagamento = PagamentoFactory::create();
            $pagamento = $regraPagamento->pegar($pedido->getIdPagamento());

            $regraEndereco = new EnderecoBLL();
            $endereco = $regraEndereco->pegarAtual();

            $str  = "$(document).ready(function() {\n";
            $str .= "\t$.carrinho.idLoja = " . $loja->getId() . ";\n";
            $str .= "\t$.carrinho.limpar();\n";
            $str .= "\t$.carrinho.atualizar();\n";
            $str .= "});\n";
            $app->addJavascriptConteudo($str);

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['usuario'] = $usuario;
            $args['pedido'] = $pedido;
            $args['pagamento'] = $pagamento;
            $args['endereco'] = $endereco;

            /** @var PhpRenderer $renderer */
            $renderer = $this->get('view');
            $response = $renderer->render($response, 'header.php', $args);
            $response = $renderer->render($response, 'pedido-sucesso.php', $args);
            $response = $renderer->render($response, 'footer.php', $args);
            return $response;
        });
    });

    $app->get('/login', function (Request $request, Response $response, $args) use ($app) {
        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        $args['endereco'] = $endereco;

        return exibirLogin($app, $request, $response, $args);
    });

    $app->post('/login', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['urlResetar'] = $app->getBaseUrl() . "/" . $loja->getSlug() . "/resetar-senha";
        $args['urlCadastro'] = $app->getBaseUrl() . "/" . $loja->getSlug() . "/cadastro";
        $post = $request->getParsedBody();

        try {
            $regraUsuario = new UsuarioBLL();
            $regraUsuario->logar($post["email"], $post["senha"]);
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/carrinho";
            return $response->withStatus(302)->withHeader('Location', $url);
        } catch (Exception $e) {

            $args['error'] = $e->getMessage();
            return exibirLogin($app, $request, $response, $args);
        }
    });

    $app->get('/logoff', function (Request $request, Response $response, $args) use ($app) {
        $regraUsuario = new UsuarioBLL();
        $regraUsuario->logout();
        $url = $app->getBaseUrl() . "/" . $args['loja_slug'];
        return $response->withStatus(302)->withHeader('Location', $url);
    });

    $app->get('/cadastro', function (Request $request, Response $response, $args) use ($app) {

        $regraUsuario = new UsuarioBLL();
        if ($regraUsuario->estaLogado()) {
            $url = $app->getBaseUrl() . "/" . $args['loja_slug'] . "/alterar-meus-dados";
            return $response->withStatus(302)->withHeader('Location', $url);
        }

        $app->addJavascriptConteudo("$.cep.urlCep = '" . SITE_URL . "';\n");

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = new UsuarioInfo();
        $args['endereco'] = $endereco;

        /** var PhpRenderer $rendererLoja */
        $rendererLoja = $app->getContainer()->get('view');
        $response = $rendererLoja->render($response, 'header.php', $args);
        $response = $rendererLoja->render($response, 'usuario-novo-head.php', $args);

        $enderecoCtrl = new EnderecoControl($request, $response);
        $enderecoCtrl->setFormGroupClasse(EnderecoControl::FORM_GROUP_LG);
        $enderecoCtrl->setTemplate(EnderecoControl::TEMPLATE_FORM_HORIZONTAL);
        $enderecoCtrl->setExibeCep(true);
        $enderecoCtrl->setExibePosicao(true);
        $enderecoCtrl->setLogradouroEditavel(false);
        $enderecoCtrl->setBairroEditavel(false);
        $enderecoCtrl->setCidadeEditavel(false);
        $enderecoCtrl->setUfEditavel(false);
        $enderecoCtrl->setPosicaoEditavel(false);
        $enderecoCtrl->setEndereco($endereco);
        $response = $enderecoCtrl->render($args);
        $response = $rendererLoja->render($response, 'usuario-novo-foot.php', $args);
        $response = $rendererLoja->render($response, 'footer.php', $args);
        return $response;
    });

    $app->post('/cadastro', function (Request $request, Response $response, $args) use ($app) {

        $postData = $request->getParsedBody();

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);

        $regraUsuario = new UsuarioBLL();
        $usuario = $regraUsuario->pegarDoPost($postData);

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = $usuario;
        $args['endereco'] = $regraEndereco->pegarAtual();

        try {
            if ($postData['senha'] != $postData['confirma']) {
                throw new Exception("A senha não está batendo.");
            }
            if (!array_key_exists("cep", $postData)) {
                throw new Exception("Preencha o CEP.");
            }

            $regraCep = new CepBLL();
            $endereco = $regraCep->pegarPorCep($postData['cep']);
            $endereco->setComplemento($postData['complemento']);
            $endereco->setNumero($postData['numero']);

            $usuarioEndereco = new UsuarioEnderecoInfo();
            $usuarioEndereco->clonarDe($endereco);
            $usuario->adicionarEndereco($usuarioEndereco);


            $usuario->setSenha($postData['senha']);
            $usuario->setCodSituacao(UsuarioInfo::ATIVO);
            $id_usuario = $regraUsuario->inserir($usuario);
            $usuario = $regraUsuario->pegar($id_usuario);
            $args["usuario"] = $usuario;

            $regraUsuario->gravarCookie($usuario->getId());
            $urlPedido = $app->getBaseUrl() . "/" . $loja->getSlug() . "/carrinho";
            return $response->withStatus(302)->withHeader('Location', $urlPedido);
        } catch (Exception $e) {
            $args['erro'] = $e->getMessage();

            /** var PhpRenderer $renderer */
            $renderer = $app->getContainer()->get('view');
            $response = $renderer->render($response, 'header.php', $args);
            $response = $renderer->render($response, 'usuario-novo-head.php', $args);

            $enderecoCtrl = new EnderecoControl($request, $response);
            $enderecoCtrl->setFormGroupClasse(EnderecoControl::FORM_GROUP_LG);
            $enderecoCtrl->setTemplate(EnderecoControl::TEMPLATE_FORM_HORIZONTAL);
            $enderecoCtrl->setExibeCep(true);
            $enderecoCtrl->setExibePosicao(true);
            $enderecoCtrl->setLogradouroEditavel(false);
            $enderecoCtrl->setBairroEditavel(false);
            $enderecoCtrl->setCidadeEditavel(false);
            $enderecoCtrl->setUfEditavel(false);
            $enderecoCtrl->setPosicaoEditavel(false);
            $enderecoCtrl->setEndereco($endereco);
            $response = $enderecoCtrl->render($args);
            $response = $renderer->render($response, 'usuario-novo-foot.php', $args);
            $response = $renderer->render($response, 'footer.php', $args);
            return $response;
        }
    });

    $app->get('/alterar-meus-dados', function (Request $request, Response $response, $args) use ($app) {

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);

        $usuario = UsuarioBLL::getUsuarioAtual();
        if (is_null($usuario)) {
            $usuario = new UsuarioInfo();
        }

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = $usuario;
        $args['endereco'] = $endereco;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        $response = $rendererMain->render($response, 'header.php', $args);
        $usuarioForm = new UsuarioFormView($request, $response);
        $usuarioForm->setView("view");
        $usuarioForm->setTemplate("usuario-alterar.php");
        $usuarioForm->setGrupoExibe(false);
        $usuarioForm->setSituacaoExibe(false);
        $usuarioForm->setUsuario($usuario);
        $response = $usuarioForm->render($args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->post('/alterar-meus-dados', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);

        $usuario = UsuarioBLL::pegarUsuarioAtual();

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();
        $args['endereco'] = $endereco;

        $usuarioForm = new UsuarioFormView($request, $response);
        $usuarioForm->setView("view");
        $usuarioForm->setTemplate("usuario-alterar.php");
        $usuarioForm->setGrupoExibe(false);
        $usuarioForm->setSituacaoExibe(false);
        $usuarioForm->setUsuario($usuario);
        try {
            $usuarioForm->gravar();
            $url = $app->getBaseUrl() . "/" . $loja->getSlug();
            return $response->withStatus(302)->withHeader('Location', $url);
        } catch (Exception $e) {
            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['erro'] = $e->getMessage();
            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            $response = $rendererMain->render($response, 'header.php', $args);
            $usuarioForm->render($args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        }
    });

    $app->get('/finalizar-pedido/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $regraPedido = new PedidoBLL();

        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
        $usuario = UsuarioBLL::getUsuarioAtual();
        if (is_null($usuario)) {
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        $pedido = $regraPedido->pegar($args["id_pedido"]);

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        //$regraPedido->enviarEmail($pedido);

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = $usuario;
        $args['pedido'] = $pedido;
        $args['urlHome'] = $app->getBaseUrl() . "/" . $loja->getSlug();
        $args['endereco'] = $endereco;

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header.php', $args);
        $response = $renderer->render($response, 'pedido-finalizado.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/email/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {
        ob_start();
        $regraPedido = new PedidoBLL();
        $pedido = $regraPedido->pegar($args["id_pedido"]);
        $regraPedido->enviarEmail($pedido);
        $content = ob_get_contents();
        ob_end_clean();

        $body = $response->getBody();
        $body->write($content);

        return $response->withStatus(200);
    });

    $app->get('/pedidos', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $regraPedido = new PedidoBLL();

        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
        $usuario = UsuarioBLL::getUsuarioAtual();
        if (is_null($usuario)) {
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        $pedidos = $regraPedido->listarPorUsuario($usuario->getId());

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();
        $args['endereco'] = $endereco;

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = $usuario;
        $args['pedidos'] = $pedidos;

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header.php', $args);
        $response = $renderer->render($response, 'pedidos.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/pedido/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $regraPedido = new PedidoBLL();

        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
        $usuario = UsuarioBLL::getUsuarioAtual();
        if (is_null($usuario)) {
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        $pedido = $regraPedido->pegar($args["id_pedido"]);

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();
        $args['endereco'] = $endereco;

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = $usuario;
        $args['pedido'] = $pedido;

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header.php', $args);
        $response = $renderer->render($response, 'pedido.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/avaliar/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $regraPedido = new PedidoBLL();

        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
        $usuario = UsuarioBLL::getUsuarioAtual();
        if (is_null($usuario)) {
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        $pedido = $regraPedido->pegar($args["id_pedido"]);

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = $usuario;
        $args['pedido'] = $pedido;
        $args['endereco'] = $endereco;

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header.php', $args);
        $response = $renderer->render($response, 'pedido-avaliacao.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/lista-de-desejos', function (Request $request, Response $response, $args) use ($app) {

        $regraLoja = new LojaBLL();

        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
        $usuario = UsuarioBLL::getUsuarioAtual();
        if (is_null($usuario)) {
            $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/login";
            return $response->withStatus(302)->withHeader('Location', $url);
        }

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();
        $args['endereco'] = $endereco;

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = $usuario;

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header.php', $args);
        $response = $renderer->render($response, 'lista-desejo.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/busca', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $regraProduto = new ProdutoBLL();

        $queryParams = $request->getQueryParams();
        $palavraChave = $queryParams["p"];
        $pg = intval($queryParams['pg']);
        if (!($pg > 0)) $pg = 1;

        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
        $produtos = $regraProduto->buscar($loja->getId(), $palavraChave);

        $produtosResultado = array_slice($produtos, ($pg - 1) * MAX_PAGE_COUNT, MAX_PAGE_COUNT);

        $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/busca?p=" . urlencode($palavraChave) . "&pg=%s";
        $paginacao = admin_pagination((count($produtos) / MAX_PAGE_COUNT), $url, $pg);

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        $args['endereco'] = $endereco;
        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = UsuarioBLL::pegarUsuarioAtual();
        $args['palavraChave'] = $palavraChave;
        $args['produtos'] = $produtosResultado;
        $args['paginacao'] = $paginacao;

        $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
        $app->addJavascriptConteudo($str);

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header.php', $args);
        $response = $renderer->render($response, 'busca.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/{categoria}/{produto}', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $regraCategoria = new CategoriaBLL();
        $regraProduto = new ProdutoBLL();
		
        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
        $categoria = $regraCategoria->pegarPorSlug($loja->getId(), $args['categoria']);
        if (is_null($categoria)) {
            throw new Exception(sprintf("Categoria '%s' não encontrada.", $args['categoria']));
        }
        $produto = $regraProduto->pegarPorSlug($loja->getId(), $args['produto']);

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();
        $args['endereco'] = $endereco;

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = UsuarioBLL::pegarUsuarioAtual();
        $args['categoria'] = $categoria;
        $args['produto'] = $produto;

        $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
        $app->addJavascriptConteudo($str);

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'header.php', $args);
        $response = $renderer->render($response, 'produto.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/{categoria}', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $regraBanner = new BannerBLL();
        $regraPeca = new BannerPecaBLL();
        $regraCategoria = new CategoriaBLL();
        $regraProduto = new ProdutoBLL();

        $loja = $regraLoja->pegarPorSlug($args['loja_slug']);
        $categoria = $regraCategoria->pegarPorSlug($loja->getId(), $args['categoria']);
        if (is_null($categoria)) {
            throw new Exception(sprintf("Categoria '%s' não encontrada.", $args['categoria']));
        }
        $produtos = $regraProduto->listar($loja->getId(), $categoria->getId());

        $queryParams = $request->getQueryParams();
        $pg = intval($queryParams['pg']);
        if (!($pg > 0)) $pg = 1;
        $produtosResultado = array_slice($produtos, ($pg - 1) * MAX_PAGE_COUNT, MAX_PAGE_COUNT);

        $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/" . $categoria->getSlug() . "?pg=%s";
        $paginacao = admin_pagination((count($produtos) / MAX_PAGE_COUNT), $url, $pg);

        $regraEndereco = new EnderecoBLL();
        $endereco = $regraEndereco->pegarAtual();

        $banner = $regraBanner->pegarPorSlug("tela-inicial");

        $filtro = new BannerFiltroInfo();
        $filtro->setIdBanner($banner->getId());
        $filtro->setOrdem(BannerFiltroInfo::ALEATORIO);
        $filtro->setLatitude($endereco->getLatitude());
        $filtro->setLongitude($endereco->getLongitude());
        $filtro->setRaio($regraLoja->getRaio());

        $pecas = $regraPeca->gerar($filtro);

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['usuario'] = UsuarioBLL::pegarUsuarioAtual();
        $args['categoria'] = $categoria;
        $args['produtos'] = $produtosResultado;
        $args['endereco'] = $endereco;
        $args['banner'] = $banner;
        $args['pecas'] = $pecas;
        $args['paginacao'] = $paginacao;

        $str = "$.carrinho.idLoja = " . $loja->getId() . ";\n";
        $app->addJavascriptConteudo($str);

        /** @var PhpRenderer $renderer */
        $renderer = $this->get('view');

        $response = $renderer->render($response, 'header.php', $args);
        $response = $renderer->render($response, 'categoria.php', $args);
        $response = $renderer->render($response, 'footer.php', $args);
        return $response;
    });
});

$app->group('/api/pedido', function () use ($app) {

    $app->put('/novo', function (Request $request, Response $response, $args) use ($app) {
        try {
            $usuario = UsuarioBLL::pegarUsuarioAtual();
            if (is_null($usuario)) {
                throw new Exception("É necessário fazer o cadastro antes de efetuar o pedido.");
            }

            $json = json_decode($request->getBody()->getContents());
            $pedido = PedidoInfo::fromJson($json);
            $pedido->setCodEntrega(PedidoInfo::ENTREGAR);

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegar($pedido->getIdLoja());

            $pedido->setIdUsuario($usuario->getId());
            if (!($pedido->getCodPagamento() > 0)) {
                $pedido->setCodPagamento(PedidoInfo::DINHEIRO);
            }
            $pedido->setCodSituacao(PedidoInfo::PENDENTE);

            $regraEndereco = new EnderecoBLL();
            $endereco = $regraEndereco->pegarAtual();

            if (is_null($endereco)) {
                /** @var EnderecoInfo $endereco */
                $endereco = array_values($usuario->listarEndereco())[0];
            }

            $pedido->setCep($endereco->getCep());
            $pedido->setLogradouro($endereco->getLogradouro());
            $pedido->setComplemento($endereco->getComplemento());
            $pedido->setNumero($endereco->getNumero());
            $pedido->setBairro($endereco->getBairro());
            $pedido->setCidade($endereco->getCidade());
            $pedido->setUf($endereco->getUf());

            $regraPedido = new PedidoBLL();
            $id_pedido = $regraPedido->inserir($pedido);

            $retorno = new stdClass();
            $retorno->id_pedido = $id_pedido;
            $retorno->url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/finalizar-pedido/" . $id_pedido;
            return $response->withJson($retorno)->withStatus(200);
        } catch (Exception $erro) {
            $body = $response->getBody();
            $body->write($erro->getMessage());
            return $response->withStatus(500);
        }
    });
});