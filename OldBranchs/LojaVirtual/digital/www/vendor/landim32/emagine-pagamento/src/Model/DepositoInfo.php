<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 05/02/18
 * Time: 21:09
 */

namespace Emagine\Pagamento\Model;

use stdClass;
use JsonSerializable;

class DepositoInfo implements JsonSerializable
{
    private $id_deposito;
    private $nome;
    private $banco;
    private $agencia;
    private $conta;
    private $desconto;
    private $correntista;
    private $cpf_cnpj;
    private $mensagem;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_deposito;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_deposito = $value;
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
    public function getBanco() {
        return $this->banco;
    }

    /**
     * @param string $value
     */
    public function setBanco($value) {
        $this->nome = $value;
    }

    /**
     * @return string
     */
    public function getAgencia() {
        return $this->agencia;
    }


    /**
     * @param string $value
     */
    public function setAgencia($value) {
        $this->agencia = $value;
    }

    /**
     * @return string
     */
    public function getConta() {
        return $this->conta;
    }

    /**
     * @param string $value
     */
    public function setConta($value) {
        $this->conta = $value;
    }

    /**
     * @return double
     */
    public function getDesconto() {
        return $this->desconto;
    }

    /**
     * @param double $value
     */
    public function setDesconto($value) {
        $this->desconto = $value;
    }

    /**
     * @return string
     */
    public function getCorrentista() {
        return $this->correntista;
    }

    /**
     * @param string $value
     */
    public function setCorrentista($value) {
        $this->desconto = $value;
    }

    /**
     * @return string
     */
    public function getCpfCnpj() {
        return $this->cpf_cnpj;
    }

    /**
     * @param string $value
     */
    public function setCpfCnpj($value) {
        $this->cpf_cnpj = $value;
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
     * @return DepositoInfo
     */
    public static function fromJson($value) {
        $deposito = new DepositoInfo();
        $deposito->setId($value->id_deposito);
        $deposito->setNome($value->nome);
        $deposito->setBanco($value->banco);
        $deposito->setAgencia($value->agencia);
        $deposito->setConta($value->conta);
        $deposito->setDesconto($value->desconto);
        $deposito->setCorrentista($value->correntista);
        $deposito->setCpfCnpj($value->cpf_cnpj);
        $deposito->setMensagem($value->mensagem);
        return $deposito;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $deposito = new stdClass();
        $deposito->id_deposito = $this->getId();
        $deposito->nome = $this->getNome();
        $deposito->banco = $this->getBanco();
        $deposito->agencia = $this->getAgencia();
        $deposito->conta = $this->getConta();
        $deposito->desconto = $this->getDesconto();
        $deposito->correntista = $this->getCorrentista();
        $deposito->cpf_cnpj = $this->getCpfCnpj();
        $deposito->mensagem = $this->getMensagem();
        return $deposito;
    }
}