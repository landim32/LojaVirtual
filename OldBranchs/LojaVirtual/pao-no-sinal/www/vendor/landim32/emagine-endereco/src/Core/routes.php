<?php

namespace Emagine\Endereco;

use Exception;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Endereco\BLL\PaisBLL;
use Emagine\Endereco\BLL\UfBLL;
use Emagine\Endereco\BLL\CidadeBLL;
use Emagine\Endereco\BLL\BairroBLL;
use Emagine\Endereco\BLL\CepBLL;
use Emagine\Endereco\Model\PaisInfo;
use Emagine\Endereco\Model\UfInfo;
use Emagine\Endereco\Model\CidadeInfo;
use Emagine\Endereco\Model\BairroInfo;

$app = EmagineApp::getApp();

$app->group('/api/cep', function() use ($app) {

    $app->get('/listar-uf', function (Request $request, Response $response, $args) {
        try {
            $regraCep = new CepBLL();
            $ufs = $regraCep->listarUf();
            return $response->withJson($ufs);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/pegar/{cep}', function (Request $request, Response $response, $args) {
        try {
            $regraCep = new CepBLL();
            $endereco = $regraCep->pegarPorCep($args['cep']);
            return $response->withJson($endereco);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/buscar-por-cidade', function (Request $request, Response $response, $args) {
        try {
            $queryParam = $request->getQueryParams();
            $palavraChave = "";
            $uf = "";
            if (array_key_exists("palavrachave", $queryParam)) {
                $palavraChave = $queryParam["palavrachave"];
            }
            if (array_key_exists("uf", $queryParam)) {
                $uf = $queryParam["uf"];
            }
            $regraCep = new CepBLL();
            $cidades = $regraCep->buscarCidadePorUf($palavraChave, $uf);
            return $response->withJson($cidades);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/buscar-por-cidade', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $regraCep = new CepBLL();
            $cidades = $regraCep->buscarCidadePorUf($json->palavrachave, $json->uf);
            return $response->withJson($cidades);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/buscar-por-bairro', function (Request $request, Response $response, $args) {
        try {
            $q = $request->getQueryParams();

            $regraCep = new CepBLL();
            $bairros = $regraCep->buscarBairro(urldecode($q['p']), $q['uf'], urldecode($q['cidade']));
            return $response->withJson($bairros);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/buscar-por-bairro', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $regraCep = new CepBLL();
            $id_cidade = intval($json->id_cidade);
            $bairros = $regraCep->buscarBairroPorIdCidade($json->palavrachave, $id_cidade);
            return $response->withJson($bairros);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->get('/buscar-por-logradouro', function (Request $request, Response $response, $args) {
        try {
            $q = $request->getQueryParams();

            /*
            ob_start();
            var_dump($q);
            $content = ob_get_contents();
            ob_end_clean();
            $body = $response->getBody();
            $body->write($content);
            return $response->withStatus(200);
            */

            $regraCep = new CepBLL();
            $enderecos = $regraCep->buscarLogradouro($q['p'], $q['bairro'], $q['cidade'], $q['uf']);
            return $response->withJson($enderecos);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

    $app->put('/buscar-por-logradouro', function (Request $request, Response $response, $args) {
        try {
            $json = json_decode($request->getBody()->getContents());
            $regraCep = new CepBLL();
            $id_bairro = intval($json->id_bairro);
            $enderecos = $regraCep->buscarLogradouroPorIdBairro($json->palavrachave, $id_bairro);
            return $response->withJson($enderecos);
        }
        catch (Exception $e) {
            $body = $response->getBody();
            $body->write($e->getMessage());
            return $response->withStatus(500);
        }
    });

});

$app->group('/api/cidade', function() use ($app) {
    $app->get('/listar', function (Request $request, Response $response, $args) {
        $regraCidade = new CidadeBLL();
        $cidades = $regraCidade->listar();
        return $response->withJson($cidades);
    });
    $app->post('/listar', function (Request $request, Response $response, $args) {
        $postParam = $request->getParsedBody();
        $regraCidade = new CidadeBLL();
        $estados = $regraCidade->listarPorUf($postParam["uf"]);
        return $response->withJson($estados);
    });
    $app->get('/buscar', function (Request $request, Response $response, $args) {
        $queryParam = $request->getQueryParams();
        $palavraChave = $queryParam["p"];
        $regraCidade = new CidadeBLL();
        $cidades = $regraCidade->buscar($palavraChave, 10);
        return $response->withJson($cidades);
    });
});

$app->group('/api/localidade', function() use ($app) {
    $app->post('/paises', function (Request $request, Response $response, $args) {
        $regraPais = new PaisBLL();
        $paises = $regraPais->listar();
        return $response->withJson($paises);
    });
    $app->post('/estados', function (Request $request, Response $response, $args) {
        $postParam = $request->getParsedBody();
        $regraUf = new UfBLL();
        $idPais = intval($postParam["id_pais"]);
        if ($idPais > 0) {
            $estados = $regraUf->listarPorPais($idPais);
        }
        else {
            $estados = $regraUf->listar();
        }
        return $response->withJson($estados);
    });
    $app->post('/cidades', function (Request $request, Response $response, $args) {
        $postParam = $request->getParsedBody();
        $regraCidade = new CidadeBLL();
        $estados = $regraCidade->listarPorUf($postParam["uf"]);
        return $response->withJson($estados);
    });
    $app->post('/bairros', function (Request $request, Response $response, $args) {
        $postParam = $request->getParsedBody();
        $regraBairro = new BairroBLL();
        $bairros = $regraBairro->listarPorCidade($postParam["id_cidade"]);
        return $response->withJson($bairros);
    });
});

$app->group('/ajax/localidade', function() use ($app) {
    $app->get('/pais/listar', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new PaisBLL();
        $args['paises'] = $bll->listar();
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'pais-lista.php', $args);
        return $response;
    });

    $app->get('/pais/{id_pais}', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new PaisBLL();
        $args['pais'] = $bll->pegar($args['id_pais']);
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'pais.php', $args);
        return $response;
    });

    $app->get('/pais-add', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $args['pais'] = new PaisInfo();
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'pais-form.php', $args);
        return $response;
    });

    $app->get('/pais-edit/{id_pais}', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new PaisBLL();
        $args['pais'] = $bll->pegar($args['id_pais']);
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'pais-form.php', $args);
        return $response;
    });

    $app->get('/uf/listar', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new UfBLL();
        $args['estados'] = $bll->listar();
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'uf-lista.php', $args);
        return $response;
    });

    $app->get('/uf/{uf}', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new UfBLL();
        $args['uf'] = $bll->pegar($args['uf']);
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'uf.php', $args);
        return $response;
    });

    $app->get('/uf-add', function (Request $request, Response $response, $args) use ($app) {
        $regraPais = new PaisBLL();
        $paises = $regraPais->listar();
        $uf = new UfInfo();

        $args['app'] = $app;
        $args['paises'] = $paises;
        $args['uf'] = $uf;
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'uf-form.php', $args);
        return $response;
    });

    $app->get('/uf-edit/{uf}', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new UfBLL();
        $args['uf'] = $bll->pegar($args['uf']);
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'uf-form.php', $args);
        return $response;
    });

    $app->get('/cidade/listar', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new CidadeBLL();
        $args['cidades'] = $bll->listar();
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'cidade-lista.php', $args);
        return $response;
    });

    $app->get('/cidade/{id_cidade}', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new CidadeBLL();
        $args['cidade'] = $bll->pegar($args['id_cidade']);
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'cidade.php', $args);
        return $response;
    });

    $app->get('/cidade-add', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $args['cidade'] = new CidadeInfo();
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'cidade-form.php', $args);
        return $response;
    });

    $app->get('/cidade-edit/{id_cidade}', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new CidadeBLL();
        $args['cidade'] = $bll->pegar($args['id_cidade']);
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'cidade-form.php', $args);
        return $response;
    });

    $app->get('/bairro/listar', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new BairroBLL();
        $args['bairros'] = $bll->listar();
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'bairro-lista.php', $args);
        return $response;
    });

    $app->get('/bairro/{id_bairro}', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new BairroBLL();
        $args['bairro'] = $bll->pegar($args['id_bairro']);
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'bairro.php', $args);
        return $response;
    });

    $app->get('/bairro-add', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $args['bairro'] = new BairroInfo();
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'bairro-form.php', $args);
        return $response;
    });

    $app->get('/bairro-edit/{id_bairro}', function (Request $request, Response $response, $args) use ($app) {
        $args['app'] = $app;
        $bll = new BairroBLL();
        $args['bairro'] = $bll->pegar($args['id_bairro']);
        /** @var PhpRenderer $renderer */
        $renderer = $this->get('localidadeView');
        $response = $renderer->render($response, 'bairro-form.php', $args);
        return $response;
    });
});

