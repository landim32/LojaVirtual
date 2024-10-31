<?php
namespace Emagine\Pagamento\MundiPagg\BLL;

use stdClass;
use Exception;
use Emagine\Pagamento\Factory\CartaoFactory;
use Emagine\Pagamento\BLL\PagamentoBLL;
use Emagine\Pagamento\Model\PagamentoOpcaoInfo;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pagamento\Exceptions\DesconhecidoException;
use Emagine\Pagamento\Exceptions\NaoAutorizadoException;
use Emagine\Pagamento\Exceptions\PagamentoException;

class PagamentoMundiPaggBLL extends PagamentoBLL
{
    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     * @return int
     */
    public function pagar(PagamentoInfo $pagamento, $transaction = true) {

        switch ($pagamento->getCodTipo()) {
            case PagamentoInfo::CREDITO_ONLINE:
                return $this->pagarCredito($pagamento, $transaction);
                break;
            case PagamentoInfo::DEBITO_ONLINE:
                throw new Exception("Débito em conta não disponível para esse gateway.");
                break;
            case PagamentoInfo::BOLETO:
                return $this->pagarBoleto($pagamento, $transaction);
                break;
            case PagamentoInfo::DINHEIRO:
            case PagamentoInfo::CARTAO_OFFLINE:
                break;
            default:
                throw new DesconhecidoException("Tipo de pagamento não informado.");
                break;
        }
        return 0;
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     * @return int
     */
    public function pagarComToken(PagamentoInfo $pagamento, $transaction = true) {
        $regraCliente = new UsuarioMundiPaggBLL();
        $mundiPaggId = $regraCliente->pegarUsuarioId($pagamento->getIdUsuario());

        $regraCartao = CartaoFactory::create();
        $cartao = $regraCartao->pegarPorToken($pagamento->getToken());

        $data = new stdClass();
        $data->amount = floor($pagamento->getTotal() * 100);
        $data->currency = "BRL";
        $data->customer_id = $mundiPaggId;
        $data->payment = new stdClass();
        if ($this->usaDebug() == true) {
            $data->payment->metadata = new stdClass();
            $data->payment->metadata->mundipagg_payment_method_code = "1";
        }
        $data->payment->payment_method = "credit_card";
        $data->payment->credit_card = new stdClass();
        //$data->payment->credit_card->capture = true;
        $data->payment->credit_card->installments = 1;
        $data->payment->credit_card->statement_descriptor = trim(substr($pagamento->getObservacao(), 0, 22));
        $data->payment->credit_card->card_id = $cartao->getToken();
        $data->payment->credit_card->card = new stdClass();
        $data->payment->credit_card->card->cvv = $cartao->getCVV();

        $mundiPagg = new MundiPaggBLL();
        $retorno = $mundiPagg->post("/charges", $data);
        $this->processandoPagamento($retorno, $pagamento->getId(), $transaction);
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     */
    public function agendar(PagamentoInfo $pagamento, $transaction = true) {
        throw new PagamentoException("Agendamento não implementado.");
    }

    /**
     * @param stdClass $retorno
     * @param int $id_pagamento
     * @param bool $transaction
     * @throws Exception
     */
    private function processandoPagamento(stdClass $retorno, $id_pagamento, $transaction) {
        $pagamento = $this->pegar($id_pagamento);
        if (!isset($retorno->status)) {
            throw new DesconhecidoException("O pagamento não retornou nenhum status.");
        }
        if (!isset($retorno->payment_method)) {
            throw new DesconhecidoException("O pagamento não retornou o campo 'payment_method'.");
        }
        if ($retorno->status == "paid") {
            $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_PAGO);
        }
        elseif ($retorno->status == "pending") {
            if ($retorno->payment_method == "boleto") {
                $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_AGUARDANDO_PAGAMENTO);
            }
            else {
                $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_VERIFICANDO);
            }
        }
        elseif ($retorno->status == "failed") {
            if (isset($retorno->last_transaction) && isset($retorno->last_transaction->acquirer_message)) {
                $mensagem = $retorno->last_transaction->acquirer_message;
            }
            else {
                $mensagem = "A transação falhou. Erro desconhecido.";
            }
            $pagamento->setMensagem($mensagem);
            $this->alterar($pagamento, $transaction);
            throw new NaoAutorizadoException($mensagem);
        }
        else {
            $mensagem = sprintf("O gateway retornou um status desconhecido '%s'.", $retorno->status);
            throw new DesconhecidoException($mensagem);
        }
        if (isset($retorno->id)) {
            $pagamento->adicionarOpcao((new PagamentoOpcaoInfo())->setChave("id")->setValor($retorno->id));
            //$pagamento->adicionarOpcao(new PagamentoOpcaoInfo("id", $retorno->id));
        }
        if (isset($retorno->code)) {
            $pagamento->adicionarOpcao((new PagamentoOpcaoInfo())->setChave("code")->setValor($retorno->code));
            //$pagamento->adicionarOpcao(new PagamentoOpcaoInfo("code", $retorno->code));
        }
        if (isset($retorno->gateway_id)) {
            $pagamento->adicionarOpcao((new PagamentoOpcaoInfo())->setChave("gateway_id")->setValor($retorno->gateway_id));
        }
        if (isset($retorno->paid_at)) {
            $pagamento->setDataPagamento(date("Y-m-d H:i:s", strtotime($retorno->paid_at)));
        }
        if (isset($retorno->last_transaction)) {
            $t = $retorno->last_transaction;
            if ($retorno->payment_method == "boleto") {
                if (isset($t->url)) {
                    $pagamento->setBoletoUrl($t->url);
                }
                if (isset($t->line)) {
                    $pagamento->setBoletoLinhaDigitavel($t->line);
                }
            }
            if (isset($t->id)) {
                $pagamento->adicionarOpcao((new PagamentoOpcaoInfo())->setChave("last_transaction.id")->setValor($t->id));
            }
            if (isset($t->gateway_id)) {
                $pagamento->adicionarOpcao((new PagamentoOpcaoInfo())->setChave("last_transaction.gateway_id")->setValor($t->gateway_id));
                //$pagamento->adicionarOpcao(new PagamentoOpcaoInfo("last_transaction.gateway_id", $t->gateway_id));
            }
            if (isset($t->acquirer_message)) {
                $pagamento->setMensagem($t->acquirer_message);
            }
        }
        $this->alterar($pagamento, $transaction);
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     */
    private function pagarCredito(PagamentoInfo $pagamento, $transaction = true) {

        $regraCliente = new UsuarioMundiPaggBLL();
        $mundiPaggId = $regraCliente->pegarUsuarioId($pagamento->getIdUsuario());

        $regraCartao = CartaoFactory::create();
        $id_cartao = $regraCartao->inserirDoPagamento($pagamento);
        $cartao = $regraCartao->pegar($id_cartao);

        $data = new stdClass();
        $data->amount = floor($pagamento->getTotal() * 100);
        $data->currency = "BRL";
        $data->customer_id = $mundiPaggId;
        $data->payment = new stdClass();
        if ($this->usaDebug() == true) {
            $data->payment->metadata = new stdClass();
            $data->payment->metadata->mundipagg_payment_method_code = "1";
        }
        $data->payment->payment_method = "credit_card";
        $data->payment->credit_card = new stdClass();
        //$data->payment->credit_card->capture = true;
        $data->payment->credit_card->installments = 1;
        $data->payment->credit_card->statement_descriptor = trim(substr($pagamento->getObservacao(), 0, 22));
        $data->payment->credit_card->card_id = $cartao->getToken();
        $data->payment->credit_card->card = new stdClass();
        $data->payment->credit_card->card->cvv = $cartao->getCVV();

        $mundiPagg = new MundiPaggBLL();
        $retorno = $mundiPagg->post("/charges", $data);
        /*
        if ($this->usaDebug() == true) {
            $this->gravarLog(json_encode($retorno));
        }
        */
        $this->processandoPagamento($retorno, $pagamento->getId(), $transaction);
    }

