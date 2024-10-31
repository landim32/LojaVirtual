<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 14/02/18
 * Time: 03:17
 */

namespace Emagine\Base\Model;

use stdClass;
use JsonSerializable;

class EmailInfo implements JsonSerializable
{
    private $assunto;
    private $mensagem;

    /**
     * @return string
     */
    public function getAssunto() {
        return $this->assunto;
    }

    /**
     * @param string $value
     */
    public function setAssunto($value) {
        $this->assunto = $value;
    }

    /**
     * @return string
     */
    public function getMensagem() {
        return $this->mensagem;
    }

    /**
     * @param string $value
     */
    public function setMensagem($value) {
        $this->mensagem = $value;
    }

    /**
     * @param stdClass $value
     * @return EmailInfo
     */
    public static function fromJson($value) {
        $email = new EmailInfo();
        $email->setAssunto($value->assunto);
        $email->setMensagem($value->mensagem);
        return $email;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $value = new stdClass();
        $value->assunto = $this->getAssunto();
        $value->mensagem = $this->getMensagem();
        return $value;
    }
}