<?php

namespace Emagine\Log;

use Emagine\Log\BLL\LogBLL;
use Emagine\Log\Factory\LogFactory;
use Emagine\Log\Model\LogFiltroInfo;
use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = EmagineApp::getApp();


$app->group('/log', function() use ($app) {

    $app->get('/excluir/{id_log}', function (Request $request, Response $response, $args) use ($app) {
        try {
            $id_log = intval($args["id_log"]);
            $regraLog = LogFactory::create();
            $regraLog->excluir($id_log);

            $url = $app->getBaseUrl() . "/log";
            return $response->withStatus(302)->withHeader('Location', $url);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/{id_log}', function (Request $request, Response $response, $args) use ($app) {
        $regraLog = LogFactory::create();
        $args['app'] = $app;
        $args['log'] = $regraLog->pegar(intval($args['id_log']));
        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererLog */
        $rendererLog = $this->get('log');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererLog->render($response, 'log.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

    $app->get('', function (Request $request, Response $response, $args) use ($app) {
        $queryParam = $request->getQueryParams();

        $pg = intval($queryParam["pg"]);
        if ($pg <= 0) $pg = 1;

        $filtro = (new LogFiltroInfo())
            ->setTamanhoPagina(25)
            ->setPagina($pg);

        if (array_key_exists("ini", $queryParam)) {
            $dataArray = explode("/", $queryParam["ini"]);
            if (count($dataArray) == 3) {
                $dataIni = $dataArray[2] . "-" . $dataArray[1] . "-" . $dataArray[0] . " 00:00:00";
                $filtro->setDataInicio($dataIni);
            }
        }
        if (array_key_exists("fim", $queryParam)) {
            $dataArray = explode("/", $queryParam["fim"]);
            if (count($dataArray) == 3) {
                $dataFim = $dataArray[2] . "-" . $dataArray[1] . "-" . $dataArray[0] . " 23:59:59";
                $filtro->setDataFim($dataFim);
            }
        }

        $regraLog = LogFactory::create();
        $logs = $regraLog->listar($filtro);

        $urlLog = $app->getBaseUrl() . "/log?pg=%s";
        if (!isNullOrEmpty($filtro->getDataInicioStr())) {
            $urlLog .= "&ini=" . str_replace("%", "%%", urlencode($filtro->getDataInicioStr()));
        }
        if (!isNullOrEmpty($filtro->getDataFimStr())) {
            $urlLog .= "&fim=" . str_replace("%", "%%", urlencode($filtro->getDataFimStr()));
        }
        $paginacao = admin_pagination($logs->getQuantidadePagina(), $urlLog, $logs->getPagina());

        $args['app'] = $app;
        $args['filtro'] = $filtro;
        $args['logs'] = $logs;
        $args['paginacao'] = $paginacao;
        if (array_key_exists("p", $queryParam)) {
            $args['pagina'] = $queryParam["p"];
        }

        /** @var PhpRenderer $rendererMain */
        $rendererMain = $this->get('view');
        /** @var PhpRenderer $rendererLog */
        $rendererLog = $this->get('log');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererLog->render($response, 'logs.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });
});