    /**
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     * @throws Exception
     */
    private function pagarBoleto(PagamentoInfo $pagamento, $transaction = true) {

        $mundiPagg = new MundiPaggBLL();

        $usuario = $pagamento->getUsuario();
        $dataVencimento = date("Y-m-d\TH:i:s\Z", strtotime($pagamento->getDataVencimento()));

        $data = new stdClass();
        $data->amount = floor($pagamento->getTotal() * 100);
        $data->currency = "BRL";
        $data->customer = new stdClass();
        $data->customer->name = $usuario->getNome();
        $data->customer->email = $usuario->getEmail();
        $data->customer->address = new stdClass();
        $data->customer->address->street = $pagamento->getLogradouro();
        $data->customer->address->number = $pagamento->getNumero();
        //$data->customer->address->complement = $pagamento->getComplemento();
        $data->customer->address->zip_code = $pagamento->getCep();
        $data->customer->address->neighborhood = $pagamento->getBairro();
        $data->customer->address->city = $pagamento->getCidade();
        $data->customer->address->state = $pagamento->getUf();
        $data->customer->address->country = "BR";
        $data->payment = new stdClass();
        if ($this->usaDebug() == true) {
            $data->payment->metadata = new stdClass();
            $data->payment->metadata->mundipagg_payment_method_code = "1";
        }
        $data->payment->payment_method = "boleto";
        $data->payment->boleto = new stdClass();
        $data->payment->boleto->bank = $mundiPagg->getBancoCodigo();
        $data->payment->boleto->instructions = $pagamento->getMensagem();
        $data->payment->boleto->due_at = $dataVencimento;

        $mundiPagg = new MundiPaggBLL();
        $retorno = $mundiPagg->post("/charges", $data);
        /*
        if ($this->usaDebug() == true) {
            $this->gravarLog(json_encode($retorno));
        }
        */
        $this->processandoPagamento($retorno, $pagamento->getId(), $transaction);
    }
}