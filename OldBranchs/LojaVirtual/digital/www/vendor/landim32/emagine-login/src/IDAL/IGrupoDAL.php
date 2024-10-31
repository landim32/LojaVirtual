<?php
namespace Emagine\Login\IDAL;

use Exception;
use Emagine\Login\Model\GrupoInfo;


interface IGrupoDAL {

	/**
     * @throws Exception
	 * @return GrupoInfo[]
	 */
	public function listar();

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return GrupoInfo[]
     */
    public function listarPorIdUsuario($id_usuario);

    /**
     * @throws Exception
     * @param int $id_grupo
     * @return string[]
     */
    public function listarPermissaoPorIdGrupo($id_grupo);

	/**
     * @throws Exception
	 * @param int $id_grupo
	 * @return GrupoInfo
	 */
	public function pegar($id_grupo);

	/**
     * @throws Exception
	 * @param GrupoInfo $grupo
	 * @return int
	 */
	public function inserir($grupo);

	/**
     * @throws Exception
	 * @param GrupoInfo $grupo
	 */
	public function alterar($grupo);

	/**
     * @throws Exception
	 * @param int $id_grupo
	 */
	public function excluir($id_grupo);

    /**
     * @throws Exception
     * @param int $id_grupo
     */
    public function limparPermissaoPorIdGrupo($id_grupo);

    /**
     * @throws Exception
     * @param int $id_grupo
     * @param string $slug
     */
    public function adicionarPermissao($id_grupo, $slug);
}

