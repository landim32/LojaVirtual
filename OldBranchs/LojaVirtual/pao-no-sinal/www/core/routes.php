<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Endereco\BLL\BairroBLL;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\BLL\CategoriaBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Exception;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Body;

$app = EmagineApp::getApp();

$app->get('/', function (Request $request, Response $response, $args) use($app) {
    $regraLoja = new LojaBLL();
    $lojas = $regraLoja->listar();
    if (count($lojas) <= 0) {
        throw new Exception("Nenhuma loja cadastrada.");
    }
    if (count($lojas) > 1) {
        $args['app'] = $app;
        $args['lojas'] = $lojas;
        /** var PhpRenderer $renderer */
        $renderer = $this->get('view');
        $response = $renderer->render($response, 'loja.php', $args);
        return $response;
    }
    else {
        $loja = reset($lojas);
        $url = $app->getBaseUrl() . "/site/" . $loja->getSlug();
        return $response->withStatus(302)->withHeader('Location', $url);
    }
});

$app->get('/site/{slug}', function (Request $request, Response $response, $args) use ($app) {

    $regraLoja = new LojaBLL();
    $regraProduto = new ProdutoBLL();

    $loja = $regraLoja->pegarPorSlug($args['slug']);
    $usuario = UsuarioBLL::getUsuarioAtual();
    $produtos = $regraProduto->listar($loja->getId());

    $queryParams = $request->getQueryParams();
    $pg = intval($queryParams['pg']);
    if (!($pg > 0)) $pg = 1;
    $produtosResultado = array_slice($produtos, ($pg - 1) * MAX_PAGE_COUNT, MAX_PAGE_COUNT);

    $url = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "?pg=%s";
    $paginacao = admin_pagination((count($produtos) / MAX_PAGE_COUNT), $url, $pg);

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['usuario'] = $usuario;
    $args['produtos'] = $produtosResultado;
    $args['paginacao'] = $paginacao;

    /** var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header.php', $args);
    $response = $renderer->render($response, 'home.php', $args);
    $response = $renderer->render($response, 'footer.php', $args);
    return $response;
});

$app->get('/site/{slug}/carrinho', function (Request $request, Response $response, $args) use ($app) {

    $regraLoja = new LojaBLL();
    $regraBairro = new BairroBLL();

    $loja = $regraLoja->pegarPorSlug($args['slug']);
    $usuario = UsuarioBLL::getUsuarioAtual();
    if (is_null($usuario)) {
        $url = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/login";
        return $response->withStatus(302)->withHeader('Location', $url);
    }

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

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['usuario'] = $usuario;

    /** var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header.php', $args);
    $response = $renderer->render($response, 'carrinho.php', $args);
    $response = $renderer->render($response, 'footer.php', $args);
    return $response;
});

$app->get('/site/{slug}/login', function (Request $request, Response $response, $args) use ($app) {
    return exibirLogin($app, $request, $response, $args);
});

$app->post('/site/{slug}/login', function (Request $request, Response $response, $args) use ($app) {
    $regraLoja = new LojaBLL();
    $loja = $regraLoja->pegarPorSlug($args['slug']);

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['urlResetar'] = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/resetar-senha";
    $args['urlCadastro'] = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/cadastro";
    $post = $request->getParsedBody();

    try {
        $regraUsuario = new UsuarioBLL();
        $regraUsuario->logar($post["email"], $post["senha"]);
        $url = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/carrinho";
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    catch (Exception $e) {

        $args['error'] = $e->getMessage();
        return exibirLogin($app, $request, $response, $args);
    }
});

$app->get('/site/{slug}/logoff', function (Request $request, Response $response, $args) use($app) {
    $regraUsuario = new UsuarioBLL();
    $regraUsuario->logout();
    $url = $app->getBaseUrl() . "/site/" . $args['slug'];
    return $response->withStatus(302)->withHeader('Location', $url);
});

$app->get('/site/{slug}/cadastro', function (Request $request, Response $response, $args) use ($app) {
    return exibirUsuarioNovo($app, $request, $response, $args);
});

$app->post('/site/{slug}/cadastro', function (Request $request, Response $response, $args) use ($app) {
    return gravarLogin($app, $request, $response, $args);
});

$app->get('/site/{slug}/alterar-meus-dados', function (Request $request, Response $response, $args) use ($app) {
    $usuario = UsuarioBLL::getUsuarioAtual();
    return exibirUsuarioAlterar($app, $request, $response, $args, $usuario);
});

$app->post('/site/{slug}/alterar-meus-dados', function (Request $request, Response $response, $args) use ($app) {
    return gravarLogin($app, $request, $response, $args);
});

$app->put('/api/pedido/novo', function (Request $request, Response $response, $args) use ($app) {

    try {
        $usuario = UsuarioBLL::pegarUsuarioAtual();
        if (is_null($usuario)) {
            throw new Exception("É necessário fazer o cadastro antes de efetuar o pedido.");
        }

        $json = json_decode( $request->getBody()->getContents() );
        $pedido = PedidoInfo::fromJson($json);

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegar($pedido->getIdLoja());

        $pedido->setIdUsuario($usuario->getId());
        if (!($pedido->getCodPagamento() > 0)) {
            $pedido->setCodPagamento(PedidoInfo::DINHEIRO);
        }
        $pedido->setCodSituacao(PedidoInfo::PENDENTE);
        $pedido->setCep($usuario->getCep());
        $pedido->setLogradouro($usuario->getLogradouro());
        $pedido->setComplemento($usuario->getComplemento());
        $pedido->setNumero($usuario->getNumero());
        $pedido->setBairro($usuario->getBairro());
        $pedido->setCidade($usuario->getCidade());
        $pedido->setUf($usuario->getUf());

        $regraPedido = new PedidoBLL();
        $id_pedido = $regraPedido->inserir($pedido);

        $retorno = new \stdClass();
        $retorno->id_pedido = $id_pedido;
        $retorno->url = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/finalizar-pedido/" . $id_pedido;
        return $response->withJson($retorno)->withStatus(200);
    }
    catch (Exception $erro) {
        $body = $response->getBody();
        $body->write($erro->getMessage());
        return $response->withStatus(500);
    }
});

$app->get('/site/{slug}/finalizar-pedido/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {
    $regraLoja = new LojaBLL();
    $regraPedido = new PedidoBLL();

    $loja = $regraLoja->pegarPorSlug($args['slug']);
    $usuario = UsuarioBLL::getUsuarioAtual();
    if (is_null($usuario)) {
        $url = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/login";
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    $pedido = $regraPedido->pegar($args["id_pedido"]);

    //$regraPedido->enviarEmail($pedido);

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['usuario'] = $usuario;
    $args['pedido'] = $pedido;
    $args['urlHome'] = $app->getBaseUrl() . "/site/" . $loja->getSlug();

    /** var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header.php', $args);
    $response = $renderer->render($response, 'pedido-finalizado.php', $args);
    $response = $renderer->render($response, 'footer.php', $args);
    return $response;
});

$app->get('/site/{slug}/email/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {
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

$app->get('/site/{slug}/pedidos', function (Request $request, Response $response, $args) use ($app) {
    $regraLoja = new LojaBLL();
    $regraPedido = new PedidoBLL();

    $loja = $regraLoja->pegarPorSlug($args['slug']);
    $usuario = UsuarioBLL::getUsuarioAtual();
    if (is_null($usuario)) {
        $url = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/login";
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    $pedidos = $regraPedido->listarPorUsuario($usuario->getId());

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['usuario'] = $usuario;
    $args['pedidos'] = $pedidos;

    /** var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header.php', $args);
    $response = $renderer->render($response, 'pedidos.php', $args);
    $response = $renderer->render($response, 'footer.php', $args);
    return $response;
});

$app->get('/site/{slug}/pedido/{id_pedido}', function (Request $request, Response $response, $args) use ($app) {
    $regraLoja = new LojaBLL();
    $regraPedido = new PedidoBLL();

    $loja = $regraLoja->pegarPorSlug($args['slug']);
    $usuario = UsuarioBLL::getUsuarioAtual();
    if (is_null($usuario)) {
        $url = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/login";
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    $pedido = $regraPedido->pegar($args["id_pedido"]);

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['usuario'] = $usuario;
    $args['pedido'] = $pedido;

    /** var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header.php', $args);
    $response = $renderer->render($response, 'pedido.php', $args);
    $response = $renderer->render($response, 'footer.php', $args);
    return $response;
});

$app->get('/site/{loja}/lista-de-desejos', function (Request $request, Response $response, $args) use ($app) {

    $regraLoja = new LojaBLL();

    $loja = $regraLoja->pegarPorSlug($args['loja']);
    $usuario = UsuarioBLL::getUsuarioAtual();
    if (is_null($usuario)) {
        $url = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/login";
        return $response->withStatus(302)->withHeader('Location', $url);
    }

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['usuario'] = $usuario;

    /** var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header.php', $args);
    $response = $renderer->render($response, 'lista-desejo.php', $args);
    $response = $renderer->render($response, 'footer.php', $args);
    return $response;
});

$app->get('/site/{loja}/busca', function (Request $request, Response $response, $args) use ($app) {
    $regraLoja = new LojaBLL();
    $regraProduto = new ProdutoBLL();

    $queryParams = $request->getQueryParams();
    $palavraChave = $queryParams["p"];
    $pg = intval($queryParams['pg']);
    if (!($pg > 0)) $pg = 1;

    $loja = $regraLoja->pegarPorSlug($args['loja']);
    $produtos = $regraProduto->buscar($loja->getId(), $palavraChave);

    $produtosResultado = array_slice($produtos, ($pg - 1) * MAX_PAGE_COUNT, MAX_PAGE_COUNT);

    $url = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/busca?p=" . urlencode($palavraChave) . "&pg=%s";
    $paginacao = admin_pagination((count($produtos) / MAX_PAGE_COUNT), $url, $pg);

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['usuario'] = UsuarioBLL::pegarUsuarioAtual();
    $args['palavraChave'] = $palavraChave;
    $args['produtos'] = $produtosResultado;
    $args['paginacao'] = $paginacao;

    /** var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header.php', $args);
    $response = $renderer->render($response, 'busca.php', $args);
    $response = $renderer->render($response, 'footer.php', $args);
    return $response;
});

$app->get('/site/{loja}/{categoria}', function (Request $request, Response $response, $args) use ($app) {
    $regraLoja = new LojaBLL();
    $regraCategoria = new CategoriaBLL();
    $regraProduto = new ProdutoBLL();

    $loja = $regraLoja->pegarPorSlug($args['loja']);
    $categoria = $regraCategoria->pegarPorSlug($loja->getId(), $args['categoria']);
    if (is_null($categoria)) {
        throw new Exception(sprintf("Categoria '%s' não encontrada.", $args['categoria']));
    }
    $produtos = $regraProduto->listar($loja->getId(), $categoria->getId());

    $queryParams = $request->getQueryParams();
    $pg = intval($queryParams['pg']);
    if (!($pg > 0)) $pg = 1;
    $produtosResultado = array_slice($produtos, ($pg - 1) * MAX_PAGE_COUNT, MAX_PAGE_COUNT);

    $url = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/" . $categoria->getSlug() . "?pg=%s";
    $paginacao = admin_pagination((count($produtos) / MAX_PAGE_COUNT), $url, $pg);

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['usuario'] = UsuarioBLL::pegarUsuarioAtual();
    $args['categoria'] = $categoria;
    $args['produtos'] = $produtosResultado;
    $args['paginacao'] = $paginacao;

    /** var PhpRenderer $renderer */
    $renderer = $this->get('view');
    $response = $renderer->render($response, 'header.php', $args);
    $response = $renderer->render($response, 'categoria.php', $args);
    $response = $renderer->render($response, 'footer.php', $args);
    return $response;
});