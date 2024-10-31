<?php
namespace Emagine\Social\Model;

use stdClass;
use Exception;
use JsonSerializable;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;

class MensagemInfo implements JsonSerializable
{
    private $id_mensagem;
    private $id_usuario;
    private $id_autor;
    private $data_inclusao;
    private $lido;
    private $mensagem;
    private $url;

    private $usuario = null;
    private $autor = null;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_mensagem;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setId($value) {
        $this->id_mensagem = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUsuario() {
        return $this->id_usuario;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setIdUsuario($value) {
        $this->id_usuario = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdAutor() {
        return $this->id_autor;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setIdAutor($value) {
        $this->id_autor = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataInclusao() {
        return $this->data_inclusao;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDataInclusao($value) {
        $this->data_inclusao = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getLido() {
        return $this->lido;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setLido($value) {
        $this->lido = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getMensagem() {
        return $this->mensagem;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMensagem($value) {
        $this->mensagem = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setUrl($value) {
        $this->url = $value;
        return $this;
    }

    /**
     * @throws Exception
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
     * @throws Exception
     * @return UsuarioInfo|null
     */
    public function getAutor() {
        if (is_null($this->autor) && $this->getIdAutor() > 0) {
            $regraUsuario = new UsuarioBLL();
            $this->autor = $regraUsuario->pegar($this->getIdAutor());
        }
        return $this->autor;
    }

    /**
     * @param stdClass $value
     * @return MensagemInfo
     */
    public static function fromJson($value) {
        $mensagem = new MensagemInfo();
        $mensagem->setId($value->id_mensagem);
        $mensagem->setIdUsuario($value->id_usuario);
        $mensagem->setIdAutor($value->id_autor);
        $mensagem->setDataInclusao($value->data_inclusao);
        $mensagem->setLido($value->lido);
        $mensagem->setMensagem($value->mensagem);
        return $mensagem;
    }

    /**
     * @throws Exception
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $mensagem = new stdClass();
        $mensagem->id_mensagem = $this->getId();
        $mensagem->id_usuario = $this->getIdUsuario();
        $mensagem->id_autor = $this->getIdAutor();
        $mensagem->data_inclusao = $this->getDataInclusao();
        $mensagem->lido = $this->getLido();
        $mensagem->mensagem = $this->getMensagem();
        $mensagem->usuario = $this->getUsuario()->jsonSerialize();
        $mensagem->autor = null;
        if (!is_null($this->getAutor())) {
            $mensagem->autor = $this->getAutor()->jsonSerialize();
        }
        $mensagem->url = $this->getUrl();
        return $mensagem;
    }
}