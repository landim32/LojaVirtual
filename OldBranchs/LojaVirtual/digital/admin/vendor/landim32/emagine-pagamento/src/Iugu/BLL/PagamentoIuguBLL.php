<?php
namespace Emagine\Pagamento\Iugu\BLL;

use Exception;
use Emagine\Pagamento\Exceptions\PagamentoException;
use Emagine\Pagamento\Factory\CartaoFactory;
use Emagine\Pagamento\BLL\PagamentoBLL;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pagamento\Model\PagamentoOpcaoInfo;

class PagamentoIuguBLL extends PagamentoBLL
{
    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @return string[]
     */
    private function adicionarItem(PagamentoInfo $pagamento) {
        $itens = array();
        foreach ($pagamento->listarItem() as $item) {
            $itens[] = array(
                "description" => $item->getDescricao(),
                "quantity" => $item->getQuantidade(),
                "price_cents" => floor($item->getValor() * 100)
            );
        }
        return $itens;
    }

    /**
     * @param PagamentoInfo $pagamento
     * @return string[]
     * @throws Exception
     */
    private function adicionarCliente(PagamentoInfo $pagamento) {
        $usuario = $pagamento->getUsuario();
        $payer = array();
        $cpf = preg_replace("/[^0-9]/", "", $pagamento->getCpf());
        if (!isNullOrEmpty($cpf)) {
            $payer["cpf_cnpj"] = $cpf;
        }
        $payer["name"] = $usuario->getNome();
        $telefone = preg_replace("/[^0-9]/", "", $usuario->getTelefone());
        if (!isNullOrEmpty($telefone)) {
            $payer["phone_prefix"] = substr($telefone,0,2);
            $payer["phone"] = substr($telefone,2);
        }
        $payer["email"] = $usuario->getEmail();
        $cep = preg_replace("/[^0-9]/", "", $pagamento->getCep());
        if (!isNullOrEmpty($cep)) {
            $cep = substr($cep, 0, 5) . "-" . substr($cep, 5);
        }
        $payer["address"] = array(
            "street" => $pagamento->getLogradouro(),
            "number" => $pagamento->getNumero(),
            "city" => $pagamento->getCidade(),
            "state" => $pagamento->getUf(),
            "country" => "Brasil",
            "zip_code" => $cep,
        );
        return $payer;
    }

    /**
     * @param $retorno
     * @throws Exception
     */
    private function processarErro($retorno) {
        if (!isNullOrEmpty($retorno['errors'])) {
            if (is_array($retorno['errors'])) {
                $erro = array_values($retorno['errors'])[0];
            }
            elseif (is_string($retorno['errors'])) {
                $erro = $retorno['errors'];
            }
            else {
                $erro = print_r($retorno['errors'], true);
            }
            if (isset($erro)) {
                if (startsWith($erro, "do cartão")) {
                    $erro = "Número " . $erro;
                }
                throw new Exception($erro);
            }
        }
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     */
    private function pagarCredito(PagamentoInfo $pagamento) {
        $iugu = new IuguBLL();
        $usuario = $pagamento->getUsuario();

        $postData = array(
            "token" => $pagamento->getToken(),
            "email" => $usuario->getEmail(),
        );
        if ($this->usaDebug() == true) {
            $postData["test"] = "true";
        }
        $postData["items"] = $this->adicionarItem($pagamento);
        $postData["payer"] = $this->adicionarCliente($pagamento);

        $retorno = $iugu->charge($postData);
        $this->processarErro($retorno);

        if (isNullOrEmpty($retorno['message'])) {
            throw new Exception("Pagamento bem sucedido mas não retornou nenhuma mensagem (message).");
        }

        if ($retorno['success'] == true) {
            if (isNullOrEmpty($retorno['invoice_id'])) {
                throw new Exception("Pagamento bem sucedido mas não retornou o id da fatura (invoice_id).");
            }
            $pagamento->setMensagem($retorno['message']);
            $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_PAGO);

            $opcao = new PagamentoOpcaoInfo();
            $opcao->setChave("invoice_id");
            $opcao->setValor($retorno['invoice_id']);
            $pagamento->adicionarOpcao($opcao);

            if (isNullOrEmpty($retorno['token'])) {
                $opcao = new PagamentoOpcaoInfo();
                $opcao->setChave("token");
                $opcao->setValor($retorno['token']);
                $pagamento->adicionarOpcao($opcao);
            }

            $this->alterar($pagamento);
        }
        else {
            $pagamento->setMensagem($retorno['message']);
            $this->alterar($pagamento);
            throw new PagamentoException("Ocorreu um erro ao processar o pagamento");
        }
    }

