<?php
namespace Emagine\Produto\BLL;

use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\Model\LojaInfo;
use Exception;
use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\BLL\UsuarioPerfilBLL;

class LojaPerfilBLL extends LojaSelecionaPerfilBLL
{
    private $loja;

    /**
     * @return LojaInfo
     */
    public function getLoja() {
        return $this->loja;
    }

    /**
     * @param LojaInfo $value
     */
    public function setLoja($value) {
        $this->loja = $value;
    }

    /**
     * @throws Exception
     * @param string $urlFormato
     * @return string
     */
    public function render($urlFormato = "")
    {
        $output = "";
        try {
            $usuario = UsuarioBLL::pegarUsuarioAtual();
            if (!is_null($usuario)) {
                $app = EmagineApp::getApp();
                if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
                    $loja = $this->getLoja();
                    if (!is_null($this->getLoja())) {
                        ob_start();
                        include dirname(__DIR__) . "/templates/loja-perfil.php";
                        $output = ob_get_clean();
                    }
                    else {
                        //return parent::render();
                        ob_start();
                        include dirname(__DIR__) . "/templates/admin-perfil.php";
                        $output = ob_get_clean();
                    }
                }
                else {
                    return parent::render();
                }
            }
        } catch(Exception $e) {
            ob_end_flush();
            throw $e;
        }
        return $output;
    }
}