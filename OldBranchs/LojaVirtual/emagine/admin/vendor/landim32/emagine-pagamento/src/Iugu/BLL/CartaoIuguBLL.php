<?php
namespace Emagine\Pagamento\Iugu\BLL;

use Exception;
use Emagine\Pagamento\Factory\PagamentoFactory;
use Emagine\Pagamento\BLL\CartaoBLL;
use Emagine\Pagamento\Model\CartaoInfo;
use Emagine\Pagamento\Model\PagamentoInfo;

class CartaoIuguBLL extends CartaoBLL
{

    /**
     * @param string $bandeira
     * @return int
     */
    private function getTextoParaBandeira($bandeira)
    {
        $retorno = 0;
        switch (strtolower($bandeira)) {
            case "visa":
                $retorno = PagamentoInfo::VISA;
                break;
            case "mastercard":
                $retorno = PagamentoInfo::MASTERCARD;
                break;
            case "american express":
                $retorno = PagamentoInfo::AMEX;
                break;
            case "elo":
                $retorno = PagamentoInfo::ELO;
                break;
            case "aura":
                $retorno = PagamentoInfo::AURA;
                break;
            case "jcb":
                $retorno = PagamentoInfo::JCB;
                break;
            case "diner's club":
                $retorno = PagamentoInfo::DINERS;
                break;
            case "discover":
                $retorno = PagamentoInfo::DISCOVER;
                break;
            case "hipercard":
                $retorno = PagamentoInfo::HIPERCARD;
                break;
        }
        return $retorno;
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @return int
     */
    public function inserirDoPagamento($pagamento) {
        $regraPagamento = PagamentoFactory::create();
        $iugu = new IuguBLL();

        $nome = trim(substr($pagamento->getNomeCartao(), 0, strpos($pagamento->getNomeCartao(), " ")));
        $sobrenome = trim(substr($pagamento->getNomeCartao(), strpos($pagamento->getNomeCartao(), " ")));

        $postData = array(
            "account_id" => $iugu->getIuguIdAccount(),
            "method" => "credit_card",
            "data[number]" => $pagamento->getNumeroCartao(),
            "data[verification_value]" => $pagamento->getCVV(),
            "data[first_name]" => $nome,
            "data[last_name]" => $sobrenome,
            "data[month]" => $pagamento->getMesValidade(),
            "data[year]" => $pagamento->getAnoValidade()
        );
        if ($regraPagamento->usaDebug() == true) {
            $postData["test"] = "true";
        }

        $retorno = $iugu->post("/payment_token", $postData);

        if (!isset($retorno->id)) {
            throw new Exception("O campo 'id' não foi informado.");
        }
        if (!isset($retorno->extra_info)) {
            throw new Exception("O campo 'extra_info' não foi informado.");
        }
        if (!isset($retorno->extra_info->display_number)) {
            throw new Exception("O campo 'display_number' não foi informado.");
        }
        if (!isset($retorno->extra_info->brand)) {
            throw new Exception("O campo 'brand' não foi informado.");
        }

        $retornoBandeira = $retorno->extra_info->brand;
        $cod_bandeira = $this->getTextoParaBandeira($retornoBandeira);
        if (!($cod_bandeira > 0)) {
            $mensagem = "Nenhuma bandeira de cartão encontrada do com retorno '%s'.";
            throw new Exception(sprintf($mensagem, $retornoBandeira));
        }

        $cartao = $this->pegarPorToken($retorno->id);
        if (is_null($cartao)) {
            $cartao = new CartaoInfo();
            $cartao->setIdUsuario($pagamento->getIdUsuario());
            $cartao->setBandeira($cod_bandeira);
            $cartao->setToken($retorno->id);
            $cartao->setCVV($pagamento->getCVV());
            $cartao->setNome($retorno->extra_info->display_number);
            $id_cartao = $this->inserir($cartao);
            $cartao->setId($id_cartao);
        }

        return $cartao->getId();
    }
}