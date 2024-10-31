<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 23/03/2017
 * Time: 15:10
 */

namespace Emagine\Social\Model;

use Emagine\Login\Model\UsuarioInfo;
use Imobsync\Imovel\BLL\ImovelBLL;
use Emagine\Login\BLL\UsuarioBLL;
use Imobsync\Imovel\Model\ImovelInfo;

class NovidadeInfo
{
    const TIPO_EXCLUSIVIDADE = "exclusividade";
    const TIPO_IMOVEL = "imovel";

    private $tipo;
    private $id_imovel;
    private $id_usuario;
    private $id_conta;
    private $data_inclusao;
    private $ultima_alteracao;
    private $titulo;
    private $subtitulo;
    private $descricao;
    private $nome;
    private $foto;
    private $imobiliaria;
    private $url;

    private $imovel = null;
    private $usuario = null;

    /**
     * @return string
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * @param string $value
     */
    public function setTipo($value) {
        $this->tipo = $value;
    }

    /**
     * @return int
     */
    public function getIdImovel() {
        return $this->id_imovel;
    }

    /**
     * @param int $value
     */
    public function setIdImovel($value) {
        $this->id_imovel = $value;
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
    public function getIdConta() {
        return $this->id_conta;
    }

    /**
     * @param int $value
     */
    public function setIdConta($value) {
        $this->id_conta = $value;
    }

    /**
     * @return int
     */
    public function getDataInclusao() {
        return $this->data_inclusao;
    }

    /**
     * @param int $value
     */
    public function setDataInclusao($value) {
        $this->data_inclusao = $value;
    }

    /**
     * @return int
     */
    public function getUltimaAlteracao() {
        return $this->ultima_alteracao;
    }

    /**
     * @param int $value
     */
    public function setUltimaAlteracao($value) {
        $this->ultima_alteracao = $value;
    }

    /**
     * @return string
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * @param string $value
     */
    public function setTitulo($value) {
        $this->titulo = $value;
    }

    /**
     * @return string
     */
    public function getSubTitulo() {
        return $this->subtitulo;
    }

    /**
     * @param string $value
     */
    public function setSubTitulo($value) {
        $this->subtitulo = $value;
    }

    /**
     * @return string
     */
    public function getDescricao() {
        return $this->descricao;
    }

    /**
     * @param string $value
     */
    public function setDescricao($value) {
        $this->descricao = $value;
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
    public function getFoto() {
        return $this->foto;
    }

    /**
     * @param string $value
     */
    public function setFoto($value) {
        $this->foto = $value;
    }

    /**
     * @return string
     */
    public function getImobiliaria() {
        return $this->imobiliaria;
    }

    /**
     * @param string $value
     */
    public function setImobiliaria($value) {
        $this->imobiliaria = $value;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $value
     */
    public function setUrl($value) {
        $this->url = $value;
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
     * @param UsuarioInfo|null $value
     */
    public function setUsuario($value) {
        $this->usuario = $value;
        if (!is_null($value)) {
            $this->id_usuario = $value->getId();
        }
        else {
            $this->id_usuario = null;
        }
    }

    /**
     * @return ImovelInfo|null
     */
    public function getImovel() {
        if (is_null($this->imovel) && $this->getIdImovel() > 0) {
            $regraImovel = new ImovelBLL();
            $this->imovel = $regraImovel->pegar($this->getIdImovel());
        }
        return $this->imovel;
    }

    /**
     * @param ImovelInfo $imovel
     */
    public function setImovel($imovel) {
        $this->imovel = $imovel;
        if (!is_null($imovel)) {
            $this->id_imovel = $imovel->getId();
        }
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getUsuarioThumbnailUrl($width = 40, $height = 40) {
        if (!is_null($this->getUsuario())) {
            return $this->getUsuario()->getThumbnailUrl($width, $height);
        }
        return "/img/" . $width . "x" . $height . "/anonimo.png";
    }
}