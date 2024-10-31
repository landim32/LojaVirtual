<?php
namespace Emagine\Pagamento\Model;

use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Pagamento\BLL\DepositoBLL;
use Emagine\Pagamento\DAL\PagamentoItemDAL;
use Emagine\Pagamento\DAL\PagamentoOpcaoDAL;
use Emagine\Pagamento\Factory\PagamentoFactory;
use JsonSerializable;
use stdClass;
use Exception;

class PagamentoInfo implements JsonSerializable
{
    const SITUACAO_ABERTO = 1;
    const SITUACAO_PAGO = 2;
    const SITUACAO_VERIFICANDO = 3;
    const SITUACAO_AGUARDANDO_PAGAMENTO = 4;
    const SITUACAO_CANCELADO = 5;

    const CREDITO_ONLINE = 1;
    const DEBITO_ONLINE = 2;
    const BOLETO = 3;
    const DINHEIRO = 4;
    const CARTAO_OFFLINE = 5;
    const DEPOSITO_BANCARIO = 6;

    const VISA = 1;
    const MASTERCARD = 2;
    const AMEX = 3;
    const ELO = 4;
    const AURA = 5;
    const JCB = 6;
    const DINERS = 7;
    const DISCOVER = 8;
    const HIPERCARD = 9;

    protected $id_pagamento;
    protected $id_usuario;
    protected $id_deposito;
    protected $data_inclusao;
    protected $ultima_alteracao;
    protected $data_vencimento;
    protected $data_pagamento;
    protected $valor_desconto;
    protected $valor_juro;
    protected $valor_multa;
    protected $troco_para;
    protected $cod_situacao;
    protected $observacao;
    protected $mensagem;

    protected $cod_tipo;
    protected $cod_bandeira;
    protected $numero_cartao;
    protected $data_expiracao;
    protected $nome_cartao;
    protected $cvv;
    protected $token;

    protected $cpf;
    protected $logradouro;
    protected $complemento;
    protected $numero;
    protected $bairro;
    protected $cidade;
    protected $uf;
    protected $cep;

    protected $boleto_url;
    protected $boleto_linha_digitavel;
    protected $autenticacao_url;

    protected $usuario = null;
    protected $deposito = null;
    protected $itens = null;
    protected $opcoes = null;

    /**
     * @return int
     */
    public function getId() {
        return $this->id_pagamento;
    }

    /**
     * @param int $value
     */
    public function setId($value) {
        $this->id_pagamento = $value;
    }

    /**
     * @return int
     */
    public function getIdUsuario() {
        return $this->id_usuario;
    }

    /**
     * @param int $value
     */
    public function setIdUsuario($value) {
        $this->id_usuario = $value;
    }

    /**
     * @return int
     */
    public function getIdDeposito() {
        return $this->id_deposito;
    }

    /**
     * @param int $value
     */
    public function setIdDeposito($value) {
        $this->id_deposito = $value;
    }

    /**
     * @throws Exception
     * @return DepositoInfo
     */
    public function getDeposito() {
        if (is_null($this->deposito) && $this->getIdDeposito() > 0) {
            $regraDeposito = new DepositoBLL();
            $this->deposito = $regraDeposito->pegar($this->getIdDeposito());
        }
        return $this->deposito;
    }

    /**
     * @return string
     */
    public function getDataInclusao() {
        return $this->data_inclusao;
    }

    /**
     * @param string $value
     */
    public function setDataInclusao($value) {
        $this->data_inclusao = $value;
    }

    /**
     * @return string
     */
    public function getUltimaAlteracao() {
        return $this->ultima_alteracao;
    }

    /**
     * @param string $value
     */
    public function setUltimaAlteracao($value) {
        $this->ultima_alteracao = $value;
    }

    /**
     * @return string
     */
    public function getDataVencimento() {
        return $this->data_vencimento;
    }

    /**
     * @param string $value
     */
    public function setDataVencimento($value) {
        $this->data_vencimento = $value;
    }

