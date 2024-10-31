<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 14/11/2018
 * Time: 11:04
 * Tablename: pedido_horario
 */

namespace Emagine\Pedido;

use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Exception;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;
use Emagine\Pedido\BLL\PedidoHorarioBLL;
use Emagine\Pedido\Model\PedidoHorarioInfo;

$app = EmagineApp::getApp();

$app->get('/{slug_loja}/horarios', function (Request $request, Response $response, $args) use ($app) {
    $queryParam = $request->getQueryParams();

    $regraLoja = new LojaBLL();
    $regraHorario = new PedidoHorarioBLL();

    $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
    $regraLoja->validarPermissao($loja);
    $horarios = $regraHorario->listar($loja->getId());

    $usuarioPerfil = UsuarioPerfilFactory::create();
    $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/horarios");
    if ($usuarioPerfil instanceof LojaPerfilBLL) {
        $usuarioPerfil->setLoja($loja);
    }

    $args['app'] = $app;
    $args['loja'] = $loja;
    $args['horario'] = new PedidoHorarioInfo();
    $args['horarios'] = $horarios;
    $args['usuarioPerfil'] = $usuarioPerfil;
    if (array_key_exists("sucesso", $queryParam)) {
        $args['sucesso'] = $queryParam['sucesso'];
    }
    if (array_key_exists("erro", $queryParam)) {
        $args['erro'] = $queryParam['erro'];
    }

    /** @var PhpRenderer $rendererMain */
    $rendererMain = $this->get('view');
    /** @var PhpRenderer $rendererHorario */
    $rendererHorario = $this->get('pedidoView');

    $response = $rendererMain->render($response, 'header.php', $args);
    $response = $rendererHorario->render($response, 'horario-modal.php', $args);
    $response = $rendererHorario->render($response, 'horarios.php', $args);
    $response = $rendererMain->render($response, 'footer.php', $args);
    return $response;
});

$app->group('/{slug_loja}/horario', function () use ($app) {

	$app->get('/{id_horario}/alterar', function (Request $request, Response $response, $args) use ($app) {
		$queryParam = $request->getQueryParams();

        $regraLoja = new LojaBLL();
		$regraHorario = new PedidoHorarioBLL();

        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $regraLoja->validarPermissao($loja);

		$horario = $regraHorario->pegar($args['id_horario']);
		$args['app'] = $app;
        $args['horario'] = $horario;
		if (array_key_exists("erro", $queryParam)) {
			$args['erro'] = $queryParam['erro'];
		}

		/** @var PhpRenderer $rendererMain */
		$rendererMain = $this->get('view');
		/** @var PhpRenderer $rendererHorario */
		$rendererHorario = $this->get('pedidoView');

		$response = $rendererMain->render($response, 'header.php', $args);
		$response = $rendererHorario->render($response, 'horario-modal.php', $args);
		$response = $rendererMain->render($response, 'footer.php', $args);
		return $response;
	});

	$app->post("", function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $regraLoja->validarPermissao($loja);

		$params = $request->getParsedBody();
        $regraHorario = new PedidoHorarioBLL();

		$id_horario = intval($params['id_horario']);
        $horario = null;
		if ($id_horario > 0) {
			$horario = $regraHorario->pegar($id_horario);
		}
		if (is_null($horario)) {
			$horario = new PedidoHorarioInfo();
		}
		$horario = $regraHorario->pegarDoPost($params, $horario);
		$horario->setIdLoja($loja->getId());

        $url = $app->getBaseUrl(). "/" . $loja->getSlug() . "/horarios";
		try {
			if ($id_horario > 0) {
                $regraHorario->alterar($horario);
                $url .= "?sucesso=" . urlencode("Horário alterado com sucesso!");
			}
			else {
                $regraHorario->inserir($horario);
                $url .= "?sucesso=" . urlencode("Horário incluído com sucesso!");
			}
			return $response->withStatus(302)->withHeader('Location', $url);
		}
		catch (Exception $e) {
			$url .= "?erro=" . urlencode($e->getMessage());
			return $response->withStatus(302)->withHeader('Location', $url);
		}
	});

	$app->get('/excluir/{id_horario}', function (Request $request, Response $response, $args) use ($app) {
        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $regraLoja->validarPermissao($loja);

		$url = $app->getBaseUrl(). "/" . $loja->getSlug() . "/horarios";
		try {
			$regraPedidoHorario = new PedidoHorarioBLL();
			$regraPedidoHorario->excluir(intval($args['id_horario']));
            $url .= "?sucesso=" . urlencode("Horário excluído com sucesso!");
			return $response->withStatus(302)->withHeader('Location', $url);
		}
		catch (Exception $e) {
			$url .= "?erro=" . urlencode($e->getMessage());
			return $response->withStatus(302)->withHeader('Location', $url);
		}
	});

});

