<?php

namespace Emagine\Login\Model;

use Emagine\Endereco\Model\EnderecoInfo;
use stdClass;

class UsuarioEnderecoInfo extends EnderecoInfo
{
    protected $id_endereco;
    protected $id_usuario;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_endereco;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_endereco = $value;
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
     * @param EnderecoInfo $endereco
     */
    public function clonarDe(EnderecoInfo $endereco) {
        $this->setCep($endereco->getCep());
        $this->setLogradouro($endereco->getLogradouro());
        $this->setComplemento($endereco->getComplemento());
        $this->setNumero($endereco->getNumero());
        $this->setBairro($endereco->getBairro());
        $this->setCidade($endereco->getCidade());
        $this->setUf($endereco->getUf());
        $this->setLatitude($endereco->getLatitude());
        $this->setLongitude($endereco->getLongitude());
    }

    /**
     * @param stdClass $value
     * @return UsuarioEnderecoInfo
     */
    public static function fromJson($value) {
        $endereco = new UsuarioEnderecoInfo();
        $endereco->setId($value->id_endereco);
        $endereco->setIdUsuario($value->id_usuario);
        $endereco->clonarDe(parent::fromJson($value));
        return $endereco;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $endereco = parent::jsonSerialize();
        $endereco->id_endereco = $this->getId();
        $endereco->id_usuario = $this->getIdUsuario();
        return $endereco;
    }
}