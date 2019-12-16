<?php
namespace Emagine\Produto\Model;

use stdClass;
use JsonSerializable;

class UnidadeInfo implements JsonSerializable
{
    const GERENCIAR_UNIDADE = "gerenciar-unidade";

    private $id_unidade;
    private $id_loja;
    private $slug;
    private $nome;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_unidade;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_unidade = $value;
    }

    /**
     * @return int
     */
    public function getIdLoja() {
        return $this->id_loja;
    }

    /**
     * @param int $value
     */
    public function setIdLoja($value) {
        $this->id_loja = $value;
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
     * @return stdClass
     */
    public function jsonSerialize() {
        $unidade = new stdClass();
        $unidade->id_unidade = $this->getId();
        $unidade->id_loja = $this->getIdLoja();
        $unidade->slug = $this->getSlug();
        $unidade->nome = $this->getNome();
        return $unidade;
    }

    /**
     * @param stdClass $value
     * @return UnidadeInfo
     */
    public static function fromJson($value) {
        $unidade = new UnidadeInfo();
        $unidade->setId($value->id_unidade);
        $unidade->setIdLoja($value->id_loja);
        $unidade->setSlug($value->slug);
        $unidade->setNome($value->nome);
        return $unidade;
    }
}