$app->post('/localidade/pais', function (Request $request, Response $response, $args) {
    $params = $request->getParsedBody();

    $bll = new PaisBLL();
    $pais = null;
    $inserir = false;
    $id_pais = intval($params['id_pais']);
    if ($id_pais > 0) {
        $pais = $bll->pegar($id_pais);
    }
    if (is_null($pais)) {
        $pais = new PaisInfo();
        $inserir = true;
    }
    $pais->setNome(!isNullOrEmpty($params['nome']) ? $params['nome'] : null );

    try {
        if ($inserir) {
            $id_pais = $bll->inserir($pais);
        }
        else {
            $bll->alterar($pais);
            $id_pais = $pais->getId();
        }
        $body = $response->getBody();
        $body->write($id_pais);
        return $response->withStatus(200);
    }
    catch (Exception $e) {
        $body = $response->getBody();
        $body->write($e->getMessage());
        return $response->withStatus(500);
    }
});

$app->post('/localidade/uf', function (Request $request, Response $response, $args) {
    $params = $request->getParsedBody();

    $inserir = false;
    $regraUf = new UfBLL();
    $uf = $regraUf->pegar($params['uf']);
    if (is_null($uf)) {
        $uf = new UfInfo();
        $inserir = true;
    }
    $uf = $regraUf->pegarDoPost($params, $uf);

    try {
        if ($inserir) {
            $regraUf->inserir($uf);
        }
        else {
            $regraUf->alterar($uf);
        }
        return $response->withStatus(200);
    }
    catch (Exception $e) {
        $body = $response->getBody();
        $body->write($e->getMessage());
        return $response->withStatus(500);
    }
});

