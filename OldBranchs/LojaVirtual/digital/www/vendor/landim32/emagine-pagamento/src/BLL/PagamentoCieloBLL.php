<?php
namespace Emagine\Pagamento\BLL;

use Exception;
use Emagine\Pagamento\Model\PagamentoInfo;
use Cielo\API30\Merchant;
use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\CreditCard;
use Cielo\API30\Ecommerce\Request\CieloRequestException;
use Cielo\API30\Ecommerce\Request\CieloError;

class PagamentoCieloBLL extends PagamentoBLL
{
    const CIELO_ID = "CIELO_ID";
    const CIELO_STATUS = "CIELO_STATUS";
    const CIELO_RETURN_CODE = "CIELO_RETURN_CODE";

    //const MERCHANT_ID = "9a933857-1e6d-414f-8b0c-ad52d9e2becf";
    //const MERCHANT_KEY = "LGWFHHFFNVRJIJJJBTKFAKBIRADPCWKWGNHQNOZF";

    /**
     * @return bool
     */
    public function usaSandbox() {
        if (defined("CIELO_SANDBOX")) {
            return (CIELO_SANDBOX == true);
        }
        return true;
    }

    /**
     * @return string
     */
    public function getMerchantId() {
        if (defined("CIELO_MERCHANT_ID")) {
            return CIELO_MERCHANT_ID;
        }
        return "";
    }

    /**
     * @return string
     */
    public function getMerchantKey() {
        if (defined("CIELO_MERCHANT_KEY")) {
            return CIELO_MERCHANT_KEY;
        }
        return "";
    }

    /**
     * @param int $bandeira
     * @return string
     */
    private function pegarBandeira($bandeira) {
        $novaBandeira = "";
        switch ($bandeira) {
            case PagamentoInfo::VISA:
                $novaBandeira = CreditCard::VISA;
                break;
            case PagamentoInfo::MASTERCARD:
                $novaBandeira = CreditCard::MASTERCARD;
                break;
            case PagamentoInfo::AMEX:
                $novaBandeira = CreditCard::AMEX;
                break;
            case PagamentoInfo::ELO:
                $novaBandeira = CreditCard::ELO;
                break;
            case PagamentoInfo::AURA:
                $novaBandeira = CreditCard::AURA;
                break;
            case PagamentoInfo::JCB:
                $novaBandeira = CreditCard::JCB;
                break;
            case PagamentoInfo::DINERS:
                $novaBandeira = CreditCard::DINERS;
                break;
            case PagamentoInfo::DISCOVER:
                $novaBandeira = CreditCard::DISCOVER;
                break;
            case PagamentoInfo::HIPERCARD:
                $novaBandeira = CreditCard::HIPERCARD;
                break;
        }
        return $novaBandeira;
    }

