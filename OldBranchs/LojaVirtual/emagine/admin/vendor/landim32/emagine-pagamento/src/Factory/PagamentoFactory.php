<?php
namespace Emagine\Pagamento\Factory;

use Exception;
use Emagine\Pagamento\BLL\PagamentoBLL;
use Emagine\Pagamento\BLL\PagamentoCieloBLL;
use Emagine\Pagamento\BLL\PagamentoIpagBLL;;
use Emagine\Pagamento\Iugu\BLL\PagamentoIuguBLL;
use Emagine\Pagamento\MundiPagg\BLL\PagamentoMundiPaggBLL;

class PagamentoFactory
{
    private static $pagamentoBLL = null;

    /**
     * @throws Exception
     * @return PagamentoBLL
     */
    public static function create() {
        if (is_null(PagamentoFactory::$pagamentoBLL)) {
            switch (PagamentoBLL::getTipoPagamento()) {
                case PagamentoBLL::CIELO:
                    PagamentoFactory::$pagamentoBLL = new PagamentoCieloBLL();
                    break;
                case PagamentoBLL::IPAG:
                    PagamentoFactory::$pagamentoBLL = new PagamentoIpagBLL();
                    break;
                case PagamentoBLL::IUGU:
                    PagamentoFactory::$pagamentoBLL = new PagamentoIuguBLL();
                    break;
                case PagamentoBLL::MUNDI_PAGG:
                    PagamentoFactory::$pagamentoBLL = new PagamentoMundiPaggBLL();
                    break;
                default:
                    throw new Exception("Nenhum gateway de pagamento definido.");
                    break;
            }
        }
        return PagamentoFactory::$pagamentoBLL;
    }
}