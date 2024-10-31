<?php
namespace Emagine\Produto\Model;

use stdClass;
use JsonSerializable;
use Exception;

class SeguimentoInfo implements JsonSerializable
{
    const GERENCIAR_SEGUIMENTO = "gerenciar-seguimento";

    private $id_seguimento;
    private $apenas_pj;
    private $slug;
    private $icone;
    private $nome;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_seguimento;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_seguimento = $value;
    }

    /**
     * @return bool
     */
    public function getApenasPJ() {
        return $this->apenas_pj;
    }

    /**
     * @param bool $value
     */
    public function setApenasPJ($value) {
        $this->apenas_pj = $value;
    }

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param string $value
     */
    public function setSlug($value) {
        $this->slug = $value;
    }

    /**
     * @return string
     */
    public function getIcone() {
        return $this->icone;
    }

    /**
     * @param string $value
     */
    public function setIcone($value) {
        $this->icone = $value;
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
     * @param int $largura
     * @param int $altura
     * @return string
     * @throws Exception
     */
    public function getIconeUrl($largura = 120, $altura = 120) {
        if (!defined("SITE_URL")) {
            throw new Exception("SITE_URL não foi definido.");
        }
        if (isNullOrEmpty($this->getIcone())) {
            return SITE_URL . sprintf("/img/%sx%s/anonimo.png", $largura, $altura);
        }
        return SITE_URL . sprintf("/seguimento/%sx%s/", $largura, $altura) . $this->getIcone();
    }

    /**
     * @throws Exception
     * @return stdClass
     */
    public function jsonSerialize() {
        $seguimento = new stdClass();
        $seguimento->id_seguimento = $this->getId();
        $seguimento->apenas_pj = ($this->getApenasPJ() == true);
        $seguimento->slug = $this->getSlug();
        $seguimento->icone = $this->getIcone();
        $seguimento->icone_url = $this->getIconeUrl();
        $seguimento->nome = $this->getNome();
        return $seguimento;
    }

    /**
     * @param stdClass $value
     * @return SeguimentoInfo
     */
    public static function fromJson($value) {
        $seguimento = new SeguimentoInfo();
        $seguimento->setId($value->id_unidade);
        $seguimento->setApenasPJ(($value->apenas_pj == true));
        $seguimento->setSlug($value->slug);
        $seguimento->setIcone($value->icone);
        $seguimento->setNome($value->nome);
        return $seguimento;
    }
}