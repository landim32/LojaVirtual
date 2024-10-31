<?php
namespace Emagine\Login\BLL;

use Exception;

use Emagine\Base\EmagineApp;

class UsuarioPerfilBLL
{
    private $urlFormato;

    /**
     * @return string
     */
    public function getUrlFormato() {
        return $this->urlFormato;
    }

    /**
     * @param string $value
     */
    public function setUrlFormato($value) {
        $this->urlFormato = $value;
    }

    /**
     * @throws Exception
     * @param string $urlFormato
     * @return string
     */
    public function render($urlFormato = "") {
        $output = "";
        try {
            $usuario = UsuarioBLL::pegarUsuarioAtual();
            if (!is_null($usuario)) {
                ob_start();
                $app = EmagineApp::getApp();
                include dirname(__DIR__) . "/templates/perfil.php";
                $output = ob_get_clean();
            }
        } catch(Exception $e) {
            ob_end_clean();
            throw $e;
        }
        return $output;
    }
}