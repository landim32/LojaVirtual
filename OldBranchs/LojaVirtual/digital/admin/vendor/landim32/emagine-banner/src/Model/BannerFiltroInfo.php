<?php
namespace Emagine\Banner\Model;

use stdClass;
use JsonSerializable;

class BannerFiltroInfo implements JsonSerializable
{
    const POR_ORDEM = "ordem";
    const ALEATORIO = "aleatorio";

    private $id_banner = null;
    private $slug_banner = null;
    private $id_loja = null;
    private $id_seguimento = null;
    private $indice = null;
    private $quantidade = null;
    private $latitude = null;
    private $longitude = null;
    private $raio = null;
    private $ordem = null;

    /**
     * @return int|null
     */
    public function getIdBanner() {
        return $this->id_banner;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function setIdBanner($value) {
        $this->id_banner = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlugBanner() {
        return $this->slug_banner;
    }

    /**
     * @param string|null $value
     * @return $this
     */
    public function setSlugBanner($value) {
        $this->slug_banner = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdLoja() {
        return $this->id_loja;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function setIdLoja($value) {
        $this->id_loja = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdSeguimento() {
        return $this->id_seguimento;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function setIdSeguimento($value) {
        $this->id_seguimento = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIndice() {
        return $this->indice;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function setIndice($value) {
        $this->indice = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantidade() {
        return $this->quantidade;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function setQuantidade($value) {
        $this->quantidade = $value;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * @param float|null $value
     * @return $this
     */
    public function setLatitude($value) {
        $this->latitude = $value;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * @param float|null $value
     * @return $this
     */
    public function setLongitude($value) {
        $this->longitude = $value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRaio() {
        return $this->raio;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function setRaio($value) {
        $this->raio = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrdem() {
        return $this->ordem;
    }

    /**
     * @param string|null $value
     * @return $this
     */
    public function setOrdem($value) {
        $this->ordem = $value;
        return $this;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize() {
        $value = new stdClass();
        $value->id_banner = $this->getIdBanner();
        $value->slug_banner = $this->getSlugBanner();
        $value->id_loja = $this->getIdLoja();
        $value->id_seguimento = $this->getIdSeguimento();
        $value->indice = $this->getIndice();
        $value->quantidade = $this->getQuantidade();
        $value->latitude = $this->getLatitude();
        $value->longitude = $this->getLongitude();
        $value->raio = $this->getRaio();
        $value->ordem = $this->getOrdem();
        return $value;
    }

    /**
     * @param stdClass $value
     * @return BannerFiltroInfo
     */
    public static function fromJson($value) {
        $filtro = new BannerFiltroInfo();
        $filtro->setIdBanner($value->id_banner);
        $filtro->setSlugBanner($value->slug_banner);
        $filtro->setIdLoja($value->id_loja);
        $filtro->setIdSeguimento($value->id_seguimento);
        $filtro->setIndice($value->indice);
        $filtro->setQuantidade($value->quantidade);
        $filtro->setLatitude($value->latitude);
        $filtro->setLongitude($value->longitude);
        $filtro->setRaio($value->raio);
        $filtro->setOrdem($value->ordem);
        return $filtro;
    }
}