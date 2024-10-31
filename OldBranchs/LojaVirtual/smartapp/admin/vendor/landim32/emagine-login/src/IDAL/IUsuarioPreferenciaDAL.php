<?php
namespace Emagine\Login\IDAL;

use Exception;
use Emagine\Login\Model\UsuarioPreferenciaInfo;

interface IUsuarioPreferenciaDAL {

	/**
     * @throws Exception
	 * @return UsuarioPreferenciaInfo[]
	 */
	public function listar();

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @return UsuarioPreferenciaInfo[]
	 */
	public function listarPorIdUsuario($id_usuario);

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @param string $chave
	 * @return UsuarioPreferenciaInfo
	 */
	public function pegar($id_usuario, $chave);

    /**
     * @throws Exception
     * @param string $chave
     * @param string $valor
     * @return UsuarioPreferenciaInfo
     */
    public function pegarPorValor($chave, $valor);

	/**
     * @throws Exception
	 * @param UsuarioPreferenciaInfo $preferencia
	 */
	public function inserir($preferencia);

	/**
     * @throws Exception
	 * @param UsuarioPreferenciaInfo $preferencia
	 */
	public function alterar($preferencia);

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @param string $chave
	 */
	public function excluir($id_usuario, $chave);

	/**
     * @throws Exception
	 * @param int $id_usuario
	 */
	public function limpar($id_usuario);

}

