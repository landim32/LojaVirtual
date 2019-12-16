<?php

namespace Emagine\Pedido;

use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Exception;
use Emagine\Base\EmagineApp;
use Landim32\EasyDB\DB;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Grafico\BLL\GraficoLinhaBLL;
use Emagine\Grafico\BLL\GraficoPizzaBLL;
use Emagine\Grafico\Model\EstatisticaInfo;

$app = EmagineApp::getApp();

if (ProdutoBLL::usaProdutoRoute()) {

    $app->group('/grafico', function () use ($app) {

        $app->get('/venda-em-{mes}-{ano}.png', function (Request $request, Response $response, $args) use ($app) {
            try {
                $queryParam = $request->getQueryParams();

                $mes = intval($args['mes']);
                $ano = intval($args['ano']);
                $id_loja = intval($queryParam['loja']);

                $regraPedido = new PedidoBLL();
                $content = $regraPedido->gerarGraficoVendaMensal($ano, $mes, $id_loja);
                $body = $response->getBody();
                $body->write($content);
                return $response->withStatus(200)->withHeader('Content-Type', 'content-type: image/png');
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->get('/mais-vendido-{mes}-{ano}.png', function (Request $request, Response $response, $args) use ($app) {
            try {
                $queryParam = $request->getQueryParams();

                $mes = intval($args['mes']);
                $ano = intval($args['ano']);
                $id_loja = intval($queryParam['loja']);

                $regraPedido = new PedidoBLL();
                $content = $regraPedido->gerarGraficoProdutoMaisVendidoPorMes($ano, $mes, $id_loja);
                $body = $response->getBody();
                $body->write($content);
                return $response->withStatus(200)->withHeader('Content-Type', 'content-type: image/png');
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });

        $app->group('/{loja_slug}', function () use ($app) {
            $app->get('/{grafico_slug}.png', function (Request $request, Response $response, $args) use ($app) {

                //$regraLoja = new LojaBLL();
                //$loja = $regraLoja->pegarPorSlug($args['loja_slug']);

                ob_start();
                try {
                    if ($args["grafico_slug"] == "linha") {
                        $graficoPizza = new GraficoLinhaBLL(500, 300);
                    } else {
                        $graficoPizza = new GraficoPizzaBLL(500, 300);
                    }
                    $graficoPizza->setTitulo("Isso é um teste");
                    $nomeArquivo = sprintf("grafico/%s/%s.png", $args['loja_slug'], $args['grafico_slug']);
                    $graficoPizza->setNomeArquivo($nomeArquivo);
                    $graficoPizza->adicionarDado(new EstatisticaInfo("teste", 1));
                    $graficoPizza->adicionarDado(new EstatisticaInfo("teste2", 2));
                    $graficoPizza->adicionarDado(new EstatisticaInfo("teste3", 3));
                    $graficoPizza->render();
                } catch (Exception $e) {
                    $erro = $e->getMessage();
                }
                $content = ob_get_contents();
                ob_end_clean();
                if (!isset($erro)) {
                    $body = $response->getBody();
                    $body->write($content);
                    return $response->withStatus(200)->withHeader('Content-Type', 'content-type: image/png');
                } else {
                    $body = $response->getBody();
                    $body->write($erro);
                    return $response->withStatus(500);
                }
            });
        });
    });
}