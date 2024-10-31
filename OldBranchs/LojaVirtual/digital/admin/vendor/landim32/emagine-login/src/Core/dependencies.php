<?php

namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\Factory\PermissaoFactory;
use Emagine\Login\Model\PermissaoInfo;
use Emagine\Login\Model\UsuarioInfo;
use Slim\Views\PhpRenderer;
use Emagine\Login\BLL\UsuarioBLL;

$app = EmagineApp::getApp();
if (UsuarioBLL::usaCropperJs() == true) {
    $app->addCss($app->getBaseUrl() . "/node_modules/cropper/dist/cropper.css");
    $app->addJavascriptUrl($app->getBaseUrl() . "/node_modules/cropper/dist/cropper.min.js");
}
$app->addModal(dirname(__DIR__) . "/templates/foto-modal.php");
//$app->addModal(dirname(__DIR__) . "/templates/permissao-modal.php");
$app->addModal(dirname(__DIR__) . "/templates/grupo-modal.php");

$container = $app->getContainer();
$container['loginView'] = function ($container) {
    return new PhpRenderer(dirname(__DIR__) . '/templates/');
};

$regraPermissao = PermissaoFactory::create();
$regraPermissao->registrar(new PermissaoInfo(UsuarioInfo::ADMIN, "Administrador"));
$regraPermissao->registrar(new PermissaoInfo(UsuarioInfo::GERENCIAR_USUARIO, "Gerenciar Usuário"));
$regraPermissao->registrar(new PermissaoInfo(UsuarioInfo::INCLUIR_USUARIO, "Incluir Usuário"));
$regraPermissao->registrar(new PermissaoInfo(UsuarioInfo::GERENCIAR_GRUPO, "Gerenciar Grupo"));
$regraPermissao->registrar(new PermissaoInfo(UsuarioInfo::VISUALIZAR_PERMISSAO, "Visualizar Permissões"));

