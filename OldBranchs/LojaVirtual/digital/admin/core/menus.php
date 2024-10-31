<?php

namespace Emagine\Pedido;

use Emagine\Base\EmagineApp;
use Landim32\BtMenu\BtMenu;

$app = EmagineApp::getApp();

$mainMenu = $app->getMenu("main");
$mainMenu->insertMenu(new BtMenu("Home", $app->getBaseUrl(), "fa fa-home"));

$menuLateral = $app->getMenu("lateral");
$menuLateral->insertMenu(new BtMenu("Home", $app->getBaseUrl(), "fa fa-home"));