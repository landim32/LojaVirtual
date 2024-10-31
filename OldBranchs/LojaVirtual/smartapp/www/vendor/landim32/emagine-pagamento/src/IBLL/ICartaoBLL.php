<?php
namespace Emagine\Pagamento\IBLL;

use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pagamento\Model\CartaoInfo;

interface ICartaoBLL
{
    /**
     * @param int $id_usuario
     * @return CartaoInfo[]
     */
    public function listar($id_usuario);

    /**
     * @param int $id_cartao
     * @return CartaoInfo
     */
    public function pegar($id_cartao);

    /**
     * @param string $token
     * @return CartaoInfo
     */
    public function pegarPorToken($token);

    /**
     * @param CartaoInfo $cartao
     * @return string
     */
    public function inserir($cartao);

    /**
     * @param PagamentoInfo $pagamento
     * @return string
     */
    public function inserirDoPagamento($pagamento);

    /**
     * @param CartaoInfo $cartao
     */
    public function alterar($cartao);

    /**
     * @param int $id_cartao
     */
    public function excluir($id_cartao);
}