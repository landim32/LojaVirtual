<?php

namespace Emagine\Log;

use Exception;
use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Base\Controls\SettingCategory;
use Emagine\Base\Controls\SettingLink;
use Emagine\Log\Model\LogInfo;

$app = EmagineApp::getApp();

try {
    $usuario = UsuarioBLL::pegarUsuarioAtual();
    if (!is_null($usuario)) {
        if ($usuario->temPermissao(LogInfo::VISUALIZAR_LOG)) {

            $logSetting = $app->setSetting(new SettingCategory("logs", "Logs", "fa fa-history"));
            $logSetting->addLink(new SettingLink("log", "Logs", $app->getBaseUrl() . "/log", "fa fa-fw fa-clock-o"));
        }
    }
}
catch (Exception $erro) {
    die($erro->getMessage());
}