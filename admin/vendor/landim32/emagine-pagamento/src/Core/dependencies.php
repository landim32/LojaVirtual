<?php

namespace Emagine\Pagamento;

use Emagine\Base\Controls\SettingCategory;
use Emagine\Base\Controls\SettingLink;
use Emagine\Base\EmagineApp;
use Slim\Views\PhpRenderer;

$app = EmagineApp::getApp();

$container = $app->getContainer();
$container['pagamento'] = function ($container) {
    return new PhpRenderer(dirname(__DIR__) . '/templates/');
};