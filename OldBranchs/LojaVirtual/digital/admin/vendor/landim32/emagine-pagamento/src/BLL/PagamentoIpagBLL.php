<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 20/03/2018
 * Time: 09:03
 */

namespace Emagine\Pagamento\BLL;

use Ipag\Classes\Customer;
use Exception;
use Emagine\Pagamento\Model\PagamentoInfo;
use Ipag\Classes\Authentication;
use Ipag\Classes\Endpoint;
use Ipag\Classes\Enum\Method;
use Ipag\Ipag;

class PagamentoIpagBLL extends PagamentoBLL
{
    const IPAG_MESSAGE = "IPAG_MESSAGE";
    const IPAG_ORDER_ID = "IPAG_ORDER_ID";
    const IPAG_ACQUIRER_MESSAGE = "IPAG_ACQUIRER_MESSAGE";

    /**
     * @return string
     * @throws Exception
     */
    private function getIPagId() {
        if (!defined("IPAG_ID")) {
            throw new Exception("IPAG_ID não foi definido.");
        }
        return IPAG_ID;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getIPagKey() {
        if (!defined("IPAG_KEY")) {
            throw new Exception("IPAG_KEY não foi definido.");
        }
        return IPAG_KEY;
    }

    /**
     * @param int $bandeira
     * @return string
     */
    private function pegarBandeira($bandeira) {
        $novaBandeira = "";
        switch ($bandeira) {
            case PagamentoInfo::VISA:
                $novaBandeira = Method::VISA;
                break;
            case PagamentoInfo::MASTERCARD:
                $novaBandeira = Method::MASTERCARD;
                break;
            case PagamentoInfo::AMEX:
                $novaBandeira = Method::AMEX;
                break;
            case PagamentoInfo::ELO:
                $novaBandeira = Method::ELO;
                break;
            case PagamentoInfo::AURA:
                $novaBandeira = Method::AURA;
                break;
            case PagamentoInfo::JCB:
                $novaBandeira = Method::JCB;
                break;
            case PagamentoInfo::DINERS:
                $novaBandeira = Method::DINERS;
                break;
            case PagamentoInfo::DISCOVER:
                $novaBandeira = Method::DISCOVER;
                break;
            case PagamentoInfo::HIPERCARD:
                $novaBandeira = Method::HIPERCARD;
                break;
        }
        return $novaBandeira;
    }

    /**
     * @param PagamentoInfo $pagamento
     * @param int $statusTransacao
     */
    private function atualizarRetorno(&$pagamento, $statusTransacao) {
        switch ($statusTransacao) {
            case 5:
            case 8:
                $pagamento->setMensagem("Operação realizada com sucesso.");
                $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_PAGO);
                break;
            case 1:
                $pagamento->setMensagem("Iniciado");
                $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_ABERTO);
                break;
            case 2:
                $pagamento->setMensagem("Boleto impresso");
                $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_AGUARDANDO_PAGAMENTO);
                break;
            case 3:
                $pagamento->setMensagem("Cancelado");
                $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_CANCELADO);
                break;
            case 4:
                $pagamento->setMensagem("Em análise");
                $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_ABERTO);
                break;
            case 7:
                $pagamento->setMensagem("Recusado");
                $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_ABERTO);
                break;
            default:
                $pagamento->setMensagem("Código de retorno desconhecido.");
                $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_ABERTO);
                break;
        }
    }

    /**
     * @return Ipag
     * @throws Exception
     */
    private function criarIpag() {
        return new Ipag(
            new Authentication($this->getIPagId(), $this->getIPagKey()),
            Endpoint::SANDBOX
        );
    }

    /**
     * @throws Exception
     * @param Ipag $ipag
     * @param PagamentoInfo $pagamento
     * @return Customer
     */
    private function criarCliente($ipag, $pagamento) {
        $telefone = $pagamento->getUsuario()->getTelefone();
        return $ipag->customer()
            ->setName($pagamento->getUsuario()->getNome())
            ->setTaxpayerId($pagamento->getCpf())
            ->setPhone(substr($telefone, 0, 2), substr($telefone, 2))
            ->setEmail($pagamento->getUsuario()->getEmail())
            ->setAddress($ipag->address()
                ->setStreet($pagamento->getUsuario()->getLogradouro())
                ->setNumber($pagamento->getUsuario()->getNumero())
                ->setNeighborhood($pagamento->getUsuario()->getBairro())
                ->setCity($pagamento->getUsuario()->getCidade())
                ->setState($pagamento->getUsuario()->getUf())
                ->setZipCode($pagamento->getUsuario()->getCep())
            );
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     * @return int
     */
    private function pagarCredito($pagamento, $transaction = true) {
        try {
            $ipag = $this->criarIpag();
            $customer = $this->criarCliente($ipag, $pagamento);

            $creditCard = $ipag->creditCard()
                ->setNumber($pagamento->getNumeroCartao())
                ->setHolder($pagamento->getNomeCartao())
                ->setExpiryMonth($pagamento->getMesValidade())
                ->setExpiryYear($pagamento->getAnoValidade())
                ->setCvc($pagamento->getCVV())
                ->setSave(true);

            $transaction = $ipag->transaction();
            $transaction->getOrder()
                ->setOrderId($pagamento->getId())
                //->setCallbackUrl('https://minha_loja.com.br/ipag/callback')
                ->setAmount($pagamento->getTotal())
                ->setInstallments(1)
                ->setPayment($ipag->payment()
                    ->setMethod($this->pegarBandeira($pagamento->getCodBandeira()))
                    ->setCreditCard($creditCard)
                )
                ->setCustomer($customer);

            $response = $transaction->execute();
            var_dump($response);

            //Retornou algum erro?
            if (!empty($response->error)) {
                $pagamento->setMensagem($response->errorMessage);
                throw new Exception($response->errorMessage);
            }

            //$pagamento->setMensagem($response->payment->message);
            $pagamento->setOpcao(PagamentoIpagBLL::IPAG_MESSAGE, $response->payment->message);
            $pagamento->setOpcao(PagamentoIpagBLL::IPAG_ORDER_ID, $response->order->orderId);
            $pagamento->setOpcao(PagamentoIpagBLL::IPAG_ACQUIRER_MESSAGE, $response->acquirerMessage);
            $this->atualizarRetorno($pagamento, $response->payment->status);

        } catch(Exception $e) {
            //print_r($e->__toString());
            $pagamento->setMensagem($e->getMessage());
            $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_ABERTO);
            $this->alterar($pagamento, $transaction);
            throw $e;
        }
        return $pagamento->getId();
    }

    /**
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     * @return int
     * @throws Exception
     */
    private function pagarDebito($pagamento, $transaction = true) {
        try {
            $ipag = $this->criarIpag();
            $customer = $this->criarCliente($ipag, $pagamento);

            $creditCard = $ipag->creditCard()
                ->setNumber($pagamento->getNumeroCartao())
                ->setHolder($pagamento->getNomeCartao())
                ->setExpiryMonth($pagamento->getMesValidade())
                ->setExpiryYear($pagamento->getAnoValidade())
                ->setCvc($pagamento->getCVV())
                ->setSave(true);

            $transaction = $ipag->transaction();

            if ($pagamento->getCodBandeira() == PagamentoInfo::VISA) {
                $bandeira = Method::VISAELECTRON;
            }
            elseif ($pagamento->getCodBandeira() == PagamentoInfo::MASTERCARD) {
                $bandeira = Method::MAESTRO;
            }
            else {
                throw new Exception("Bandeira desconhecidade.");
            }

            $transaction->getOrder()
                ->setOrderId($pagamento->getId())
                ->setCallbackUrl('https://minha_loja.com.br/ipag/callback')
                ->setAmount($pagamento->getTotal())
                ->setInstallments(1)
                ->setPayment($ipag->payment()
                    ->setMethod($bandeira)
                    ->setCreditCard($creditCard)
                )
                ->setCustomer($customer);

            $response = $transaction->execute();
            var_dump($response);

            if (!empty($response->error)) {
                $pagamento->setMensagem($response->errorMessage);
                throw new Exception($response->errorMessage);
            }

            $pagamento->setAutenticacaoUrl($response->urlAuthentication);
            $pagamento->setOpcao(PagamentoIpagBLL::IPAG_MESSAGE, $response->payment->message);
            $pagamento->setOpcao(PagamentoIpagBLL::IPAG_ORDER_ID, $response->order->orderId);
            $pagamento->setOpcao(PagamentoIpagBLL::IPAG_ACQUIRER_MESSAGE, $response->acquirerMessage);
            $this->atualizarRetorno($pagamento, $response->payment->status);

        } catch(Exception $e) {
            $pagamento->setMensagem($e->getMessage());
            $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_ABERTO);
            $this->alterar($pagamento, $transaction);
            throw $e;
        }
        return $pagamento->getId();
    }

    /**
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     * @return int
     * @throws Exception
     */
    private function pagarBoleto($pagamento, $transaction = true) {
        try {
            $ipag = $this->criarIpag();
            $customer = $this->criarCliente($ipag, $pagamento);

            $transaction = $ipag->transaction();
            $transaction->getOrder()
                ->setOrderId($pagamento->getId())
                //->setCallbackUrl('https://minha_loja.com.br/ipag/callback')
                ->setAmount($pagamento->getTotal())
                ->setInstallments(1)
                ->setExpiry($pagamento->getDataVencimentoStr())
                ->setPayment($ipag->payment()
                    ->setMethod(Method::BANK_ITAUSHOPLINE)
                )->setCustomer($customer);

            $response = $transaction->execute();
            var_dump($response);

            //Retornou algum erro?
            if (!empty($response->error)) {
                $pagamento->setMensagem($response->errorMessage);
                throw new Exception($response->errorMessage);
            }

            //$pagamento->setMensagem($response->payment->message);
            $pagamento->setOpcao(PagamentoIpagBLL::IPAG_MESSAGE, $response->payment->message);
            $pagamento->setOpcao(PagamentoIpagBLL::IPAG_ORDER_ID, $response->order->orderId);
            $pagamento->setOpcao(PagamentoIpagBLL::IPAG_ACQUIRER_MESSAGE, $response->acquirerMessage);
            $this->atualizarRetorno($pagamento, $response->payment->status);

        } catch(Exception $e) {
            //print_r($e->__toString());
            $pagamento->setMensagem($e->getMessage());
            $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_ABERTO);
            $this->alterar($pagamento, $transaction);
            throw $e;
        }
        return $pagamento->getId();
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     */
    public function pagar($pagamento, $transaction = true) {

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
            default:
                throw new Exception("Tipo de pagamento não informado.");
                break;
        }

    }
}