$app->post('/localidade/cidade', function (Request $request, Response $response, $args) {
    $params = $request->getParsedBody();

    $bll = new CidadeBLL();
    $cidade = null;
    $inserir = false;
    $id_cidade = intval($params['id_cidade']);
    if ($id_cidade > 0) {
        $cidade = $bll->pegar($id_cidade);
    }
    if (is_null($cidade)) {
        $cidade = new CidadeInfo();
        $inserir = true;
    }
    $cidade->setUf(!isNullOrEmpty($params['uf']) ? $params['uf'] : null );
    $cidade->setNome(!isNullOrEmpty($params['nome']) ? $params['nome'] : null );

    try {
        if ($inserir) {
            $id_cidade = $bll->inserir($cidade);
        }
        else {
            $bll->alterar($cidade);
            $id_cidade = $cidade->getId();
        }
        $body = $response->getBody();
        $body->write($id_cidade);
        return $response->withStatus(200);
    }
    catch (Exception $e) {
        $body = $response->getBody();
        $body->write($e->getMessage());
        return $response->withStatus(500);
    }
});

$app->post('/localidade/bairro', function (Request $request, Response $response, $args) {
    $params = $request->getParsedBody();

    $bll = new BairroBLL();
    $bairro = null;
    $inserir = false;
    $id_bairro = intval($params['id_bairro']);
    if ($id_bairro > 0) {
        $bairro = $bll->pegar($id_bairro);
    }
    if (is_null($bairro)) {
        $bairro = new BairroInfo();
        $inserir = true;
    }
    $bairro = $bll->pegarDoPost($params, $bairro);

    try {
        if ($inserir) {
            $id_bairro = $bll->inserir($bairro);
        }
        else {
            $bll->alterar($bairro);
            $id_bairro = $bairro->getId();
        }
        $body = $response->getBody();
        $body->write($id_bairro);
        return $response->withStatus(200);
    }
    catch (Exception $e) {
        $body = $response->getBody();
        $body->write($e->getMessage());
        return $response->withStatus(500);
    }
});
