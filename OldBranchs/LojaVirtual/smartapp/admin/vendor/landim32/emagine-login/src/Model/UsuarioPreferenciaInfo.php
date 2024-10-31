<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/07/2017
 * Time: 13:07
 * Tablename: usuario_preferencia
 */

namespace Emagine\Login\Model;

use Emagine\Login\Factory\UsuarioFactory;
use stdClass;
use Exception;
use JsonSerializable;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;

/**
 * Class UsuarioPreferenciaInfo
 * @package EmagineAuth\Model
 * @tablename usuario_preferencia
 * @author EmagineCRUD
 */
class UsuarioPreferenciaInfo implements JsonSerializable {

	private $id_usuario;
	private $chave;
	private $valor;
	private $usuario = null;

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
     * @throws Exception
	 * @return UsuarioInfo
	 */
	public function getUsuario() {
		if (is_null($this->usuario) && $this->getIdUsuario() > 0) {
			$regraUsuario = UsuarioFactory::create();
			$this->usuario = $regraUsuario->pegar($this->getIdUsuario());
		}
		return $this->usuario;
	}

	/**
	 * @return string
	 */
	public function getChave() {
		return $this->chave;
	}

	/**
	 * @param string $value
	 */
	public function setChave($value) {
		$this->chave = $value;
	}

	/**
	 * @return string
	 */
	public function getValor() {
		return $this->valor;
	}

	/**
	 * @param string $value
	 */
	public function setValor($value) {
		$this->valor = $value;
	}

	/**
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_usuario = $this->getIdUsuario();
		$value->chave = $this->getChave();
		$value->valor = $this->getValor();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return UsuarioPreferenciaInfo
	 */
	public static function fromJson($value) {
		$preferencia = new UsuarioPreferenciaInfo();
		$preferencia->setIdUsuario($value->id_usuario);
		$preferencia->setChave($value->chave);
		$preferencia->setValor($value->valor);
		return $preferencia;
	}

}

