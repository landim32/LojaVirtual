<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 02/03/2018
 * Time: 21:34
 */

namespace Emagine\Base\BLL;


use Emagine\Base\EmagineApp;

class ReCaptchaBLL
{
    /**
     * @return string
     */
    public function getSiteKey() {
        if (defined("RECAPTCHA_SITE_KEY")) {
            return RECAPTCHA_SITE_KEY;
        }
        return "";
    }

    /**
     * @return string
     */
    public function getSecretKey() {
        if (defined("RECAPTCHA_SECRET_KEY")) {
            return RECAPTCHA_SECRET_KEY;
        }
        return "";
    }

    /**
     * Inicializar javascript
     */
    public function inicializarJavascript() {
        $app = EmagineApp::getApp();
        $app->addJavascriptUrl("https://www.google.com/recaptcha/api.js");
    }

    /**
     * Renderiza o Recaptcha
     * @return string
     */
    public function render() {
        return "<div class=\"g-recaptcha\" data-sitekey=\"" . $this->getSiteKey() . "\"></div>";
    }

}