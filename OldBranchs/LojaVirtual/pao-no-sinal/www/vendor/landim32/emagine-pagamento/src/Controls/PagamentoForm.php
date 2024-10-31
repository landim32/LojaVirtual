<?php
namespace Emagine\Pagamento\Controls;

use Exception;
use Emagine\Base\EmagineApp;
use Emagine\Pagamento\BLL\DepositoBLL;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pagamento\Model\DepositoInfo;

class PagamentoForm
{
    private $pagamento;
    private $aceita_credito_online = true;
    private $aceita_debito_online = true;
    private $aceita_cartao_offline = true;
    private $aceita_boleto = true;
    private $aceita_dinheiro = true;
    private $aceita_deposito = true;

    /**
     * @return PagamentoInfo
     */
    public function getPagamento() {
        return $this->pagamento;
    }

    /**
     * @param PagamentoInfo $value
     */
    public function setPagamento($value) {
        $this->pagamento = $value;
    }

    /**
     * @return bool
     */
    public function getAceitaCreditoOnline() {
        return $this->aceita_credito_online;
    }

    /**
     * @param bool $value
     */
    public function setAceitaCreditoOnline($value) {
        $this->aceita_credito_online = $value;
    }

    /**
     * @return bool
     */
    public function getAceitaDebitoOnline() {
        return $this->aceita_debito_online;
    }

    /**
     * @param bool $value
     */
    public function setAceitaDebitoOnline($value) {
        $this->aceita_debito_online = $value;
    }

    /**
     * @return bool
     */
    public function getAceitaCartaoOffline() {
        return $this->aceita_cartao_offline;
    }

    /**
     * @param bool $value
     */
    public function setAceitaCartaoOffline($value) {
        $this->aceita_cartao_offline = $value;
    }

    /**
     * @return bool
     */
    public function getAceitaBoleto() {
        return $this->aceita_boleto;
    }

    /**
     * @param bool $value
     */
    public function setAceitaBoleto($value) {
        $this->aceita_boleto = $value;
    }

    /**
     * @return bool
     */
    public function getAceitaDinheiro() {
        return $this->aceita_dinheiro;
    }

    /**
     * @param bool $value
     */
    public function setAceitaDinheiro($value) {
        $this->aceita_dinheiro = $value;
    }

    /**
     * @return bool
     */
    public function getAceitaDeposito() {
        return $this->aceita_deposito;
    }

    /**
     * @param bool $value
     */
    public function setAceitaDeposito($value) {
        $this->aceita_deposito = $value;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function renderForm() {
        $app = EmagineApp::getApp();
        $pagamentoForm = $this;
        $pagamento = $this->getPagamento();
        if ($this->getAceitaDeposito() == true) {
            $regraDeposito = new DepositoBLL();
            $depositos = $regraDeposito->listar();
        }

        if (is_null($pagamento)) {
            throw new Exception("Nenhum pagamento informado.");
        }
        ob_start();
        require(dirname(__DIR__) . "/templates/pagamento-form.php");
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}