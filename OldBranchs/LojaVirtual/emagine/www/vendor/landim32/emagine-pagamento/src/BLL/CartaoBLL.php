<?php
namespace Emagine\Pagamento\BLL;

use Exception;
use Emagine\Pagamento\IBLL\ICartaoBLL;
use Emagine\Pagamento\DAL\CartaoDAL;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pagamento\Model\CartaoInfo;

class CartaoBLL implements ICartaoBLL
{
    /**
     * @param int $id_usuario
     * @return CartaoInfo[]
     * @throws Exception
     */
    public function listar($id_usuario) {
        $dal = new CartaoDAL();
        return $dal->listar($id_usuario);
    }

    /**
     * @param int $id_cartao
     * @return CartaoInfo
     * @throws Exception
     */
    public function pegar($id_cartao) {
        $dal = new CartaoDAL();
        return $dal->pegar($id_cartao);
    }

    /**
     * @param string $token
     * @return CartaoInfo
     * @throws Exception
     */
    public function pegarPorToken($token) {
        $dal = new CartaoDAL();
        return $dal->pegarPorToken($token);
    }

    /**
     * @throws Exception
     * @param CartaoInfo $cartao
     */
    private function validar(&$cartao) {
        if (is_null($cartao)) {
            throw new Exception("Nenhum dado de cartão informado.");
        }
        if (!($cartao->getIdUsuario() > 0)) {
            throw new Exception("O cartão não está vinculado ao usuário.");
        }
        if (!($cartao->getBandeira() > 0)) {
            throw new Exception("Informe a bandeira do cartão.");
        }
        if (isNullOrEmpty($cartao->getNome())) {
            throw new Exception("Informe o nome do cartão.");
        }
        if (isNullOrEmpty($cartao->getCVV())) {
            throw new Exception("Informe o código de verificação.");
        }
        if (isNullOrEmpty($cartao->getToken())) {
            throw new Exception("Informe o token.");
        }
    }

    /**
     * @throws Exception
     * @param CartaoInfo $cartao
     * @return int
     */
    public function inserir($cartao) {
        $this->validar($cartao);
        $dal = new CartaoDAL();
        return $dal->inserir($cartao);
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @return int
     */
    public function inserirDoPagamento($pagamento) {

        $cartao = $this->pegarPorToken($pagamento->getToken());

        if (!is_null($cartao)) {
            return $cartao->getId();
        }
        else {

            $nome = $pagamento->getNumeroCartao();
            $nome = substr($nome, -4, 4);

            $cartao = new CartaoInfo();
            $cartao->setIdUsuario($pagamento->getIdUsuario());
            $cartao->setBandeira($pagamento->getCodBandeira());
            $cartao->setNome($nome);
            $cartao->setToken($pagamento->getToken());
            $cartao->setCVV($pagamento->getCVV());

            return $this->inserir($cartao);
        }
    }

    /**
     * @throws Exception
     * @param CartaoInfo $cartao
     */
    public function alterar($cartao) {
        $this->validar($cartao);
        $dal = new CartaoDAL();
        $dal->alterar($cartao);
    }

    /**
     * @throws Exception
     * @param int $id_cartao
     */
    public function excluir($id_cartao) {
        $dal = new CartaoDAL();
        $dal->excluir($id_cartao);
    }
}