<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/07/2017
 * Time: 13:13
 * Tablename: permissao
 */

namespace Emagine\Login\Model;

use stdClass;
use JsonSerializable;

/**
 * Class PermissaoInfo
 * @package EmagineAuth\Model
 * @tablename permissao
 * @author EmagineCRUD
 */
class PermissaoInfo implements JsonSerializable {

	private $slug;
	private $nome;

    /**
     * PermissaoInfo constructor.
     * @param string $slug
     * @param string $nome
     */
	public function __construct($slug, $nome)
    {
        $this->slug = $slug;
        $this->nome = $nome;
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
		$value = new stdClass();
		$value->slug = $this->getSlug();
		$value->nome = $this->getNome();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return PermissaoInfo
	 */
	public static function fromJson($value) {
		$permissao = new PermissaoInfo($value->slug, $value->nome);
		return $permissao;
	}

}

