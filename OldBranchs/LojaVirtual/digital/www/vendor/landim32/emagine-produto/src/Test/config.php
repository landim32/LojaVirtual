<?php
namespace Emagine\Produto\Test;

$baseDir = dirname(dirname(__DIR__));
require $baseDir . "/core/config.php";
require $baseDir . "/vendor/landim32/emagine-base/src/Core/function.inc.php";
$loader = require $baseDir . "/vendor/autoload.php";
$loader->addPsr4('Landim32\\EasyDB\\', $baseDir . '/vendor/landim32/easydb/src');
$loader->addPsr4('Landim32\\BtMenu\\', $baseDir . '/vendor/landim32/btmenu/src');
$loader->addPsr4('Emagine\\Base\\', $baseDir . '/vendor/landim32/emagine-base/src');
$loader->addPsr4('Emagine\\Endereco\\', $baseDir . '/vendor/landim32/emagine-endereco/src');
$loader->addPsr4('Emagine\\Login\\', $baseDir . '/vendor/landim32/emagine-login/src');
$loader->addPsr4('Emagine\\Produto\\', $baseDir . '/src');