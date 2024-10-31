<?php

namespace Emagine\Login\Controls;

use Exception;
use Emagine\Login\Factory\UsuarioPerfilFactory;

class UsuarioPerfil {

    /**
     * @deprecated Use UsuarioPerfilBLL diretamente
     * @param string $urlFormato
     * @return string
     * @throws Exception
     */
    public static function render($urlFormato = "") {
        $regraPerfil = UsuarioPerfilFactory::create();
        $regraPerfil->setUrlFormato($urlFormato);
        return $regraPerfil->render($urlFormato);
    }

}