    /**
     * @return string
     */
    public function getDataPagamento() {
        return $this->data_pagamento;
    }

    /**
     * @param string $value
     */
    public function setDataPagamento($value) {
        $this->data_pagamento = $value;
    }

    /**
     * @return double
     */
    public function getValorDesconto() {
        return $this->valor_desconto;
    }

    /**
     * @param double $value
     */
    public function setValorDesconto($value) {
        $this->valor_desconto = $value;
    }

    /**
     * @return double
     */
    public function getValorJuro() {
        return $this->valor_juro;
    }

    /**
     * @param double $value
     */
    public function setValorJuro($value) {
        $this->valor_juro = $value;
    }

    /**
     * @return double
     */
    public function getValorMulta() {
        return $this->valor_multa;
    }

    /**
     * @param double $value
     */
    public function setValorMulta($value) {
        $this->valor_multa = $value;
    }

    /**
     * @return double
     */
    public function getTrocoPara() {
        return $this->troco_para;
    }

    /**
     * @param double $value
     */
    public function setTrocoPara($value) {
        $this->troco_para = $value;
    }

    /**
     * @return int
     */
    public function getCodSituacao() {
        return $this->cod_situacao;
    }

    /**
     * @param int $value
     */
    public function setCodSituacao($value) {
        $this->cod_situacao = $value;
    }

    /**
     * @return string
     */
    public function getObservacao() {
        return $this->observacao;
    }

    /**
     * @param string $value
     */
    public function setObservacao($value) {
        $this->observacao = $value;
    }

    /**
     * @return string
     */
    public function getMensagem() {
        return $this->mensagem;
    }

    /**
     * @param string $value
     */
    public function setMensagem($value) {
        $this->mensagem = $value;
    }

    /**
     * @return int
     */
    public function getCodTipo() {
        return $this->cod_tipo;
    }

    /**
     * @param int $value
     */
    public function setCodTipo($value) {
        $this->cod_tipo = $value;
    }

    /**
     * @return int
     */
    public function getCodBandeira() {
        return $this->cod_bandeira;
    }

    /**
     * @param int $value
     */
    public function setCodBandeira($value) {
        $this->cod_bandeira = $value;
    }

    /**
     * @return string
     */
    public function getNumeroCartao() {
        return $this->numero_cartao;
    }

    /**
     * @param string $value
     */
    public function setNumeroCartao($value) {
        $this->numero_cartao = $value;
    }

    /**
     * @return string
     */
    public function getDataExpiracao() {
        return $this->data_expiracao;
    }

    /**
     * @param string $value
     */
    public function setDataExpiracao($value) {
        $this->data_expiracao = $value;
    }

    /**
     * @return string
     */
    public function getNomeCartao() {
        return $this->nome_cartao;
    }

    /**
     * @param string $value
     */
    public function setNomeCartao($value) {
        $this->nome_cartao = $value;
    }

    /**
     * @return string
     */
    public function getCVV() {
        return $this->cvv;
    }

