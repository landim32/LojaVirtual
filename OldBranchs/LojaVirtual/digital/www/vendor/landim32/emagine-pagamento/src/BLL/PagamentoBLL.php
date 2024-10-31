<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 05/02/18
 * Time: 21:33
 */

namespace Emagine\Pagamento\BLL;

use Emagine\Base\BLL\EmailBLL;
use Emagine\Base\BLL\ValidaCpfCnpj;
use Emagine\Log\BLL\LogBLL;
use Emagine\Log\Model\LogInfo;
use Exception;
use Emagine\Pagamento\DAL\PagamentoDAL;
use Emagine\Pagamento\DAL\PagamentoItemDAL;
use Emagine\Pagamento\DAL\PagamentoOpcaoDAL;
use Emagine\Pagamento\Model\PagamentoInfo;
use Landim32\EasyDB\DB;

abstract class PagamentoBLL
{
    const CIELO = "cielo";
    const IPAG = "ipag";
    const IUGU = "iugu";
    const MUNDI_PAGG = "mundi-pagg";

    /**
     * @return bool
     */
    public function usaDebug() {
        if (defined("PAGAMENTO_DEBUG")) {
            return (PAGAMENTO_DEBUG == true);
        }
        return false;
    }

    /**
     * @return string
     */
    public static function getTipoPagamento() {
        if (defined("PAGAMENTO_TIPO")) {
            return PAGAMENTO_TIPO;
        }
        return PagamentoBLL::CIELO;
    }

    /**
     * @return bool
     */
    public static function usaDebitoOnline() {
        $retorno = true;
        if (PagamentoBLL::getTipoPagamento() == PagamentoBLL::IUGU) {
            $retorno = false;
        }
        return $retorno;
    }

