<?php

namespace Emagine\Social;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Factory\UsuarioFactory;
use Emagine\Social\BLL\MensagemBLL;
use Emagine\Social\BLL\UsuarioSocialBLL;
use Slim\Views\PhpRenderer;

$app = EmagineApp::getApp();

if (UsuarioSocialBLL::getSocialUsaRoute() == true) {
    $app->addModal(dirname(__DIR__) . "/templates/mensagem-modal.php");
}

$container = $app->getContainer();
$container['socialView'] = function ($container) {
    return new PhpRenderer(dirname(__DIR__) . '/templates/');
};

if (MensagemBLL::getExibirAviso() == true) {
    $regraUsuario = UsuarioFactory::create();
    if ($regraUsuario->estaLogado()) {
        $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        $script  = "$(document).ready(function() {\n";
        $script .= "\t$.mensagem.exibirAviso(" . $id_usuario . ");\n";
        $script .= "\tsetInterval(function() { $.mensagem.exibirAviso(" . $id_usuario . "); }, 15000);\n";
        $script .= "});\n";
        $app->addJavascriptConteudo($script);
    }
}