<?php
namespace Emagine\Produto\BLL;

use Emagine\Log\Model\LogRetornoInfo;
use Exception;
use Emagine\Log\BLL\LogBLL;
use Emagine\Log\Model\LogFiltroInfo;
use Emagine\Log\Model\LogInfo;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\DAL\LojaDAL;
use Emagine\Produto\Model\LojaInfo;

class ProdutoLogBLL extends LogBLL
{
    /**
     * @param LogFiltroInfo $filtro
     * @return LogRetornoInfo
     * @throws Exception
     */
    public function listar(LogFiltroInfo $filtro)
    {
        $usuario = UsuarioBLL::pegarUsuarioAtual();
        if (is_null($usuario)) {
            return (new LogRetornoInfo())
                ->setTotal(0)
                ->setPagina(0)
                ->setTamanhoPagina(0)
                ->setLogs(array());
        }
        if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
            return parent::listar($filtro);
        }
        $dalLoja = new LojaDAL();
        $lojas = $dalLoja->listarPorUsuario($usuario->getId());
        if (count($lojas) > 0) {
            /** @var LojaInfo $loja */
            $loja = array_values($lojas)[0];
            $filtro->setIdLoja($loja->getId());
        }
        return parent::listar($filtro);
    }

    /**
     * @throws Exception
     * @param LogInfo $log
     * @param bool $transaction
     * @return int
     */
    public function inserir($log, $transaction = true)
    {
        $usuario = UsuarioBLL::pegarUsuarioAtual();
        if (!is_null($usuario)) {
            if (!($log->getIdUsuario() > 0)) {
                $log->setIdUsuario($usuario->getId());
            }
            if (!($log->getIdLoja() > 0)) {
                $dalLoja = new LojaDAL();
                $lojas = $dalLoja->listarPorUsuario($usuario->getId());
                if (count($lojas) > 0) {
                    /** @var LojaInfo $loja */
                    $loja = array_values($lojas)[0];
                    $log->setIdLoja($loja->getId());
                }
            }
        }
        return parent::inserir($log, $transaction);
    }
}