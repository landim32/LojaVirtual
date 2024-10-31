<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 01/03/2018
 * Time: 17:33
 */

namespace Emagine\Base\Model;

use stdClass;
use JsonSerializable;

class FormMailInfo implements JsonSerializable
{
    private $nome;
    private $email;
    private $assunto;
    private $mensagem;
    private $campos = array();

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getAssunto()
    {
        return $this->assunto;
    }

    /**
     * @param string $assunto
     */
    public function setAssunto($assunto)
    {
        $this->assunto = $assunto;
    }

    /**
     * @return string
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * @param string $mensagem
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
    }

    /**
     * @return string[]
     */
    public function getCampos()
    {
        return $this->campos;
    }

    /**
     * @param string[] $campos
     */
    public function setCampos($campos)
    {
        $this->campos = $campos;
    }

    /**
     * @param stdClass $value
     * @return FormMailInfo
     */
    public static function fromJson($value) {
        $retorno = new FormMailInfo();
        $retorno->setNome($value->nome);
        $retorno->setEmail($value->email);
        $retorno->setAssunto($value->assunto);
        $retorno->setMensagem($value->mensagem);
        $retorno->setCampos($value->campos);
        return $retorno;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $retorno = new stdClass();
        $retorno->nome = $this->getNome();
        $retorno->email = $this->getEmail();
        $retorno->assunto = $this->getAssunto();
        $retorno->mensagem = $this->getMensagem();
        $retorno->campos = $this->getCampos();
        return $retorno;
    }
}