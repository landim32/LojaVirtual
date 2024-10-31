<?php
namespace Emagine\Login\IDAL;

use Exception;
use Emagine\Login\Model\UsuarioInfo;

interface IUsuarioDAL {

    /**
     * @throws Exception
     * @param int $codSituacao
     * @return UsuarioInfo[]
     */
    public function listar($codSituacao = 0);

    /**
     * @throws Exception
     * @param string $palavraChave
     * @return UsuarioInfo[]
     */
    public function buscaPorPalavra($palavraChave);

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return UsuarioInfo
     */
    public function pegar($id_usuario);

    /**
     * @throws Exception
     * @param string $slug
     * @return UsuarioInfo
     */
    public function pegarPorSlug($slug);

    /**
     * @throws Exception
     * @param string $email
     * @return UsuarioInfo
     */
    public function pegarPorEmail($email);

    /**
     * @param string $email
     * @param string $senha
     * @return UsuarioInfo
     */
    public function pegarPorLogin($email, $senha);

    /**
     * @throws Exception
     * @param UsuarioInfo $usuario
     * @return int
     */
    public function inserir($usuario);

    /**
     * @throws Exception
     * @param UsuarioInfo $usuario
     */
    public function alterar($usuario);

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param string $senha
     */
    public function alterarSenha($id_usuario, $senha);

    /**
     * @throws Exception
     * @param int $id_usuario
     */
    public function excluir($id_usuario);

    /**
     * @throws Exception
     * @param int $id_usuario
     */
    public function limparGrupoPorIdUsuario($id_usuario);

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param int $id_grupo
     */
    public function adicionarGrupo($id_usuario, $id_grupo);
}

