<?php

namespace Emagine\Banner;

use Emagine\Banner\BLL\BannerPecaBLL;
use Emagine\Banner\Model\BannerFiltroInfo;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Banner\BLL\BannerBLL;

$app = EmagineApp::getApp();

$app->group('/api/banner', function () use ($app) {

    $app->get('/listar', function (Request $request, Response $response, $args) {
        try {
            $bll = new BannerBLL();
            $banners = $bll->listar();
            return $response->withJson($banners);
        } catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->group('/peca', function () use ($app) {

        $app->put('/gerar', function (Request $request, Response $response, $args) {
            try {
                $json = json_decode($request->getBody()->getContents());
                $filtro = BannerFiltroInfo::fromJson($json);
                $regraPeca = new BannerPecaBLL();
                $pecas = $regraPeca->gerar($filtro);
                return $response->withJson($pecas);
            } catch (Exception $e) {
                $body = $response->getBody();
                $body->write($e->getMessage());
                return $response->withStatus(500);
            }
        });
    });
});