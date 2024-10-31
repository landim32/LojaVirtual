<?php

namespace Emagine\Endereco\Model;

use stdClass;
use JsonSerializable;

class EnderecoInfo implements JsonSerializable
{
    protected $cep;
    protected $logradouro;
    protected $complemento;
    protected $numero;
    protected $bairro;
    protected $cidade;
    protected $uf;
    protected $latitude;
    protected $longitude;

    /**
     * @return string
     */
    public function getCep() {
        return $this->cep;
    }

    /**
     * @param string $value
     */
    public function setCep($value) {
        $this->cep = $value;
    }

    /**
     * @return string
     */
    public function getLogradouro() {
        return $this->logradouro;
    }

    /**
     * @param string $value
     */
    public function setLogradouro($value) {
        $this->logradouro = $value;
    }

    /**
     * @return string
     */
    public function getComplemento() {
        return $this->complemento;
    }

    /**
     * @param string $value
     */
    public function setComplemento($value) {
        $this->complemento = $value;
    }

    /**
     * @return string
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * @param string $value
     */
    public function setNumero($value) {
        $this->numero = $value;
    }

    /**
     * @return string
     */
    public function getBairro() {
        return $this->bairro;
    }

    /**
     * @param string $value
     */
    public function setBairro($value) {
        $this->bairro = $value;
    }

    /**
     * @return string
     */
    public function getCidade() {
        return $this->cidade;
    }

    /**
     * @param string $value
     */
    public function setCidade($value) {
        $this->cidade = $value;
    }

    /**
     * @return string
     */
    public function getUf() {
        return $this->uf;
    }

    /**
     * @param string $value
     */
    public function setUf($value) {
        $this->uf = $value;
    }

    /**
     * @return float
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * @param float $value
     */
    public function setLatitude($value) {
        $this->latitude = $value;
    }

    /**
     * @return float
     */
    public function getLongitude(){
        return $this->longitude;
    }

    /**
     * @param float $value
     */
    public function setLongitude($value) {
        $this->longitude = $value;
    }

    /**
     * @return string
     */
    public function getPosicao() {
        if ($this->getLatitude() != 0 && $this->getLongitude() != 0) {
            $posicao  = number_format($this->getLatitude(), 5, ".", "");
            $posicao .= ",";
            $posicao .= number_format($this->getLongitude(), 5, ".", "");
            return $posicao;
        }
        return "";
    }

    /**
     * @var bool $cep
     * @var bool $posicao
     * @return string
     */
    public function getEnderecoCompleto($cep = true, $posicao = true) {
        $endereco = array();
        if (!isNullOrEmpty($this->getLogradouro())) {
            $endereco[] = $this->getLogradouro();
        }
        if (!isNullOrEmpty($this->getComplemento())) {
            $endereco[] = $this->getComplemento();
        }
        if (!isNullOrEmpty($this->getNumero())) {
            $endereco[] = $this->getNumero();
        }
        if (!isNullOrEmpty($this->getBairro())) {
            $endereco[] = $this->getBairro();
        }
        if (!isNullOrEmpty($this->getCidade())) {
            $endereco[] = $this->getCidade();
        }
        if (!isNullOrEmpty($this->getUf())) {
            $endereco[] = $this->getUf();
        }
        if ($cep == true && !isNullOrEmpty($this->getCep())) {
            $endereco[] = $this->getCep();
        }
        if ($posicao == true && !isNullOrEmpty($this->getPosicao())) {
            $endereco[] = $this->getPosicao();
        }
        return implode(", ", $endereco);
    }

    /**
     * @return EnderecoInfo
     */
    public static function create() {
        return new EnderecoInfo();
    }

    /**
     * @param stdClass $value
     * @return EnderecoInfo
     */
    public static function fromJson($value) {
        $endereco = EnderecoInfo::create();
        $endereco->setCep($value->cep);
        $endereco->setLogradouro($value->logradouro);
        $endereco->setComplemento($value->complemento);
        $endereco->setNumero($value->numero);
        $endereco->setBairro($value->bairro);
        $endereco->setCidade($value->cidade);
        $endereco->setUf($value->uf);
        $endereco->setLatitude($value->latitude);
        $endereco->setLongitude($value->longitude);
        return $endereco;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $endereco = new stdClass();
        $endereco->cep = $this->getCep();
        $endereco->logradouro = $this->getLogradouro();
        $endereco->complemento = $this->getComplemento();
        $endereco->numero = $this->getNumero();
        $endereco->bairro = $this->getBairro();
        $endereco->cidade = $this->getCidade();
        $endereco->uf = $this->getUf();
        $endereco->latitude = floatval($this->getLatitude());
        $endereco->longitude = floatval($this->getLongitude());
        return $endereco;
    }
}