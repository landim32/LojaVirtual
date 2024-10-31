<?php
namespace Emagine\Pagamento\Model;

use stdClass;
use JsonSerializable;

class CartaoInfo implements JsonSerializable
{
    private $id_cartao;
    private $id_usuario;
    private $bandeira;
    private $nome;
    private $token;
    private $cvv;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_cartao;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_cartao = $value;
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
     * @return int
     */
    public function getBandeira() {
        return $this->bandeira;
    }

    /**
     * @param int $value
     */
    public function setBandeira($value) {
        $this->bandeira = $value;
    }

    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @param string $value
     */
    public function setNome($value) {
        $this->nome = $value;
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @param string $value
     */
    public function setToken($value) {
        $this->token = $value;
    }

    /**
     * @return string
     */
    public function getCVV() {
        return $this->cvv;
    }

    /**
     * @param string $value
     */
    public function setCVV($value) {
        $this->cvv = $value;
    }

    /**
     * @param stdClass $value
     * @return CartaoInfo
     */
    public static function fromJson($value) {
        $cartao = new CartaoInfo();
        $cartao->setId($value->id_cartao);
        $cartao->setIdUsuario($value->id_usuario);
        $cartao->setBandeira($value->bandeira);
        $cartao->setNome($value->nome);
        $cartao->setToken($value->token);
        $cartao->setCVV($value->cvv);
        return $cartao;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $cartao = new stdClass();
        $cartao->id_cartao = $this->getId();
        $cartao->id_usuario = $this->getIdUsuario();
        $cartao->bandeira = $this->getBandeira();
        $cartao->nome = $this->getNome();
        $cartao->token = $this->getToken();
        $cartao->cvv = $this->getCVV();
        return $cartao;
    }
}