<?php
namespace Emagine\Produto\BLL;

use Emagine\Produto\DAL\LojaDAL;
use Emagine\Produto\DAL\ProdutoUsuarioDAL;
use Emagine\Produto\DAL\UsuarioLojaDAL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\UsuarioLojaInfo;
use Exception;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Login\BLL\UsuarioBLL;

class ProdutoUsuarioBLL extends UsuarioBLL
{
    /**
     * @param int $codSituacao
     * @return UsuarioInfo[]
     * @throws Exception
     */
    public function listar($codSituacao = 0)
    {
        $usuario = UsuarioBLL::pegarUsuarioAtual();
        if (is_null($usuario)) {
            return array();
        }
        if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
            return parent::listar($codSituacao);
        }
        $dalLoja = new LojaDAL();
        $lojas = $dalLoja->listarPorUsuario($usuario->getId());
        if (count($lojas) > 0) {
            /** @var LojaInfo $loja */
            $loja = array_values($lojas)[0];

            $dalUsuario = new ProdutoUsuarioDAL();
            return $dalUsuario->listarPorLoja($loja->getId(), $codSituacao);;
        }
        return array();
    }

    /**
     * @param UsuarioInfo $usuario
     * @param bool $transaction
     * @return int
     * @throws Exception
     */
    public function inserir($usuario, $transaction = true)
    {
        $id_usuario = parent::inserir($usuario, $transaction);

        $usuario = UsuarioBLL::pegarUsuarioAtual();
        if (!is_null($usuario)) {
            $dalLoja = new LojaDAL();
            $lojas = $dalLoja->listarPorUsuario($usuario->getId());
            if (count($lojas) > 0) {
                /** @var LojaInfo $loja */
                $loja = array_values($lojas)[0];

                $usuarioLoja = new UsuarioLojaInfo();
                $usuarioLoja->setIdLoja($loja->getId());
                $usuarioLoja->setIdUsuario($id_usuario);

                $dalUsuario = new UsuarioLojaDAL();
                $dalUsuario->inserir($usuarioLoja);
            }
        }
        return $id_usuario;
    }
}