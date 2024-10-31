<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/07/2017
 * Time: 13:12
 * Tablename: grupo
 */

namespace Emagine\Login\Model;

use Emagine\Login\DALFactory\GrupoDALFactory;
use Emagine\Login\Factory\PermissaoFactory;
use stdClass;
use Exception;
use JsonSerializable;

/**
 * Class GrupoInfo
 * @package EmagineAuth\Model
 * @tablename grupo
 * @author EmagineCRUD
 */
class GrupoInfo implements JsonSerializable {

	private $id_grupo;
	private $nome;
	private $permissoes = null;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_grupo;
	}

	/**
	 * @param int $value
	 */
	public function setId($value) {
		$this->id_grupo = $value;
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
     * @throws Exception
	 * @return PermissaoInfo[]
	 */
	public function listarPermissao() {
		if (is_null($this->permissoes) && $this->getId() > 0) {
            $this->permissoes = array();
		    $regraPermissao = PermissaoFactory::create();
            $permissoes = array();
            foreach ($regraPermissao->listar() as $permissao) {
                $permissoes[$permissao->getSlug()] = $permissao;
            }
			$dalGrupo = GrupoDALFactory::create();
			foreach ($dalGrupo->listarPermissaoPorIdGrupo($this->getId()) as $slug) {
			    if (array_key_exists($slug, $permissoes)) {
                    $this->permissoes[] = $permissoes[$slug];
                }
            }
		}
        //var_dump($this->permissoes);
		return $this->permissoes;
	}

	/**
	 * Limpa todos os Permissões relacionados com Grupos.
	 */
	public function limparPermissao() {
		$this->permissoes = array();
	}

	/**
     * @throws Exception
	 * @param PermissaoInfo $permissao
	 */
	public function adicionarPermissao($permissao) {
		$this->listarPermissao();
		$this->permissoes[] = $permissao;
	}

    /**
     * @throws Exception
     * @return string
     */
	public function getPermissaoStr() {
	    $retorno = array();
        foreach ($this->listarPermissao() as $permissao) {
            $retorno[] = $permissao->getNome();
        }
        return implode(", ", $retorno);
    }

	/**
     * @throws Exception
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_grupo = $this->getId();
		$value->nome = $this->getNome();
		$value->permissao_str = $this->getPermissaoStr();
		$value->permissoes = array();
		foreach ($this->listarPermissao() as $item) {
			$value->permissoes[] = $item->jsonSerialize();
		}
		return $value;
	}

	/**
     * @throws Exception
	 * @param stdClass $value
	 * @return GrupoInfo
	 */
	public static function fromJson($value) {
		$grupo = new GrupoInfo();
		$grupo->setId($value->id_grupo);
		$grupo->setNome($value->nome);
		if (isset($value->permissoes)) {
			$grupo->limparPermissao();
			foreach ($value->permissoes as $item) {
				$grupo->adicionarPermissao(PermissaoInfo::fromJson($item));
			}
		}
		return $grupo;
	}

}

