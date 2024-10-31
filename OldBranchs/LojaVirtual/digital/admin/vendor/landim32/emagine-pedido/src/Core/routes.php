<?php

namespace Emagine\Pedido;

use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Pedido\Model\PedidoSituacaoInfo;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Produto\BLL\ProdutoBLL;

$app = EmagineApp::getApp();

if (ProdutoBLL::usaProdutoRoute()) {

    $app->get('/dashboard[/{mes}-{ano}]', function (Request $request, Response $response, $args) use ($app) {

        $regraPedido = new PedidoBLL();

        $usuario = UsuarioBLL::pegarUsuarioAtual();
        if (is_null($usuario)) {
            throw new Exception("Usuário não está logado.");
        }
        if (!$usuario->temPermissao(UsuarioInfo::ADMIN)) {
            throw new Exception("Usuário não é um administrador.");
        }

        $mes = intval($args['mes']);
        $ano = intval($args['ano']);
        if ($mes > 0 && $ano > 0) {
            $dataAtual = strtotime("$ano-$mes-01");
        }
        else {
            $dataAtual = time();
            $mes = intval(date("m", $dataAtual));
            $ano = intval(date("Y", $dataAtual));
        }

        $dataInicio = date("Y-m-01", $dataAtual);
        $dataFim = date("Y-m-t", $dataAtual);
        $mesAtual = date("Y-m-01", $dataAtual);
        $mesAtualStr = ucfirst(strftime("%B/%Y", $dataAtual));

        $mesAtividade = $regraPedido->listarMesAtividade();
        $pedidos = $regraPedido->listarVendidoPorData($dataInicio, $dataFim);

        $produtos = $regraPedido->listarProdutoPorVenda(0, $dataInicio, $dataFim, 7);

        $graficoProdutoBase64 = "";
        if (count($produtos) > 0) {
            $graficoProduto = $regraPedido->gerarGraficoProdutoMaisVendido($produtos, "Mais vendidos em " . $mesAtualStr);
            $graficoProdutoBase64 = "data:image/png;base64, " . base64_encode($graficoProduto);
        }

        $args['app'] = $app;
        $args['mes'] = $mes;
        $args['ano'] = $ano;
        $args['mesAtual'] = $mesAtual;
        $args['mesAtualStr'] = $mesAtualStr;
        $args['mesAtividade'] = $mesAtividade;
        $args['pedidos'] = $pedidos;
        $args['produtos'] = $produtos;
        $args['graficoProdutoBase64'] = $graficoProdutoBase64;
        $args['usuarioPerfil'] = UsuarioPerfilFactory::create();

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererPedido */
        $rendererPedido = $this->get('pedidoView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererPedido->render($response, 'dashboard.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/{slug_loja}/dashboard[/{mes}-{ano}]', function (Request $request, Response $response, $args) use ($app) {

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $regraLoja->validarPermissao($loja);

        $regraPedido = new PedidoBLL();

        $mes = intval($args['mes']);
        $ano = intval($args['ano']);
        if ($mes > 0 && $ano > 0) {
            $dataAtual = strtotime("$ano-$mes-01");
        }
        else {
            $dataAtual = time();
            $mes = intval(date("m", $dataAtual));
            $ano = intval(date("Y", $dataAtual));
        }

        $dataInicio = date("Y-m-01", $dataAtual);
        $dataFim = date("Y-m-t", $dataAtual);
        $mesAtual = date("Y-m-01", $dataAtual);
        $mesAtualStr = ucfirst(strftime("%B/%Y", $dataAtual));

        $mesAtividade = $regraPedido->listarMesAtividade($loja->getId());
        $pedidos = $regraPedido->listarVendidoPorData($dataInicio, $dataFim, $loja->getId());

        $produtos = $regraPedido->listarProdutoPorVenda($loja->getId(), $dataInicio, $dataFim, 7);

        $graficoProdutoBase64 = "";
        if (count($produtos) > 0) {
            $graficoProduto = $regraPedido->gerarGraficoProdutoMaisVendido($produtos, "Mais vendidos em " . $mesAtualStr);
            $graficoProdutoBase64 = "data:image/png;base64, " . base64_encode($graficoProduto);
        }

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/dashboard");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['mes'] = $mes;
        $args['ano'] = $ano;
        $args['mesAtual'] = $mesAtual;
        $args['mesAtualStr'] = $mesAtualStr;
        $args['mesAtividade'] = $mesAtividade;
        $args['pedidos'] = $pedidos;
        $args['produtos'] = $produtos;
        $args['graficoProdutoBase64'] = $graficoProdutoBase64;
        $args['usuarioPerfil'] = $usuarioPerfil;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererPedido */
        $rendererPedido = $this->get('pedidoView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererPedido->render($response, 'dashboard.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('/{slug_loja}/pedidos[/{cod_situacao}]', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $regraLoja->validarPermissao($loja);

        $codSituacao = intval($args["cod_situacao"]);

        $regraPedido = new PedidoBLL();
        $pedidos = $regraPedido->listar($loja->getId(), $codSituacao);

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/pedidos");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['pedidos'] = $pedidos;
        $args['cod_situacao'] = $codSituacao;
        $args['usuarioPerfil'] = $usuarioPerfil;

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererPedido */
        $rendererPedido = $this->get('pedidoView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererPedido->render($response, 'pedidos.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->group('/{slug_loja}/pedido', function () use ($app) {

        $app->get('/mapa', function (Request $request, Response $response, $args) use ($app) {
            $args['app'] = $app;

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $script = sprintf("\$.loja.id_loja = %s;\n", $loja->getId());
            $script .= sprintf("\$.pedido.js_path = '%s';\n", $app->getModuleUrl(dirname(__DIR__)));

            $app->addJavascriptConteudo($script);

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererPedido */
            $rendererPedido = $this->get('pedidoView');
            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererPedido->render($response, 'mapa.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->get('/situacao/{id_pedido}/{cod_situacao}', function (Request $request, Response $response, $args) use ($app) {
            try {
                $id_pedido = intval($args["id_pedido"]);
                $regraPedido = new PedidoBLL();
                $pedido = $regraPedido->pegar($id_pedido);
                $regraPedido->alterarSituacao((new PedidoSituacaoInfo())
                    ->setIdUsuario($pedido->getIdUsuario())
                    ->setIdPedido($id_pedido)
                    ->setCodSituacao(intval($args["cod_situacao"]))
                );
                //$regraPedido->alterarSituacao($args["id_pedido"], $args['cod_situacao']);
                $url = $app->getBaseUrl() . "/" . $args['slug_loja'] . "/pedido/id/" . $args["id_pedido"];
                return $response->withStatus(302)->withHeader("Location", $url);
            } catch (Exception $e) {
                $url = $app->getBaseUrl() . "/" . $args['slug_loja'] . "/pedido/id/" . $args["id_pedido"];
                $url .= "?erro=" . urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader("Location", $url);
            }
        });

        $app->get('/id/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {

            $queryParam = $request->getQueryParams();

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $usuario = UsuarioBLL::pegarUsuarioAtual();
            $regraLoja->validarPermissao($loja, $usuario);

            $regraPedido = new PedidoBLL();
            $pedido = $regraPedido->pegar($args['id_pedido']);

            $usuarioPerfil = UsuarioPerfilFactory::create();
            $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/pedidos");
            if ($usuarioPerfil instanceof LojaPerfilBLL) {
                $usuarioPerfil->setLoja($loja);
            }

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['pedido'] = $pedido;
            $args['usuario'] = $usuario;
            $args['situacoes'] = $regraPedido->listarSituacao();
            $args['exibeFoto'] = true;
            $args['usuarioPerfil'] = $usuarioPerfil;
            if (array_key_exists('erro', $queryParam)) {
                $args['erro'] = $queryParam['erro'];
            }

            /** @var PhpRenderer $rendererMain */
            $rendererMain = $this->get('view');
            /** @var PhpRenderer $rendererPedido */
            $rendererPedido = $this->get('pedidoView');

            $response = $rendererMain->render($response, 'header.php', $args);
            $response = $rendererPedido->render($response, 'situacao-modal.php', $args);
            $response = $rendererPedido->render($response, 'pedido.php', $args);
            $response = $rendererMain->render($response, 'footer.php', $args);
            return $response;
        });

        $app->post('/id/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {
            $postParam = $request->getParsedBody();

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $url = $app->getBaseUrl(). "/" . $loja->getSlug() . "/pedido/id/" . $args["id_pedido"];
            try {
                $usuario = UsuarioBLL::pegarUsuarioAtual();

                $situacao = (new PedidoSituacaoInfo())
                    ->setIdPedido(intval($postParam["id_pedido"]))
                    ->setIdUsuario($usuario->getId())
                    ->setCodSituacao(intval($postParam["cod_situacao"]))
                    ->setMensagem($postParam["mensagem"]);

                $regraPedido = new PedidoBLL();
                $regraPedido->alterarSituacao($situacao);
                return $response->withStatus(302)->withHeader('Location', $url);
            }
            catch (Exception $e) {
                $url .= "?erro=" . urlencode($e->getMessage());
                return $response->withStatus(302)->withHeader('Location', $url);
            }
        });

        $app->get('/imprimir/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {

            $queryParam = $request->getQueryParams();

            $regraLoja = new LojaBLL();
            $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
            $regraLoja->validarPermissao($loja);

            $regraPedido = new PedidoBLL();
            $pedido = $regraPedido->pegar($args['id_pedido']);

            $args['app'] = $app;
            $args['loja'] = $loja;
            $args['pedido'] = $pedido;
            $args['situacoes'] = $regraPedido->listarSituacao();
            $args['exibeFoto'] = false;
            if (array_key_exists('erro', $queryParam)) {
                $args['erro'] = $queryParam['erro'];
            }

            /** @var PhpRenderer $rendererPedido */
            $rendererPedido = $this->get('pedidoView');
            $response = $rendererPedido->render($response, 'pedido-imprime.php', $args);
            return $response;
        });

        $app->get('/email/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {
            $regraPedido = new PedidoBLL();
            $pedido = $regraPedido->pegar($args['id_pedido']);
            $conteudoEmail = $regraPedido->gerarEmail($pedido);
            $body = $response->getBody();
            $body->write($conteudoEmail);
            return $response;
        });
    });
}