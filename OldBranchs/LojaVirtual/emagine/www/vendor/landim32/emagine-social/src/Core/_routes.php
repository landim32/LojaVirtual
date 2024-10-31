<?php

namespace Emagine\Social;

use Emagine\Social\Factory\MensagemFactory;
use Exception;
use stdClass;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Social\BLL\UsuarioSocialBLL;
use Emagine\Social\BLL\MensagemBLL;
use Imobsync\Conta\BLL\ContaBLL;
use Imobsync\Imovel\BLL\ImovelBLL;
use Emagine\Social\BLL\NovidadeBLL;
use Emagine\Social\Model\ContatoInfo;

$app = EmagineApp::getApp();

$app->get('/ajax/timeline/{slug}', function (Request $request, Response $response, $args) use ($app) {

    $regraConta = new ContaBLL();
    $regraImovel = new ImovelBLL();
    $regraNovidade = new NovidadeBLL();

    $conta = $regraConta->pegarPorSlug($args['slug']);
    $quantidadeExpirado = $regraImovel->quantidadeExpirado();
    $imovelExpirado = $regraImovel->listarExpirado();
    $imovelExpira = $regraImovel->listarExpira();
    $quantidadeExpira = $regraImovel->quantidadeExpira();
    $novidades = $regraNovidade->listarNovidade($conta->getId());

    $args['app'] = $app;
    $args['conta'] = $conta;
    $args['quantidadeExpirado'] = $quantidadeExpirado;
    $args['imovelExpirado'] = $imovelExpirado;
    $args['imovelExpira'] = $imovelExpira;
    $args['quantidadeExpira'] = $quantidadeExpira;
    $args['novidades'] = $novidades;

    /** var PhpRenderer $renderer */
    $renderer = $this->get('socialView');

    $response = $renderer->render($response, 'timeline.php', $args);
    return $response;

})->add(function (Request $request, Response $response, callable $next) {
    $regraUsuario = new UsuarioBLL();
    if (!$regraUsuario->estaLogado()) {
        throw new Exception("Acesso negado!");
    }
    $response = $next($request, $response);
    return $response;
});

$app->group('/perfil', function () use ($app) {

    $app->get('/{slug}', function (Request $request, Response $response, $args) use ($app) {

        $regraUsuario = new UsuarioBLL();
        $args['app'] = $app;
        $args['usuario'] = $regraUsuario->pegarPorSlug($args['slug']);

        /** var PhpRenderer $renderer */
        $rendererMain = $this->get('view');
        /** var PhpRenderer $renderer */
        $rendererSocial = $this->get('socialView');

        $response = $rendererMain->render($response, 'header.php', $args);
        $response = $rendererSocial->render($response, 'perfil.php', $args);
        $response = $rendererMain->render($response, 'footer.php', $args);
        return $response;
    });

})->add(function (Request $request, Response $response, callable $next) {
    $regraUsuario = new UsuarioBLL();
    if (!$regraUsuario->estaLogado()) {
        throw new Exception("Acesso negado!");
    }
    $response = $next($request, $response);
    return $response;
});

$app->group('/ajax/social', function () use ($app) {

    $app->get('/mensagens', function (Request $request, Response $response, $args) use ($app) {
        $regraUsuario = new UsuarioBLL();
        $regraMensagem = MensagemFactory::create();

        $queryParams = $request->getQueryParams();
        $id_usuario = intval($queryParams['usuario']);
        if (!($id_usuario > 0)) {
            $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        }

        $mensagens = $regraMensagem->listar($id_usuario);
        /** @var ContatoInfo[] $contatos */
        $contatos = $regraMensagem->listarContato($mensagens);

        if (!($id_usuario > 0) && count($contatos) > 0) {
            //list($id_usuario, $contato) = each($contatos);
            reset($contatos);
            /** @var ContatoInfo $contato */
            $contato = array_shift(array_slice($contatos, 0, 1));
            $id_usuario = $contato->getIdUsuario();
        }

        if ($id_usuario > 0 && array_key_exists($id_usuario, $contatos)) {
            $contato = $contatos[$id_usuario];
            $regraMensagem->marcarLido($id_usuario);
            $mensagens = $contato->listarMensagem($id_usuario);
        }
        if ($id_usuario > 0)
            $usuario = $regraUsuario->pegar($id_usuario);
        else
            $usuario = null;

        $args['id_usuario'] = $id_usuario;
        $args['usuario'] = $usuario;
        $args['contatos'] = $contatos;
        $args['mensagens'] = $mensagens;

        /** var PhpRenderer $renderer */
        $renderer = $this->get('socialView');
        $response = $renderer->render($response, 'mensagem.php', $args);
        return $response;
    });

    $app->get('/busca', function (Request $request, Response $response, $args) use ($app) {
        $regraSocial = new UsuarioSocialBLL();

        $queryParams = $request->getQueryParams();
        $pg = intval($queryParams['pg']);

        $usuarios = $regraSocial->buscaPorPalavra($queryParams['p']);
        $usuariosResultado = array_slice($usuarios, $pg * MAX_PAGE_COUNT, MAX_PAGE_COUNT);

        $args['app'] = $app;
        $args['usuariosResultado'] = $usuariosResultado;
        $args['paginacao'] = admin_pagination(ceil(count($usuarios) / MAX_PAGE_COUNT), "#social/busca?pg=%s");

        /** var PhpRenderer $renderer */
        $renderer = $this->get('socialView');
        $response = $renderer->render($response, 'busca.php', $args);
        return $response;
    });

})->add(function (Request $request, Response $response, callable $next) {
    $regraUsuario = new UsuarioBLL();
    if (!$regraUsuario->estaLogado()) {
        throw new Exception("Acesso negado!");
    }
    $response = $next($request, $response);
    return $response;
});

$app->group('/api/social', function () use ($app) {

    $app->post('/buscar-amigo', function (Request $request, Response $response, $args) {
        $params = $request->getParsedBody();
        $bll = new UsuarioSocialBLL();
        $usuarios = $bll->buscarAmigo($params["palavra_chave"]);
        return $response->withJson($usuarios);
    });

    $app->post('/enviar-mensagem', function (Request $request, Response $response, $args) {
        $params = $request->getParsedBody();
        $bll = MensagemFactory::create();
        $bll->enviarMensagem($params["id_usuario"], $params["mensagem"]);
        return $response->withStatus(200);
    });

    $app->get('/excluir-mensagem/{id_mensagem}', function (Request $request, Response $response, $args) {
        $bll = MensagemFactory::create();
        $bll->excluir($args["id_mensagem"]);
        return $response->withStatus(200);
    });

})->add(function (Request $request, Response $response, callable $next) {
    try {
        $regraUsuario = new UsuarioBLL();
        if (!$regraUsuario->estaLogado()) {
            throw new Exception("Acesso negado!");
        }
        $response = $next($request, $response);
        return $response;
    }
    catch (Exception $e) {
        $body = $response->getBody();
        $body->write($e->getMessage());
        return $response->withStatus(500);
    }
});