    /**
     * @param PagamentoInfo $pagamento
     * @param int $status
     */
    //private function atualizarRetorno(&$pagamento, $returnCode) {
    private function atualizarRetorno(&$pagamento, $status) {
        switch ($status) {
            case 1:
            case 2:
                $pagamento->setDataPagamento(date("Y-m-d h:i:s"));
                $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_PAGO);
                break;
            default:
                $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_ABERTO);
                break;
        }
    }

    /**
     * @param PagamentoInfo $pagamento
     * @param double $valor
     * @param string $dataExpiracao
     * @return string
     */
    private function gerarLogCredito(PagamentoInfo $pagamento, $valor, $dataExpiracao) {
        $log = "";
        $log .= "\$environment = Environment::" . ($this->usaSandbox() ? "sandbox" : "production") . "();\n";
        $log .= "\$merchant = new Merchant('" . $this->getMerchantId() ."', '" .$this->getMerchantKey() . "');\n";
        $log .= "\$venda = new Sale(" . $pagamento->getId() . ");\n";
        $log .= "\$venda->customer('" . $pagamento->getNomeCartao() . "');\n";

        $log .= "\$payment = \$venda->payment(" . $valor . ");\n";
        $log .= "\$payment->setType(Payment::PAYMENTTYPE_CREDITCARD)\n";
        $log .= "   ->creditCard('" . $pagamento->getCVV() . "', '" . $this->pegarBandeira($pagamento->getCodBandeira()) . "')\n";
        $log .= "   ->setExpirationDate('" . $dataExpiracao . "')\n";
        $log .= "   ->setCardNumber('" . $pagamento->getNumeroCartao() . "')\n";
        $log .= "   ->setHolder('" . $pagamento->getNomeCartao() . "')\n";
        $log .= "   ->setSaveCard(true);\n";
        $log .= "\n";
        return $log;
    }

    /**
     * @throws Exception
     * @param CieloRequestException $e
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     */
    private function processarErro(CieloRequestException $e, PagamentoInfo $pagamento, $transaction = false) {
        /** @var CieloError $error */
        $error = $e->getCieloError();
        if (!is_null($error)) {
            $mensagemErro = $error->getMessage();
        }
        else {
            $mensagemErro = $e->getMessage();
        }
        $pagamento->setMensagem($mensagemErro);
        $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_ABERTO);
        $this->alterar($pagamento, $transaction);
        throw new Exception($mensagemErro);
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     */
    private function pagarCredito($pagamento, $transaction = true) {
        $environment = $this->usaSandbox() ? Environment::sandbox() : Environment::production();
        $merchant = new Merchant($this->getMerchantId(), $this->getMerchantKey());
        $venda = new Sale($pagamento->getId());
        $venda->customer($pagamento->getNomeCartao());
        $valor = $pagamento->getTotal() * 100;
        $valor = intval(floor($valor));
        $payment = $venda->payment($valor);

        $dataExpiracao = date("m/Y", strtotime($pagamento->getDataExpiracao()));

        $payment->setType(Payment::PAYMENTTYPE_CREDITCARD)
            ->creditCard($pagamento->getCVV(), $this->pegarBandeira($pagamento->getCodBandeira()))
            ->setExpirationDate($dataExpiracao)
            ->setCardNumber($pagamento->getNumeroCartao())
            ->setHolder($pagamento->getNomeCartao())
            ->setSaveCard(true);

        try {
            $cieloEcommerce = new CieloEcommerce($merchant, $environment);
            $retorno = $cieloEcommerce->createSale($venda);

            if ($this->usaDebug() == true) {
                $descricao = $this->gerarLogCredito($pagamento, $valor, $dataExpiracao);
                $descricao .= print_r($retorno, true);
                $this->gravarInfo("Pagamento com Cartão de Crédito via Cielo", $descricao, $pagamento->getUsuario()->getNome());
            }

            /** @var Payment $payment */
            $payment = $retorno->getPayment();

            $pagamento->setToken($payment->getCreditCard()->getCardToken());
            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_ID, $payment->getPaymentId());
            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_STATUS, $payment->getStatus());
            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_RETURN_CODE, $payment->getReturnCode());
            $pagamento->setMensagem($payment->getReturnMessage());
            $this->atualizarRetorno($pagamento, $payment->getStatus());

            $this->alterar($pagamento, $transaction);

            if ($pagamento->getCodSituacao() == PagamentoInfo::SITUACAO_PAGO) {
                $regraCartao = new CartaoBLL();
                $regraCartao->inserirDoPagamento($pagamento);
            }

        } catch (CieloRequestException $e) {
            if ($this->usaDebug() == true) {
                $descricao = $this->gerarLogCredito($pagamento, $valor, $dataExpiracao);
                if (isset($retorno)) {
                    $descricao .= print_r($retorno, true);
                    $descricao .= "<hr />\n";
                }
                $descricao .= print_r($e, true);
                $this->gravarErro("Erro ao pagamento com Cartão de Crédito via Cielo", $descricao, $pagamento->getUsuario()->getNome());
            }
            $this->processarErro($e, $pagamento, $transaction);
        }
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     */
    private function pagarDebito($pagamento, $transaction = true) {
        if (!defined("SITE_URL")) {
            throw new Exception("SITE_URL não está definido.");
        }

        $environment = $this->usaSandbox() ? Environment::sandbox() : Environment::production();
        $merchant = new Merchant($this->getMerchantId(), $this->getMerchantKey());
        $venda = new Sale($pagamento->getId());
        $venda->customer($pagamento->getNomeCartao());
        $valor = $pagamento->getTotal() * 100;
        $valor = intval(floor($valor));
        $payment = $venda->payment($valor);

        $payment->setReturnUrl(SITE_URL . "/api/pagamento/retorno");

        $dataExpiracao = date("m/Y", strtotime($pagamento->getDataExpiracao()));
        $payment->debitCard($pagamento->getCVV(), $this->pegarBandeira($pagamento->getCodBandeira()))
            ->setExpirationDate($dataExpiracao)
            ->setCardNumber($pagamento->getNumeroCartao())
            ->setHolder($pagamento->getNomeCartao());

        try {
            $cieloEcommerce = new CieloEcommerce($merchant, $environment);
            $retorno = $cieloEcommerce->createSale($venda);

            /** @var Payment $payment */
            $payment = $retorno->getPayment();

            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_ID, $payment->getPaymentId());
            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_STATUS, $payment->getStatus());
            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_RETURN_CODE, $payment->getReturnCode());
            $pagamento->setAutenticacaoUrl($payment->getAuthenticationUrl());
            $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_AGUARDANDO_PAGAMENTO);
            $this->alterar($pagamento, $transaction);

        } catch (CieloRequestException $e) {
            $this->processarErro($e, $pagamento, $transaction);
        }
    }

    /**
     * @param PagamentoInfo $pagamento
     * @param double $valor
     * @return string
     * @throws Exception
     */
    private function gerarLogBoleto(PagamentoInfo $pagamento, $valor) {
        $log = "";
        $log .= "\$environment = Environment::" . ($this->usaSandbox() ? "sandbox" : "production") . "();\n";
        $log .= "\$merchant = new Merchant('" . $this->getMerchantId() ."', '" .$this->getMerchantKey() . "');\n";
        $log .= "\$venda = new Sale('" . $pagamento->getId() . "');\n";
        $log .= "\$venda->customer('" . $pagamento->getUsuario()->getNome() . "')\n";
        $log .= "   ->setIdentity('" . $pagamento->getCpf() . "')\n";
        $log .= "   ->setIdentityType('CPF')\n";
        $log .= "   ->address()->setZipCode('" . $pagamento->getCep() . "')\n";
        $log .= "      ->setCountry('BRA')\n";
        $log .= "      ->setState('" . $pagamento->getUf() . "')\n";
        $log .= "      ->setCity('" . $pagamento->getCidade() . "')\n";
        $log .= "      ->setStreet('" . $pagamento->getLogradouro() . "')\n";
        $log .= "      ->setNumber('" . $pagamento->getNumero() . "');\n";
        $log .= "\$venda->payment(" . $valor . ")\n";
        $log .= "   ->setType(Payment::PAYMENTTYPE_BOLETO)\n";
        $log .= "   ->setAddress('Rua de Teste')\n";
        $log .= "   ->setBoletoNumber(" . $pagamento->getId() . ")\n";
        $log .= "   ->setAssignor('Empresa de Teste')\n";
        $log .= "   ->setDemonstrative('Desmonstrative Teste')\n";
        $log .= "   ->setExpirationDate(" . date('d/m/Y', strtotime('+1 month')) . ")\n";
        $log .= "   ->setIdentification('11884926754')\n";
        $log .= "   ->setInstructions('Esse é um boleto de exemplo');\n";
        $log .= "\$cieloEcommerce = new CieloEcommerce(\$merchant, \$environment);\n";
        $log .= "\$venda = \$cieloEcommerce->createSale(\$venda);\n";
        $log .= "<hr />\n";
        return $log;
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     */
    private function pagarBoleto($pagamento, $transaction = true) {

        $environment = $this->usaSandbox() ? Environment::sandbox() : Environment::production();
        $merchant = new Merchant($this->getMerchantId(), $this->getMerchantKey());

        $venda = new Sale($pagamento->getId());
        $venda->customer($pagamento->getUsuario()->getNome())
            ->setIdentity($pagamento->getCpf())
            ->setIdentityType('CPF')
            ->address()->setZipCode($pagamento->getCep())
                ->setCountry('BRA')
                ->setState($pagamento->getUf())
                ->setCity($pagamento->getCidade())
                ->setDistrict($pagamento->getBairro())
                ->setStreet($pagamento->getLogradouro())
                ->setNumber($pagamento->getNumero());

        $valor = $pagamento->getTotal() * 100;
        $valor = intval(floor($valor));

        $venda->payment($valor)
            ->setType(Payment::PAYMENTTYPE_BOLETO)
            ->setAddress('Rua de Teste')
            ->setBoletoNumber($pagamento->getId())
            ->setAssignor('Empresa de Teste')
            ->setDemonstrative('Desmonstrative Teste')
            ->setExpirationDate(date('d/m/Y', strtotime('+1 month')))
            ->setIdentification('11884926754')
            ->setInstructions('Esse é um boleto de exemplo');

        try {
            $cieloEcommerce = new CieloEcommerce($merchant, $environment);
            $retorno = $cieloEcommerce->createSale($venda);

            if ($this->usaDebug() == true) {
                $descricao = $this->gerarLogBoleto($pagamento, $valor);
                $descricao .= print_r($retorno, true);
                $this->gravarInfo("Pagamento de Boleto via Cielo", $descricao, $pagamento->getUsuario()->getNome());
            }

            /** @var Payment $payment */
            $payment = $retorno->getPayment();

            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_ID, $payment->getPaymentId());
            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_STATUS, $payment->getStatus());
            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_RETURN_CODE, $payment->getReturnCode());
            $pagamento->setBoletoUrl($payment->getUrl());
            $pagamento->setBoletoLinhaDigitavel($payment->getDigitableLine());
            $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_AGUARDANDO_PAGAMENTO);

            $this->alterar($pagamento, $transaction);

        } catch (CieloRequestException $e) {
            if ($this->usaDebug() == true) {
                $descricao = $this->gerarLogBoleto($pagamento, $valor);
                if (isset($retorno)) {
                    $descricao .= print_r($retorno, true);
                    $descricao .= "<hr />\n";
                }
                $descricao .= print_r($e, true);
                $this->gravarErro("Erro ao pagamento de Boleto via Cielo", $descricao, $pagamento->getUsuario()->getNome());
            }
            $this->processarErro($e, $pagamento, $transaction);
        }
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     */
    public function pagar(PagamentoInfo $pagamento, $transaction = true) {

        switch ($pagamento->getCodTipo()) {
            case PagamentoInfo::CREDITO_ONLINE:
                $this->pagarCredito($pagamento, $transaction);
                break;
            case PagamentoInfo::DEBITO_ONLINE:
                $this->pagarDebito($pagamento, $transaction);
                break;
            case PagamentoInfo::BOLETO:
                $this->pagarBoleto($pagamento, $transaction);
                break;
            case PagamentoInfo::DINHEIRO:
            case PagamentoInfo::CARTAO_OFFLINE:
            case PagamentoInfo::DEPOSITO_BANCARIO:
                if ($this->podeEnviarEmail() == true) {
                    $this->enviarEmail($pagamento);
                }
                break;
            default:
                throw new Exception("Tipo de pagamento não informado.");
                break;
        }

    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     */
    public function pagarComToken(PagamentoInfo $pagamento, $transaction = true) {

        $environment = $this->usaSandbox() ? Environment::sandbox() : Environment::production();
        $merchant = new Merchant($this->getMerchantId(), $this->getMerchantKey());
        $venda = new Sale($pagamento->getId());
        $venda->customer($pagamento->getNomeCartao());
        $valor = $pagamento->getTotal() * 100;
        $valor = intval(floor($valor));
        $payment = $venda->payment($valor);

        $payment->setType(Payment::PAYMENTTYPE_CREDITCARD)
            ->creditCard($pagamento->getCVV(), $this->pegarBandeira($pagamento->getCodBandeira()))
            ->setCardToken($pagamento->getToken());

        try {
            $cieloEcommerce = new CieloEcommerce($merchant, $environment);
            $retorno = $cieloEcommerce->createSale($venda);

            /** @var Payment $payment */
            $payment = $retorno->getPayment();
            //$payment->getStatus()

            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_ID, $payment->getPaymentId());
            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_STATUS, $payment->getStatus());
            $pagamento->setOpcao(PagamentoCieloBLL::CIELO_RETURN_CODE, $payment->getReturnCode());
            $pagamento->setMensagem($payment->getReturnMessage());
            $this->atualizarRetorno($pagamento, $payment->getStatus());

            $this->alterar($pagamento, $transaction);

        } catch (CieloRequestException $e) {
            $this->processarErro($e, $pagamento, $transaction);
        }
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     * @return int
     */
    public function agendar(PagamentoInfo $pagamento, $transaction = true) {
        throw new Exception("Agendamento não implementado.");
    }
}