    /**
     * @param PagamentoInfo $pagamento
     * @throws Exception
     */
    private function pagarBoleto(PagamentoInfo $pagamento) {
        $iugu = new IuguBLL();

        $usuario = $pagamento->getUsuario();

        $postData = array(
            "method" => "bank_slip",
            "email" => $usuario->getEmail(),
        );
        if ($this->usaDebug() == true) {
            $postData["test"] = "true";
        }
        $postData["items"] = $this->adicionarItem($pagamento);
        $postData["payer"] = $this->adicionarCliente($pagamento);

        $retorno = $iugu->charge($postData);
        $this->processarErro($retorno);

        if ($retorno['success'] == true) {
            if (isNullOrEmpty($retorno['url'])) {
                throw new Exception("Pagamento bem sucedido mas não retornou a URL do boleto (url).");
            }
            if (isNullOrEmpty($retorno['identification'])) {
                throw new Exception("Pagamento bem sucedido mas não retornou a linha digitável do boleto (url).");
            }
            if (isNullOrEmpty($retorno['invoice_id'])) {
                throw new Exception("Pagamento bem sucedido mas não retornou o id da fatura (invoice_id).");
            }
            $pagamento->setBoletoUrl($retorno['url']);
            $pagamento->setBoletoLinhaDigitavel($retorno['identification']);
            $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_AGUARDANDO_PAGAMENTO);

            $opcao = new PagamentoOpcaoInfo();
            $opcao->setChave("invoice_id");
            $opcao->setValor($retorno['invoice_id']);
            $pagamento->adicionarOpcao($opcao);

            $this->alterar($pagamento);
        }
        else {
            $pagamento->setMensagem("Ocorreu um erro ao gerar o boleto.");
            $this->alterar($pagamento);
            throw new PagamentoException("Ocorreu um erro ao processar o pagamento");
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
                if (isNullOrEmpty($pagamento->getToken())) {
                    $regraCartao = CartaoFactory::create();
                    $id_cartao = $regraCartao->inserirDoPagamento($pagamento);
                    $cartao = $regraCartao->pegar($id_cartao);
                    $pagamento->setToken($cartao->getToken());
                }
                $this->pagarCredito($pagamento);
                break;
            case PagamentoInfo::DEBITO_ONLINE:
                throw new PagamentoException("Cartão de débito não implementado.");
                break;
            case PagamentoInfo::BOLETO:
                $this->pagarBoleto($pagamento);
                break;
            case PagamentoInfo::DINHEIRO:
            case PagamentoInfo::CARTAO_OFFLINE:
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
     * @return int
     */
    public function pagarComToken(PagamentoInfo $pagamento, $transaction = true) {
        if (isNullOrEmpty($pagamento->getToken())) {
            $regraCartao = CartaoFactory::create();
            $id_cartao = $regraCartao->inserirDoPagamento($pagamento);
            $cartao = $regraCartao->pegar($id_cartao);
            $pagamento->setToken($cartao->getToken());
        }
        $this->pagarCredito($pagamento);
        return $pagamento->getId();
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     */
    public function agendar(PagamentoInfo $pagamento, $transaction = true) {
        throw new PagamentoException("Agendamento não implementado.");
    }
}