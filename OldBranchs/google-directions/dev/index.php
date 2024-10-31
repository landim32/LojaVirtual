<?php

namespace Landim32\GoogleDirectionApi;

$loader = require __DIR__ . "/vendor/autoload.php";
$loader->addPsr4('Landim32\\GoogleDirectionApi\\', __DIR__ . '/src');

use Landim32\GoogleDirectionApi\BLL\GoogleDirectionApi;

define("GOOGLE_MAPS_API", "AIzaSyBgrWD-mJvKK7DJbRFKECMxxUYXJXgHp-I");

$gd = new GoogleDirectionApi(GOOGLE_MAPS_API);

$gd->setOrigin("-23.494900,-46.874044");
$gd->setDestination("-23.514798,-46.874629");
$response = $gd->execute();

echo "<pre>";
var_dump($response);
echo "</pre>";
