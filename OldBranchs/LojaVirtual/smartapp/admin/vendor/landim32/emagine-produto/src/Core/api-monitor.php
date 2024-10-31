<?php

namespace Emagine\Produto;

use Emagine\Produto\BLL\AvisoBLL;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\ProdutoBLL;

$app = EmagineApp::getApp();

if (!ProdutoBLL::usaProdutoAPI()) {

    $app->group('/api/monitor', function () use ($app) {

        $app->get('/listar', function (Request $request, Response $response, $args) use ($app) {
            try {
                $queryParam = $request->getQueryParams();
                $id_loja = 0;
                if (array_key_exists("loja", $queryParam)) {
                    $id_loja = intval($queryParam['loja']);
                }
                $data = null;
                if (array_key_exists("data", $queryParam)) {
                    $data = $queryParam['data'];
                }
                else {
                    $data = date("Y-m-d H:i:s",time() - 10);
                }
                $regraMonitor = new AvisoBLL();
                $atividades = $regraMonitor->listar($id_loja, $data, 1);
                return $response->withJson($atividades);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });
    });
}