<?php

namespace Emagine\Base\Core;

use Emagine\Base\BLL\FormMailBLL;
use Emagine\Base\BLL\MailJetBLL;
use Emagine\Base\BLL\ReCaptchaBLL;
use Emagine\Base\Controls\SettingView;
use Emagine\Base\Model\EmailInfo;
use Exception;
use Emagine\Base\BLL\EmailBLL;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Emagine\Base\EmagineApp;

$app = EmagineApp::getApp();

$app->get('/painel-controle', function (Request $request, Response $response, $args) use ($app) {
    $args['app'] = $app;
    /** @var PhpRenderer $rendererBase */
    $rendererBase = $this->get('baseView');
    /** @var PhpRenderer $rendererMain */
    $rendererMain = $this->get('view');
    $response = $rendererMain->render($response, 'header.php', $args);
    $configView = new SettingView($request, $response);
    $configView->setSettings($app->getSettings());
    $response = $configView->render($args);
    $response = $rendererMain->render($response, 'footer.php', $args);
    return $response;
});

$app->put('/api/mensagem/enviar', function (Request $request, Response $response, $args) use ($app) {
    try {
        $json = $request->getBody()->getContents();
        $email = EmailInfo::fromJson(json_decode($json));

        $regraEmail = new MailJetBLL();
        $regraEmail->send("rodrigo@emagine.com.br", $email->getAssunto(), $email->getMensagem(), "App", "rodrigo@emagine.com.br");
        $body = $response->getBody();
        $body->write("ok");
        return $response->withStatus(200);
    }
    catch (Exception $e) {
        $body = $response->getBody();
        $body->write($e->getMessage());
        return $response->withStatus(500);
    }
});

$app->post('/formmail', function (Request $request, Response $response, $args) use ($app) {
    try {
        $regraEmail = new FormMailBLL();

        $postParam = $request->getParsedBody();
        $formmail = $regraEmail->pegarDoPost($postParam);
        $regraEmail->enviar($formmail);

        $body = $response->getBody();
        $body->write("ok");
        return $response->withStatus(200);
    }
    catch (Exception $e) {
        $body = $response->getBody();
        $body->write($e->getMessage());
        return $response->withStatus(500);
    }
});