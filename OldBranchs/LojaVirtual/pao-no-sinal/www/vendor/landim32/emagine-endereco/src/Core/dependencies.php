<?php

namespace Emagine\Endereco;

use Emagine\Base\Controls\SettingCategory;
use Emagine\Base\Controls\SettingLink;
use Emagine\Base\EmagineApp;
use Emagine\Endereco\BLL\EnderecoBLL;
use Slim\Views\PhpRenderer;

$app = EmagineApp::getApp();

$container = $app->getContainer();
$container['localidadeView'] = function ($container) {
    return new PhpRenderer(dirname(__DIR__) . '/templates/');
};

if (!EnderecoBLL::usaBaseCep()) {
    $baseUrl = $app->getBaseUrl();
    $usuarioSetting = $app->setSetting(new SettingCategory("localidade", "Localidade", "fa fa-map-marker"));
    $usuarioSetting->addLink(new SettingLink("pais", "Países", $baseUrl . "/localidade/pais/listar", "fa fa-fw fa-globe"));
    $usuarioSetting->addLink(new SettingLink("uf", "Estados", $baseUrl . "/localidade/uf/listar", "fa fa-fw fa-flag"));
    $usuarioSetting->addLink(new SettingLink("cidade", "Cidades", $baseUrl . "/localidade/cidade/listar", "fa fa-fw fa-flag"));
    $usuarioSetting->addLink(new SettingLink("bairro", "Bairros", $baseUrl . "/localidade/bairro/listar", "fa fa-fw fa-flag"));
}