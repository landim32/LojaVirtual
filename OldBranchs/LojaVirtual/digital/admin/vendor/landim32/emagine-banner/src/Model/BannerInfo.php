<?php
namespace Emagine\Banner\Model;

use stdClass;
use JsonSerializable;

/**
 * Class BannerInfo
 * @package Emagine\Banner\Model
 * @tablename banner
 * @author EmagineCRUD
 */
class BannerInfo implements JsonSerializable {

    const GERENCIAR_BANNER = "gerenciar-banner";

    const NORMAL = 1;
    const ADMIN = 2;

	private $id_banner;
	private $cod_tipo;
	private $slug;
	private $nome;
	private $largura;
	private $altura;
	private $quantidade_loja;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_banner;
	}

	/**
	 * @param int $value
     * @return $this
	 */
	public function setId($value) {
		$this->id_banner = $value;
		return $this;
	}

    /**
     * Auto Increment Field
     * @return int
     */
    public function getCodTipo() {
        return $this->cod_tipo;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setCodTipo($value) {
        $this->cod_tipo = $value;
        return $this;
    }

	/**
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @param string $value
     * @return $this
	 */
	public function setSlug($value) {
		$this->slug = $value;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNome() {
		return $this->nome;
	}

	/**
	 * @param string $value
     * @return $this
	 */
	public function setNome($value) {
		$this->nome = $value;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLargura() {
		return $this->largura;
	}

	/**
	 * @param int $value
     * @return $this
	 */
	public function setLargura($value) {
		$this->largura = $value;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAltura() {
		return $this->altura;
	}

	/**
	 * @param int $value
     * @return $this
	 */
	public function setAltura($value) {
		$this->altura = $value;
		return $this;
	}

    /**
     * @return int
     */
    public function getQuantidadeLoja() {
        return $this->quantidade_loja;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setQuantidadeLoja($value) {
        $this->quantidade_loja = $value;
        return $this;
    }

	/**
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_banner = $this->getId();
		$value->cod_tipo = $this->getCodTipo();
		$value->slug = $this->getSlug();
		$value->nome = $this->getNome();
		$value->largura = $this->getLargura();
		$value->altura = $this->getAltura();
		$value->quantidade_loja = $this->getQuantidadeLoja();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return BannerInfo
	 */
	public static function fromJson($value) {
		$banner = new BannerInfo();
		$banner->setId($value->id_banner);
		$banner->setCodTipo($value->cod_tipo);
		$banner->setSlug($value->slug);
		$banner->setNome($value->nome);
		$banner->setLargura($value->largura);
		$banner->setAltura($value->altura);
		$banner->setQuantidadeLoja($value->quantidade_loja);
		return $banner;
	}

}

