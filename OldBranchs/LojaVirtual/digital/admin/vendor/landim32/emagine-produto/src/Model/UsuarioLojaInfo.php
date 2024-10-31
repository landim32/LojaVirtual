<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 30/11/17
 * Time: 23:13
 */

namespace Emagine\Produto\Model;

use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use JsonSerializable;
use stdClass;

class UsuarioLojaInfo implements JsonSerializable
{
    private $id_loja;
    private $id_usuario;
    private $usuario = null;

    /**
     * @return int
     */
    public function getIdLoja() {
        return $this->id_loja;
    }

    /**
     * @param int $value
     */
    public function setIdLoja($value) {
        $this->id_loja = $value;
    }

    /**
     * @return int
     */
    public function getIdUsuario() {
        return $this->id_usuario;
    }

    /**
     * @param int $value
     */
    public function setIdUsuario($value) {
        $this->id_usuario = $value;
    }

    /**
     * @return UsuarioInfo|null
     */
    public function getUsuario() {
        if (is_null($this->usuario) && $this->getIdUsuario() > 0) {
            $regraUsuario = new UsuarioBLL();
            $this->usuario = $regraUsuario->pegar($this->getIdUsuario());
        }
        return $this->usuario;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize() {
        $value = new stdClass();
        $value->id_loja = $this->getIdLoja();
        $value->id_usuario = $this->getIdUsuario();
        return $value;
    }

    /**
     * @param stdClass $value
     * @return UsuarioLojaInfo
     */
    public static function fromJson($value) {
        $loja = new UsuarioLojaInfo();
        $loja->setIdLoja($value->id_loja);
        $loja->setIdUsuario($value->id_usuario);
        return $loja;
    }

}