    /**
     * @return bool
     */
    public function podeEnviarEmail() {
        if (defined("PAGAMENTO_ENVIAR_EMAIL")) {
            return (PAGAMENTO_ENVIAR_EMAIL == true);
        }
        return false;
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @return string
     */
    public function gerarAssunto(PagamentoInfo $pagamento) {
        $assunto = "";
        switch ($pagamento->getCodTipo()) {
            case PagamentoInfo::CREDITO_ONLINE:
                $assunto = "Pedido #%s recebido";
                break;
            case PagamentoInfo::DEBITO_ONLINE:
                $assunto = "Pedido #%s recebido";
                break;
            case PagamentoInfo::BOLETO:
                $assunto = "Pedido #%s recebido, aguardando pagamento";
                break;
            case PagamentoInfo::DINHEIRO:
                $assunto = "Pedido #%s recebido";
                break;
            case PagamentoInfo::CARTAO_OFFLINE:
                $assunto = "Pedido #%s recebido";
                break;
            case PagamentoInfo::DEPOSITO_BANCARIO:
                $assunto = "Pedido #%s recebido, aguardando pagamento";
                break;
        }
        return sprintf($assunto, $pagamento->getId());
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     */
    public function enviarEmail($pagamento) {
        if (!defined("EMAIL_ADMIN")) {
            throw new Exception("EMAIL_ADMIN não está definido.");
        }
        ob_start();
        $assunto = $this->gerarAssunto($pagamento);
        include dirname(__DIR__) . "/templates/pagamento-email.php";
        $conteudo = ob_get_contents();
        ob_end_clean();

        //$regraEmail = new MailJetBLL();
        $regraEmail = new EmailBLL();
        //$regraEmail->sendmail($loja->getEmail(),$assunto, $mensagem);
        $regraEmail->sendmail(EMAIL_ADMIN, $assunto, $conteudo, NOME_REMETENTE, EMAIL_REMETENTE);
    }

    /**
     * @return string[]
     */
    public function listarTipo() {
        return array(
            PagamentoInfo::CREDITO_ONLINE => "Cartão de Crédito",
            PagamentoInfo::DEBITO_ONLINE => "Cartão de Débito",
            PagamentoInfo::BOLETO => "Boleto",
            PagamentoInfo::DINHEIRO => "Dinheiro",
            PagamentoInfo::CARTAO_OFFLINE => "Cartão/Vale/Ticket",
            PagamentoInfo::DEPOSITO_BANCARIO => "Depósito Bancário"
        );
    }

    /**
     * @return string[]
     */
    public function listarSituacao() {
        return array(
            PagamentoInfo::SITUACAO_ABERTO => "Aberto",
            PagamentoInfo::SITUACAO_PAGO => "Pago",
            PagamentoInfo::SITUACAO_VERIFICANDO => "Verificando pagamento",
            PagamentoInfo::SITUACAO_AGUARDANDO_PAGAMENTO => "Aguardando pagamento",
            PagamentoInfo::SITUACAO_CANCELADO => "Cancelado"
        );
    }

        /**
     * @return array<int, string>
     */
    public function listarBandeira() {
        return array(
            PagamentoInfo::VISA => "Visa",
            PagamentoInfo::MASTERCARD => "Mastercard",
            PagamentoInfo::AMEX => "Amex",
            PagamentoInfo::ELO => "Elo",
            PagamentoInfo::AURA => "Aura",
            PagamentoInfo::JCB => "JCB",
            PagamentoInfo::DINERS => "Diners Club",
            PagamentoInfo::DISCOVER => "Discover",
            PagamentoInfo::HIPERCARD => "Hipercard"
        );
    }

    /**
     * @throws Exception
     * @param int|null $id_usuario
     * @param int|null $cod_situacao
     * @return PagamentoInfo[]
     */
    public function listar($id_usuario = null, $cod_situacao = null) {
        $dal = new PagamentoDAL();
        return $dal->listar($id_usuario, $cod_situacao);
    }

    /**
     * @throws Exception
     * @param int $id_pagamento
     * @return PagamentoInfo|null
     */
    public function pegar($id_pagamento) {
        $dal = new PagamentoDAL();
        return $dal->pegar($id_pagamento);
    }

    /**
     * @param string[] $postData
     * @param PagamentoInfo|null $pagamento
     * @return PagamentoInfo
     */
    public function pegarDoPost($postData, PagamentoInfo $pagamento = null) {
        if (is_null($pagamento)) {
            $pagamento = new PagamentoInfo();
        }
        if (array_key_exists("id_pagamento", $postData)) {
            $pagamento->setId(intval($postData["id_pagamento"]));
        }
        if (array_key_exists("id_usuario", $postData)) {
            $pagamento->setIdUsuario(intval($postData["id_usuario"]));
        }
        if (array_key_exists("id_deposito", $postData)) {
            $pagamento->setIdDeposito(intval($postData["id_deposito"]));
        }
        if (array_key_exists("data_vencimento", $postData)) {
            $pagamento->setDataVencimento($postData["data_vencimento"]);
        }
        if (array_key_exists("valor_desconto", $postData)) {
            $pagamento->setValorDesconto($postData["valor_desconto"]);
        }
        if (array_key_exists("valor_juro", $postData)) {
            $pagamento->setValorJuro($postData["valor_juro"]);
        }
        if (array_key_exists("valor_multa", $postData)) {
            $pagamento->setValorMulta($postData["valor_multa"]);
        }
        if (array_key_exists("cod_situacao", $postData)) {
            $pagamento->setCodSituacao($postData["cod_situacao"]);
        }
        if (array_key_exists("observacao", $postData)) {
            $pagamento->setObservacao($postData["observacao"]);
        }

        if (array_key_exists("cod_tipo", $postData)) {
            $pagamento->setCodTipo(intval($postData["cod_tipo"]));
        }
        if (array_key_exists("forma_pagamento", $postData)) {
            if ($postData["forma_pagamento"] == "credito") {
                $pagamento->setCodTipo(PagamentoInfo::CREDITO_ONLINE);
            }
            elseif ($postData["forma_pagamento"] == "debito") {
                $pagamento->setCodTipo(PagamentoInfo::DEBITO_ONLINE);
            }
            elseif ($postData["forma_pagamento"] == "boleto") {
                $pagamento->setCodTipo(PagamentoInfo::BOLETO);
            }
            elseif ($postData["forma_pagamento"] == "dinheiro") {
                $pagamento->setCodTipo(PagamentoInfo::DINHEIRO);
            }
            elseif ($postData["forma_pagamento"] == "cartao") {
                $pagamento->setCodTipo(PagamentoInfo::CARTAO_OFFLINE);
            }
            elseif ($postData["forma_pagamento"] == "deposito") {
                $pagamento->setCodTipo(PagamentoInfo::DEPOSITO_BANCARIO);
            }
        }

        if (array_key_exists("bandeira", $postData)) {
            switch ($postData["bandeira"]) {
                case 'mastercard':
                    $pagamento->setCodBandeira(PagamentoInfo::MASTERCARD);
                    break;
                case 'amex':
                    $pagamento->setCodBandeira(PagamentoInfo::AMEX);
                    break;
                case 'discover':
                    $pagamento->setCodBandeira(PagamentoInfo::DISCOVER);
                    break;
                case 'diners':
                    $pagamento->setCodBandeira(PagamentoInfo::DINERS);
                    break;
                case 'jcb':
                    $pagamento->setCodBandeira(PagamentoInfo::JCB);
                    break;
                default:
                    $pagamento->setCodBandeira(PagamentoInfo::VISA);
                    break;
            }
        }
        if (array_key_exists("numero_cartao", $postData)) {
            $pagamento->setNumeroCartao($postData["numero_cartao"]);
        }
        if (array_key_exists("mes_validade", $postData) && array_key_exists("ano_validade", $postData)) {
            $mes = intval($postData["mes_validade"]);
            $ano = intval($postData["ano_validade"]);
            $data = mktime(0,0,0, $mes, 1, $ano);
            $pagamento->setDataExpiracao(date("Y-m-01", $data));
        }
        if (array_key_exists("data_expiracao", $postData)) {
            $pagamento->setDataExpiracao(date("Y-m-01", strtotime($postData["data_expiracao"])));
        }
        if (array_key_exists("nome_cartao", $postData)) {
            $pagamento->setNomeCartao($postData["nome_cartao"]);
        }
        if (array_key_exists("codigo_seguranca", $postData)) {
            $pagamento->setCVV($postData["codigo_seguranca"]);
        }

        if (array_key_exists("cpf", $postData)) {
            $pagamento->setCpf($postData["cpf"]);
        }

        if (array_key_exists("cep", $postData)) {
            $pagamento->setCep($postData["cep"]);
        }
        if (array_key_exists("logradouro", $postData)) {
            $pagamento->setLogradouro($postData["logradouro"]);
        }
        if (array_key_exists("numero", $postData)) {
            $pagamento->setNumero($postData["numero"]);
        }
        if (array_key_exists("bairro", $postData)) {
            $pagamento->setBairro($postData["bairro"]);
        }
        if (array_key_exists("cidade", $postData)) {
            $pagamento->setCidade($postData["cidade"]);
        }
        if (array_key_exists("uf", $postData)) {
            $pagamento->setUf($postData["uf"]);
        }

        if (array_key_exists("troco_para", $postData)) {
            $pagamento->setTrocoPara(floatvalx($postData["troco_para"]));
        }

        return $pagamento;
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     */
    private function validar(PagamentoInfo &$pagamento) {
        if (is_null($pagamento)) {
            throw new Exception("Nenhum pagamento informado.");
        }
        if (!($pagamento->getIdUsuario() > 0)) {
            throw new Exception("O pagamento não está vinculado a nenhum usuário.");
        }
        if (!($pagamento->getId() > 0)) {
            if ($pagamento->getCodTipo() == PagamentoInfo::CREDITO_ONLINE) {
                if (isNullOrEmpty($pagamento->getToken())) {
                    if (isNullOrEmpty($pagamento->getNumeroCartao())) {
                        throw new Exception("Preencha o número do cartão.");
                    }
                    if (isNullOrEmpty($pagamento->getNomeCartao())) {
                        throw new Exception("Preencha o nome que está no cartão.");
                    }
                    if (isNullOrEmpty($pagamento->getDataExpiracao())) {
                        throw new Exception("Selecione a data de expiração.");
                    }
                }
                if (!($pagamento->getCodBandeira() > 0)) {
                    throw new Exception("Selecione a bandeira do cartão.");
                }
                if (isNullOrEmpty($pagamento->getCVV())) {
                    throw new Exception("Preencha o código de verificação. Está atrás do cartão.");
                }
            } elseif ($pagamento->getCodTipo() == PagamentoInfo::BOLETO) {
                if (isNullOrEmpty($pagamento->getCpf())) {
                    throw new Exception("Preencha o CPF.");
                }
                if (isNullOrEmpty($pagamento->getCep())) {
                    throw new Exception("Preencha o CEP.");
                }
                if (isNullOrEmpty($pagamento->getLogradouro())) {
                    throw new Exception("Preencha o logradouro.");
                }
                if (isNullOrEmpty($pagamento->getNumero())) {
                    throw new Exception("Preencha o número.");
                }
                if (isNullOrEmpty($pagamento->getBairro())) {
                    throw new Exception("Preencha o bairro.");
                }
                if (isNullOrEmpty($pagamento->getCidade())) {
                    throw new Exception("Preencha o cidade.");
                }
                if (isNullOrEmpty($pagamento->getUf())) {
                    throw new Exception("Preencha a UF.");
                }
            }
        }
        if ($pagamento->getCodTipo() == PagamentoInfo::DEPOSITO_BANCARIO) {
            if (!($pagamento->getIdDeposito() > 0)) {
                throw new Exception("Selecione alguma conta para depósito/transferência.");
            }
        }
        if (!is_array($pagamento->listarItem()) || !(count($pagamento->listarItem()) > 0)) {
            throw new Exception("Nenhum item ligado a esse pagamento.");
        }
        if (!($pagamento->getTotal() > 0)) {
            throw new Exception("O valor total do pagamento precisa ser maior que 0.");
        }
        if (!isNullOrEmpty($pagamento->getCpf())) {
            $validarCpf = new ValidaCpfCnpj($pagamento->getCpf());
            if (!$validarCpf->validar()) {
                throw new Exception("O CPF não é válido.");
            }
            $cpf = preg_replace("/[^0-9]/", "", $pagamento->getCpf());
            $pagamento->setCpf($cpf);
        }
        if (!($pagamento->getCodSituacao() > 0)) {
            $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_ABERTO);
        }
        if (!($pagamento->getValorDesconto() > 0)) {
            $pagamento->setValorDesconto(0);
        }
        if (!($pagamento->getValorMulta() > 0)) {
            $pagamento->setValorMulta(0);
        }
        if (!($pagamento->getValorJuro() > 0)) {
            $pagamento->setValorJuro(0);
        }
        if (!($pagamento->getTrocoPara() > 0)) {
            $pagamento->setTrocoPara(0);
        }
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     * @return int
     */
    public function inserir(PagamentoInfo $pagamento, $transaction = true) {
        $this->validar($pagamento);

        $dalPagamento = new PagamentoDAL();
        $dalItem = new PagamentoItemDAL();
        $dalOpcao = new PagamentoOpcaoDAL();
        try{
            if ($transaction === true) {
                DB::beginTransaction();
            }
            $id_pagamento = $dalPagamento->inserir($pagamento);
            foreach ($pagamento->listarItem() as $item) {
                $item->setIdPagamento($id_pagamento);
                $dalItem->inserir($item);
            }
            foreach ($pagamento->listarOpcao() as $opcao) {
                $opcao->setIdPagamento($id_pagamento);
                $dalOpcao->inserir($opcao);
            }
            if ($transaction === true) {
                DB::commit();
            }
        }
        catch (Exception $e){
            if ($transaction === true) {
                DB::rollBack();
            }
            throw $e;
        }
        return $id_pagamento;
    }

    /**
     * @throws Exception
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     */
    public function alterar(PagamentoInfo $pagamento, $transaction = true) {
        $this->validar($pagamento);

        $dalPagamento = new PagamentoDAL();
        $dalItem = new PagamentoItemDAL();
        $dalOpcao = new PagamentoOpcaoDAL();
        try{
            if ($transaction === true) {
                DB::beginTransaction();
            }
            $dalPagamento->alterar($pagamento);
            $id_pagamento = $pagamento->getId();
            $pagamento->listarItem();
            $dalItem->limpar($pagamento->getId());
            $dalOpcao->limpar($pagamento->getId());
            $ordem = 1;
            foreach ($pagamento->listarItem() as $item) {
                $item->setIdPagamento($id_pagamento);
                $dalItem->inserir($item);
                $ordem++;
            }
            foreach ($pagamento->listarOpcao() as $opcao) {
                $opcao->setIdPagamento($id_pagamento);
                $dalOpcao->inserir($opcao);
            }
            if ($transaction === true) {
                DB::commit();
            }
        }
        catch (Exception $e){
            if ($transaction === true) {
                DB::rollBack();
            }
            throw $e;
        }
    }

    /**
     * @throws Exception
     * @param int $id_pagamento
     */
    public function excluir($id_pagamento) {
        $dalPagamento = new PagamentoDAL();
        $dalItem = new PagamentoItemDAL();
        $dalOpcao = new PagamentoOpcaoDAL();
        try{
            DB::beginTransaction();;
            $dalOpcao->limpar($id_pagamento);
            $dalItem->limpar($id_pagamento);
            $dalPagamento->excluir($id_pagamento);
            DB::commit();
        }
        catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
    }


    /**
     * @throws Exception
     * @param string $titulo
     * @param string $descricao
     * @param string $nome
     */
    protected function gravarInfo($titulo, $descricao, $nome) {
        $log = new LogInfo();
        $log->setCodTipo(LogInfo::INFORMACAO);
        $log->setNome($nome);
        $log->setTitulo($titulo);
        $log->setDescricao($descricao);

        $regraLog = new LogBLL();
        $regraLog->inserir($log);
    }

    /**
     * @throws Exception
     * @param string $titulo
     * @param string $descricao
     * @param string $nome
     */
    protected function gravarErro($titulo, $descricao, $nome) {
        $log = new LogInfo();
        $log->setCodTipo(LogInfo::ERRO);
        $log->setNome($nome);
        $log->setTitulo($titulo);
        $log->setDescricao($descricao);

        $regraLog = new LogBLL();
        $regraLog->inserir($log);
    }

    /**
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     * @return int
     */
    public abstract function pagar(PagamentoInfo $pagamento, $transaction = true);

    /**
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     * @return int
     */
    public abstract function pagarComToken(PagamentoInfo $pagamento, $transaction = true);

    /**
     * @param PagamentoInfo $pagamento
     * @param bool $transaction
     * @return int
     */
    public abstract function agendar(PagamentoInfo $pagamento, $transaction = true);

}