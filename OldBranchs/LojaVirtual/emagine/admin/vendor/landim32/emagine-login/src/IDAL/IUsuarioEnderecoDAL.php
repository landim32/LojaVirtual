<?php
namespace Emagine\Login\IDAL;

use Exception;
use Emagine\Login\Model\UsuarioEnderecoInfo;

interface IUsuarioEnderecoDAL {

	/**
     * @throws Exception
	 * @param int $id_usuario
	 * @return UsuarioEnderecoInfo[]
	 */
	public function listar($id_usuario);

	/**
     * @throws Exception
	 * @param UsuarioEnderecoInfo $endereco
	 */
	public function inserir($endereco);

	/**
     * @throws Exception
	 * @param UsuarioEnderecoInfo $endereco
	 */
	public function alterar($endereco);

	/**
     * @throws Exception
	 * @param int $id_endereco
	 */
	public function excluir($id_endereco);

	/**
     * @throws Exception
	 * @param int $id_usuario
	 */
	public function limpar($id_usuario);

}

