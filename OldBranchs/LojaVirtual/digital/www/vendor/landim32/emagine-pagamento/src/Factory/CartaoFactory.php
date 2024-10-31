<?php
namespace Emagine\Pagamento\Factory;

use Emagine\Pagamento\BLL\CartaoBLL;
use Emagine\Pagamento\BLL\PagamentoBLL;
use Emagine\Pagamento\IBLL\ICartaoBLL;
use Emagine\Pagamento\Iugu\BLL\CartaoIuguBLL;
use Emagine\Pagamento\MundiPagg\BLL\CartaoMundiPaggBLL;

class CartaoFactory
{
    private static $cartao = null;

    /**
     * @return ICartaoBLL
     */
    public static function create() {
        if (is_null(CartaoFactory::$cartao)) {
            switch (PagamentoBLL::getTipoPagamento()) {
                case PagamentoBLL::MUNDI_PAGG:
                    CartaoFactory::$cartao = new CartaoMundiPaggBLL();
                    break;
                case PagamentoBLL::IUGU:
                    CartaoFactory::$cartao = new CartaoIuguBLL();
                    break;
                default:
                    CartaoFactory::$cartao = new CartaoBLL();
                    break;
            }
        }
        return CartaoFactory::$cartao;
    }
}