    /**
     * @param string $value
     */
    public function setCVV($value) {
        $this->cvv = $value;
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @param string $value
     */
    public function setToken($value) {
        $this->token = $value;
    }

    /**
     * @return string
     */
    public function getCpf() {
        return $this->cpf;
    }

    /**
     * @param string $value
     */
    public function setCpf($value) {
        $this->cpf = $value;
    }

    /**
     * @return string
     */
    public function getLogradouro() {
        return $this->logradouro;
    }

    /**
     * @param string $value
     */
    public function setLogradouro($value) {
        $this->logradouro = $value;
    }

    /**
     * @return string
     */
    public function getComplemento() {
        return $this->complemento;
    }

    /**
     * @param string $value
     */
    public function setComplemento($value) {
        $this->complemento = $value;
    }

    /**
     * @return string
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * @param string $value
     */
    public function setNumero($value) {
        $this->numero = $value;
    }

    /**
     * @return string
     */
    public function getBairro() {
        return $this->bairro;
    }

    /**
     * @param string $value
     */
    public function setBairro($value) {
        $this->bairro = $value;
    }

    /**
     * @return string
     */
    public function getCidade() {
        return $this->cidade;
    }

    /**
     * @param string $value
     */
    public function setCidade($value) {
        $this->cidade = $value;
    }

    /**
     * @return string
     */
    public function getUf() {
        return $this->uf;
    }

    /**
     * @param string $value
     */
    public function setUf($value) {
        $this->uf = $value;
    }

    /**
     * @return string
     */
    public function getCep() {
        return $this->cep;
    }

    /**
     * @param string $value
     */
    public function setCep($value) {
        $this->cep = $value;
    }

    /**
     * @return string
     */
    public function getBoletoUrl() {
        return $this->boleto_url;
    }

    /**
     * @param string $value
     */
    public function setBoletoUrl($value) {
        $this->boleto_url = $value;
    }

    /**
     * @return string
     */
    public function getBoletoLinhaDigitavel() {
        return $this->boleto_linha_digitavel;
    }

    /**
     * @param string $value
     */
    public function setBoletoLinhaDigitavel($value) {
        $this->boleto_linha_digitavel = $value;
    }

    /**
     * @return string
     */
    public function getAutenticacaoUrl() {
        return $this->autenticacao_url;
    }

    /**
     * @param string $value
     */
    public function setAutenticacaoUrl($value) {
        $this->autenticacao_url = $value;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getTipo() {
        $regraPagamento = PagamentoFactory::create();
        $tipos = $regraPagamento->listarTipo();
        return $tipos[$this->getCodTipo()];
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getBandeira() {
        $regraPagamento = PagamentoFactory::create();
        $bandeiras = $regraPagamento->listarBandeira();
        if (array_key_exists($this->getCodBandeira(), $bandeiras)) {
            return $bandeiras[$this->getCodBandeira()];
        }
        else {
            return "-";
        }
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getSituacao() {
        $regraPagamento = PagamentoFactory::create();
        $situacoes = $regraPagamento->listarSituacao();
        return $situacoes[$this->getCodSituacao()];
    }

    /**
     * @return string
     */
    public function getSituacaoClasse() {
        $classe = "";
        switch ($this->getCodSituacao()) {
            case PagamentoInfo::SITUACAO_ABERTO:
                $classe = "label label-primary";
                break;
            case PagamentoInfo::SITUACAO_PAGO:
                $classe = "label label-success";
                break;
            case PagamentoInfo::SITUACAO_VERIFICANDO:
                $classe = "label label-info";
                break;
            case PagamentoInfo::SITUACAO_AGUARDANDO_PAGAMENTO:
                $classe = "label label-warning";
                break;
            case PagamentoInfo::SITUACAO_CANCELADO:
                $classe = "label label-danger";
                break;
        }
        return $classe;
    }

    /**
     * @throws Exception
     * @return UsuarioInfo
     */
    public function getUsuario() {
        if (is_null($this->usuario) && $this->getIdUsuario() > 0) {
            $regraUsuario = new UsuarioBLL();
            $this->usuario = $regraUsuario->pegar($this->getIdUsuario());
        }
        return $this->usuario;
    }

    /**
     * @return int
     */
    public function getMesValidade() {
        if (isNullOrEmpty($this->getDataExpiracao())) {
            return 0;
        }
        $data = strtotime($this->getDataExpiracao());
        return intval(date('n', $data));
    }

    /**
     * @return int
     */
    public function getAnoValidade() {
        if (isNullOrEmpty($this->getDataExpiracao())) {
            return 0;
        }
        $data = strtotime($this->getDataExpiracao());
        return intval(date('Y', $data));
    }

    /**
     * @throws Exception
     * @return double
     */
    public function getValor() {
        $valor = 0;
        foreach ($this->listarItem() as $item) {
            $valor += $item->getValor() * $item->getQuantidade();
        }
        return $valor;
    }

    /**
     * @return double
     * @throws Exception
     */
    public function getTotal() {
        $valor = $this->getValor();
        $valor -= $this->getValorDesconto();
        $valor += $this->getValorMulta();
        $valor += $this->getValorJuro();
        return $valor;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getValorStr() {
        $valor = $this->getValor();
        if ($valor > 0) {
            return "R$ " . number_format($valor, 2, ",", ".");
        }
        else {
            return "-";
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getTotalStr() {
        $total = $this->getTotal();
        if ($total > 0) {
            return "R$ " . number_format($total, 2, ",", ".");
        }
        else {
            return "-";
        }
    }

    /**
     * @throws Exception
     * @return PagamentoItemInfo[]
     */
    public function listarItem() {
        if (is_null($this->itens) && $this->getId() > 0) {
            $dalItem = new PagamentoItemDAL();
            $this->itens = $dalItem->listar($this->getId());
        }
        return $this->itens;
    }

    /**
     * @param PagamentoItemInfo $item
     */
    public function adicionarItem($item) {
        $this->itens[] = $item;
    }

    public function limparItem() {
        $this->itens = array();
    }

    /**
     * @throws Exception
     * @return PagamentoOpcaoInfo[]
     */
    public function listarOpcao() {
        if (is_null($this->opcoes)) {
            if ($this->getId() > 0) {
                $dalOpcao = new PagamentoOpcaoDAL();
                $this->opcoes = $dalOpcao->listar($this->getId());
            }
            else {
                $this->opcoes = array();
            }
        }
        return $this->opcoes;
    }

    /**
     * @param PagamentoOpcaoInfo $item
     */
    public function adicionarOpcao($item) {
        $this->opcoes[] = $item;
    }

    public function limparOpcao() {
        $this->opcoes = array();
    }

    /**
     * @param $chave
     * @return PagamentoOpcaoInfo|string
     * @throws Exception
     */
    public function getOpcao($chave) {
        $opcoes = $this->listarOpcao();
        if (array_key_exists($chave, $opcoes)) {
            return $opcoes[$chave]->getValor();
        }
        return "";
    }

    /**
     * @param string $chave
     * @param string $valor
     * @throws Exception
     */
    public function setOpcao($chave, $valor) {
        if (isNullOrEmpty($valor)) {
            return;
        }
        $achou = false;
        foreach ($this->listarOpcao() as $opcao) {
            if ($opcao->getChave() == $chave) {
                $opcao->setValor($valor);
                $achou = true;
                break;
            }
        }
        if (!$achou) {
            $opcao = new PagamentoOpcaoInfo();
            $opcao->setChave($chave);
            $opcao->setValor($valor);
            $this->adicionarOpcao($opcao);
        }
    }

    /**
     * @return string
     */
    public function getDataVencimentoStr() {
        return date("d/m/Y", strtotime($this->getDataVencimento()));
    }

    /**
     * @return string
     */
    public function getDataPagamentoStr() {
        return date("d/m/Y", strtotime($this->getDataPagamento()));
    }

    /**
     * @param stdClass $value
     * @return PagamentoInfo
     */
    public static function fromJson($value) {
        $pagamento = new PagamentoInfo();
        $pagamento->setId($value->id_pagamento);
        $pagamento->setIdUsuario($value->id_usuario);
        $pagamento->setIdDeposito($value->id_deposito);
        $pagamento->setDataInclusao($value->data_inclusao);
        $pagamento->setUltimaAlteracao($value->ultima_alteracao);
        $pagamento->setDataVencimento($value->data_vencimento);
        $pagamento->setDataPagamento($value->data_pagamento);
        $pagamento->setValorDesconto($value->valor_desconto);
        $pagamento->setValorJuro($value->valor_juro);
        $pagamento->setValorMulta($value->valor_multa);
        $pagamento->setTrocoPara($value->troco_para);
        $pagamento->setCodSituacao($value->cod_situacao);
        $pagamento->setObservacao($value->observacao);
        $pagamento->setMensagem($value->mensagem);

        $pagamento->setCodTipo($value->cod_tipo);
        $pagamento->setCodBandeira($value->cod_bandeira);
        $pagamento->setNumeroCartao($value->numero_cartao);
        $pagamento->setDataExpiracao($value->data_expiracao);
        $pagamento->setNomeCartao($value->nome_cartao);
        $pagamento->setCVV($value->cvv);
        $pagamento->setToken($value->token);

        $pagamento->setCpf($value->cpf);
        $pagamento->setLogradouro($value->logradouro);
        $pagamento->setNumero($value->numero);
        $pagamento->setBairro($value->bairro);
        $pagamento->setCidade($value->cidade);
        $pagamento->setUf($value->uf);
        $pagamento->setCep($value->cep);

        $pagamento->setBoletoUrl($value->boleto_url);
        $pagamento->setAutenticacaoUrl($value->autenticacao_url);

        $pagamento->limparItem();
        if (isset($value->itens) && count($value->itens) > 0) {
            foreach ($value->itens as $item) {
                $pagamento->adicionarItem(PagamentoItemInfo::fromJson($item));
            }
        }

        $pagamento->limparOpcao();
        if (isset($value->opcoes) && count($value->opcoes) > 0) {
            foreach ($value->opcoes as $opcao) {
                $pagamento->adicionarOpcao(PagamentoOpcaoInfo::fromJson($opcao));
            }
        }

        return $pagamento;
    }

    /**
     * @throws Exception
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $pagamento = new stdClass();
        $pagamento->id_pagamento = $this->getId();
        $pagamento->id_usuario = $this->getIdUsuario();
        $pagamento->id_deposito = $this->getIdDeposito();
        if ($this->getIdDeposito() > 0) {
            $pagamento->deposito = $this->getDeposito()->jsonSerialize();
        }

        $pagamento->data_inclusao = $this->getDataInclusao();
        $pagamento->ultima_alteracao = $this->getUltimaAlteracao();
        $pagamento->data_vencimento = $this->getDataVencimento();
        $pagamento->data_pagamento = $this->getDataPagamento();
        $pagamento->valor_desconto = $this->getValorDesconto();
        $pagamento->valor_juro = $this->getValorJuro();
        $pagamento->valor_multa = $this->getValorMulta();
        $pagamento->troco_para = $this->getTrocoPara();
        $pagamento->cod_situacao = $this->getCodSituacao();
        $pagamento->situacao = $this->getSituacao();
        $pagamento->observacao = $this->getObservacao();
        $pagamento->mensagem = $this->getMensagem();

        $pagamento->cod_tipo = $this->getCodTipo();
        $pagamento->tipo = $this->getTipo();
        $pagamento->cod_bandeira = $this->getCodBandeira();
        $pagamento->bandeira = $this->getBandeira();
        $pagamento->numero_cartao = $this->getNumeroCartao();
        $pagamento->data_expiracao = $this->getDataExpiracao();
        $pagamento->nome_cartao = $this->getNomeCartao();
        $pagamento->cvv = $this->getCVV();
        $pagamento->token = $this->getToken();

        $pagamento->cpf = $this->getCpf();
        $pagamento->logradouro = $this->getLogradouro();
        $pagamento->numero = $this->getNumero();
        $pagamento->bairro = $this->getBairro();
        $pagamento->cidade = $this->getCidade();
        $pagamento->uf = $this->getUf();
        $pagamento->cep = $this->getCep();

        $pagamento->boleto_url = $this->getBoletoUrl();
        $pagamento->autenticacao_url = $this->getAutenticacaoUrl();

        $pagamento->itens = array();
        foreach ($this->listarItem() as $item) {
            $pagamento->itens[] = $item->jsonSerialize();
        }

        $pagamento->opcoes = array();
        foreach ($this->listarOpcao() as $opcao) {
            $pagamento->opcoes[] = $opcao->jsonSerialize();
        }
        return $pagamento;
    }
}