<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/07/2017
 * Time: 13:12
 * Tablename: grupo
 */

namespace Emagine\Login\BLL;

use Emagine\Login\DALFactory\GrupoDALFactory;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Login\DAL\GrupoDAL;
use Emagine\Login\Model\GrupoInfo;

/**
 * Class GrupoBLL
 * @package EmagineAuth\BLL
 * @tablename grupo
 * @author EmagineCRUD
 */
class GrupoBLL {

	/**
     * @throws Exception
	 * @return GrupoInfo[]
	 */
	public function listar() {
		$dal = GrupoDALFactory::create();
		return $dal->listar();
	}

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return GrupoInfo[]
     */
    public function listarPorIdUsuario($id_usuario) {
        $dal = GrupoDALFactory::create();
        return $dal->listarPorIdUsuario($id_usuario);
    }

	/**
     * @throws Exception
	 * @param int $id_grupo
	 * @return GrupoInfo
	 */
	public function pegar($id_grupo) {
		$dal = GrupoDALFactory::create();
		return $dal->pegar($id_grupo);
	}

	/**
	 * @throws Exception
	 * @param GrupoInfo $grupo
	 */
	protected function validar(&$grupo) {
		if (isNullOrEmpty($grupo->getNome())) {
			throw new Exception('Preencha o campo Nome.');
		}
	}

	/**
	 * @throws Exception
	 * @param GrupoInfo $grupo
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($grupo, $transaction = true) {
		$id_grupo = 0;
		$this->validar($grupo);
		$dal = GrupoDALFactory::create();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$id_grupo = $dal->inserir($grupo);
			foreach ($grupo->listarPermissao() as $item) {
				$dal->adicionarPermissao($id_grupo, $item->getSlug());
			}
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
		return $id_grupo;
	}

	/**
	 * @throws Exception
	 * @param GrupoInfo $grupo
	 * @param bool $transaction
	 */
	public function alterar($grupo, $transaction = true) {
		$this->validar($grupo);
		$dal = GrupoDALFactory::create();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($grupo);
			$id_grupo = $grupo->getId();
			$grupo->listarPermissao();
            $dal->limparPermissaoPorIdGrupo($grupo->getId());
			foreach ($grupo->listarPermissao() as $item) {
                $dal->adicionarPermissao($id_grupo, $item->getSlug());
			}
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
	}

	/**
	 * @throws Exception
	 * @param int $id_grupo
	 * @param bool $transaction
	 */
	public function excluir($id_grupo, $transaction = true) {
		$dal = GrupoDALFactory::create();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->limparPermissaoPorIdGrupo($id_grupo);
			$dal->excluir($id_grupo);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
	}

}

