<?php
namespace Emagine\Pagamento\MundiPagg\BLL;

use stdClass;
use Exception;
use Emagine\Pagamento\BLL\CartaoBLL;
use Emagine\Pagamento\Model\CartaoInfo;
use Emagine\Pagamento\Model\PagamentoInfo;

class CartaoMundiPaggBLL extends CartaoBLL
{

    /**
     * @param string $bandeira
     * @return int
     */
    private function getTextoParaBandeira($bandeira) {
        $retorno = 0;
        switch (strtolower($bandeira)) {
            case "visa":
                $retorno = PagamentoInfo::VISA;
                break;
            case "mastercard":
                $retorno = PagamentoInfo::MASTERCARD;
                break;
            case "amex":
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
            case "diners":
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

    private function getBandeiraParaTexto($bandeira) {
        $retorno = "";
        switch ($bandeira) {
            case PagamentoInfo::VISA:
                $retorno = "Visa";
                break;
            case PagamentoInfo::MASTERCARD:
                $retorno = "Mastercard";
                break;
            case PagamentoInfo::AMEX:
                $retorno = "Amex";
                break;
            case PagamentoInfo::ELO:
                $retorno = "Elo";
                break;
            case PagamentoInfo::AURA:
                $retorno = "Aura";
                break;
            case PagamentoInfo::JCB:
                $retorno = "JCB";
                break;
            case PagamentoInfo::DINERS:
                $retorno = "Diners";
                break;
            case PagamentoInfo::DISCOVER:
                $retorno = "Discover";
                break;
            case PagamentoInfo::HIPERCARD:
                $retorno = "Hipercard";
                break;
        }
        return $retorno;
    }

    /**
     * @param stdClass $data
     * @return CartaoInfo
     */
    /*
    private function processarCartao(stdClass $data) {
        $cartao = new CartaoInfo();
        $cartao->setId($data->id);
        $cartao->setNome("x" . $data->last_four_digits);
        $cartao->setToken($data->id);
        $cartao->setBandeira($this->getTextoParaBandeira($data->brand));
        return $cartao;
    }
    */

    /**
     * @throws Exception
     * @param int $id_usuario
     * @return CartaoInfo[]
     */
    /*
    public function listar($id_usuario) {
        $cartoes = array();
        $regraCliente = new UsuarioMundiPaggBLL();
        $mundiPaggId = $regraCliente->pegarUsuarioId($id_usuario);
        $mundiPagg = new MundiPaggBLL();
        $retorno = $mundiPagg->get("/customers/" . $mundiPaggId . "/cards");
        if (!isset($retorno->data)) {
            throw new Exception("O gateway não retornou o campo 'data'.");
        }
        if (!is_array($retorno->data)) {
            throw new Exception("O campo 'data' não é uma lista válida.");
        }
        foreach ($retorno->data as $data) {
            if ($data->status == "active") {
                $cartao = $this->processarCartao($data);
                $cartao->setIdUsuario($id_usuario);
                $cartoes[] = $cartao;
            }
        }
        return $cartoes;
    }
    */

    /**
     * @throws Exception
     * @param string $id_cartao
     * @param int $id_usuario
     * @return CartaoInfo
     */
    /*
    public function pegar($id_cartao, $id_usuario = null) {
        if (!($id_usuario > 0)) {
            throw new Exception("Informe o id do usuário.");
        }
        $regraCliente = new UsuarioMundiPaggBLL();
        $mundiPaggId = $regraCliente->pegarUsuarioId($id_usuario);

        $mundiPagg = new MundiPaggBLL();
        $retorno = $mundiPagg->get("/customers/" . $mundiPaggId . "/cards/" . $id_cartao);
        $cartao = $this->processarCartao($retorno);
        $cartao->setIdUsuario($id_usuario);

        return $cartao;
    }
    */

    /**
     * @throws Exception
     * @param string $token
     * @param int $id_usuario
     * @return CartaoInfo
     */
    /*
    public function pegarPorToken($token, $id_usuario = null) {
        if (!($id_usuario > 0)) {
            throw new Exception("Informe o id do usuário.");
        }
        $regraCliente = new UsuarioMundiPaggBLL();
        $mundiPaggId = $regraCliente->pegarUsuarioId($id_usuario);

        $mundiPagg = new MundiPaggBLL();
        $retorno = $mundiPagg->get("/customers/" . $mundiPaggId . "/cards/" . $token);
        $cartao = $this->processarCartao($retorno);
        $cartao->setIdUsuario($id_usuario);

        return $cartao;
    }
    */

    /**
     * @throws Exception
     * @param CartaoInfo $cartao
     * @return int
     */
    /*
    public function inserir($cartao) {
        throw new Exception("Não Implementado para MundiPagg.");
    }
    */

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @return int
     */
    public function inserirDoPagamento($pagamento) {
        //$id_cartao = parent::inserirDoPagamento($pagamento);

        $regraCliente = new UsuarioMundiPaggBLL();
        $mundiPaggId = $regraCliente->pegarUsuarioId($pagamento->getIdUsuario());

        $data = new stdClass();
        $data->number = $pagamento->getNumeroCartao();
        $data->holder_name = $pagamento->getNomeCartao();
        $data->exp_month = $pagamento->getMesValidade();
        $data->exp_year = $pagamento->getAnoValidade();
        $data->cvv = $pagamento->getCVV();
        $data->brand = $this->getBandeiraParaTexto($pagamento->getCodBandeira());

        $mundiPagg = new MundiPaggBLL();
        $retorno = $mundiPagg->post("/customers/" . $mundiPaggId . "/cards", $data);
        if (!isset($retorno->id)) {
            throw new Exception("O gateway retornou sem o id do cliente.");
        }
        if (!isset($retorno->status)) {
            throw new Exception("O gateway não retornou o campo 'status'.");
        }
        if ($retorno->status != "active") {
            $mensagem = "O gateway retornou status '%s', o esperado era 'active'.";
            throw new Exception(sprintf($mensagem, $retorno->status));
        }
        if (!isset($retorno->brand)) {
            throw new Exception("O gateway não retornou o campo 'brand'.");
        }

        $cartao = $this->pegarPorToken($retorno->id);
        if (is_null($cartao)) {
            $cartao = new CartaoInfo();
            $cartao->setIdUsuario($pagamento->getIdUsuario());
            $cartao->setBandeira($this->getTextoParaBandeira($retorno->brand));
            $cartao->setToken($retorno->id);
            $cartao->setCVV($pagamento->getCVV());
            $cartao->setNome(substr($pagamento->getNumeroCartao(), -4, 4));
            $id_cartao = $this->inserir($cartao);
            $cartao->setId($id_cartao);
        }

        return $cartao->getId();
    }

    /**
     * @throws Exception
     * @param int $id_cartao
     */
    public function excluir($id_cartao) {
        $cartao = $this->pegar($id_cartao);
        if (is_null($cartao)) {
            return;
        }

        $regraCliente = new UsuarioMundiPaggBLL();
        $mundiPaggId = $regraCliente->pegarUsuarioId($cartao->getIdUsuario());

        $mundiPagg = new MundiPaggBLL();
        $retorno = $mundiPagg->delete("/customers/" . $mundiPaggId . "/cards/" . $cartao->getToken());
        if (!isset($retorno->status)) {
            throw new Exception("O gateway não retornou o campo 'status'.");
        }
        if ($retorno->status != "deleted") {
            $mensagem = "O gateway retornou um status '%s'. Deveria ter retornado 'deleted'.";
            throw new Exception(sprintf($mensagem, $retorno->status));
        }
        parent::excluir($id_cartao);
    }
}