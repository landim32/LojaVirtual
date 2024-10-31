<?php

namespace Emagine\Pedido\Model;

use stdClass;
use JsonSerializable;
use Exception;
use Emagine\Pagamento\Factory\PagamentoFactory;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Pedido\DAL\PedidoItemDAL;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Login\Model\UsuarioInfo;

/**
 * Class PedidoInfo
 * @package EmaginePedido\Model
 * @tablename pedido
 * @author EmagineCRUD
 */
class PedidoInfo implements JsonSerializable {

    const GERENCIAR_PEDIDO = "gerenciar-pedido";

    const ENTREGAR = 1;
    const RETIRAR_NA_LOJA = 2;
    const RETIRADA_MAPEADA = 3;

	const CREDITO_ONLINE = 1;
	const DEBITO_ONLINE = 2;
    const BOLETO = 3;
    const DINHEIRO = 4;
    const CARTAO_OFFLINE = 5;

	const PENDENTE = 1;
	const AGUARDANDO_PAGAMENTO = 2;
	const ENTREGANDO = 3;
	const ENTREGUE = 4;
	const FINALIZADO = 5;
    const PREPARANDO = 6;
    const CANCELADO = 7;

	private $id_pedido;
	private $id_loja;
	private $id_usuario;
	private $id_pagamento;
	private $data_inclusao;
	private $ultima_alteracao;
	private $cod_entrega;
	private $cod_pagamento;
	private $cod_situacao;
	private $nota;
	private $valor_frete;
	private $troco_para;
	private $cep;
	private $logradouro;
	private $complemento;
	private $numero;
	private $bairro;
	private $cidade;
	private $uf;
	private $dia_entrega;
	private $horario_entrega;
	private $latitude;
	private $longitude;
	private $distancia;
	private $distancia_str;
	private $tempo;
	private $tempo_str;
	private $observacao;
	private $comentario;
	private $avisar = true;
	private $loja = null;
	private $usuario = null;
	private $pagamento = null;
	private $itens = null;

	/**
	 * Auto Increment Field
	 * @return int
	 */
	public function getId() {
		return $this->id_pedido;
	}

	/**
	 * @param int $value
	 */
	public function setId($value) {
		$this->id_pedido = $value;
	}

    /**
     * @return int
     */
	public function getIdLoja() {
	    return $this->id_loja;
    }

    /**
     * @param int $value
     */
    public function setIdLoja($value) {
	    $this->id_loja = $value;
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
	public function getIdPagamento() {
	    return $this->id_pagamento;
    }

    /**
     * @param int $value
     */
    public function setIdPagamento($value) {
	    $this->id_pagamento = $value;
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
     * @return int
     */
    public function getCodEntrega() {
        return $this->cod_entrega;
    }

    /**
     * @param int $value
     */
    public function setCodEntrega($value) {
        $this->cod_entrega = $value;
    }

    /**
     * @return string
     */
    public function getEntregaStr() {
        $bll = new PedidoBLL();
        $lista = $bll->listarEntrega();
        return $lista[$this->getCodEntrega()];
    }

	/**
	 * @return string
	 */
	public function getCodPagamento() {
		return $this->cod_pagamento;
	}

	/**
	 * @param string $value
	 */
	public function setCodPagamento($value) {
		$this->cod_pagamento = $value;
	}

	/**
	 * @return string
	 */
	public function getPagamentoStr() {
		$bll = new PedidoBLL();
		$lista = $bll->listarPagamento();
		return $lista[$this->getCodPagamento()];
	}

	/**
	 * @return int
	 */
	public function getCodSituacao() {
		return $this->cod_situacao;
	}

	/**
	 * @param int-option $value
	 */
	public function setCodSituacao($value) {
		$this->cod_situacao = $value;
	}

    /**
     * @return int
     */
	public function getNota() {
	    return $this->nota;
    }

    /**
     * @param int $value
     */
    public function setNota($value) {
	    $this->nota = $value;
    }

    /**
     * @return string
     */
    public function getNotaHtml() {
        $html = "";
        for ($i = 0; $i < $this->getNota(); $i++) {
            $html .= "<i class='fa fa-star'></i>";
        }
        for ($i = $this->getNota(); $i < 5; $i++) {
            $html .= "<i class='fa fa-star-o'></i>";
        }
        return $html;
    }

	/**
	 * @return string
	 */
	public function getSituacaoStr() {
		$bll = new PedidoBLL();
		$lista = $bll->listarSituacao();
		return $lista[$this->getCodSituacao()];
	}

    /**
     * @return string
     */
	public function getSituacaoHtml() {
	    switch ($this->getCodSituacao()) {
            case PedidoInfo::PENDENTE:
                $str = "label label-danger";
                break;
            case PedidoInfo::AGUARDANDO_PAGAMENTO:
                $str = "label label-warning";
                break;
            case PedidoInfo::ENTREGANDO:
                $str = "label label-warning";
                break;
            case PedidoInfo::ENTREGUE:
                $str = "label label-primary";
                break;
            case PedidoInfo::FINALIZADO:
                $str = "label label-success";
                break;
            default:
                $str = "label label-default";
        }
        return $str;
    }

    /**
     * @return int
     */
    public function getProximaAcao() {
	    $cod_situacao = 0;
	    switch ($this->getCodSituacao()) {
            case PedidoInfo::PREPARANDO:
                $cod_situacao = PedidoInfo::PENDENTE;
                break;
            case PedidoInfo::PENDENTE:
                $cod_situacao = PedidoInfo::ENTREGANDO;
                break;
            case PedidoInfo::ENTREGANDO:
                $cod_situacao = PedidoInfo::ENTREGUE;
                break;
            case PedidoInfo::ENTREGUE:
                $cod_situacao = PedidoInfo::FINALIZADO;
                break;
        }
        return $cod_situacao;
    }

    /**
     * @return int
     */
    public function getProximaAcaoStr() {
        $situacao = "";
        switch ($this->getCodSituacao()) {
            case PedidoInfo::PREPARANDO:
                if ($this->getCodEntrega() == PedidoInfo::ENTREGAR) {
                    $situacao = "<i class='fa fa-fw fa-check-circle'></i> Aguardando Entrega";
                }
                else {
                    $situacao = "<i class='fa fa-fw fa-check-circle'></i> Aguardando retirada";
                }
                break;
            case PedidoInfo::PENDENTE:
                $situacao = "<i class='fa fa-fw fa-car'></i> Enviar para Entrega";
                break;
            case PedidoInfo::ENTREGANDO:
                $situacao = "<i class='fa fa-fw fa-check-circle'></i> Marcar como Entregue";
                break;
            case PedidoInfo::ENTREGUE:
                $situacao = "<i class='fa fa-fw fa-thumbs-up'></i> Finalizar";
                break;
        }
        return $situacao;
    }

	/**
	 * @return double
	 */
	public function getValorFrete() {
		return $this->valor_frete;
	}

	/**
	 * @param double $value
	 */
	public function setValorFrete($value) {
		$this->valor_frete = $value;
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
	public function getDiaEntrega() {
	    return $this->dia_entrega;
    }

    /**
     * @param string $value
     */
    public function setDiaEntrega($value) {
	    $this->dia_entrega = $value;
    }

    /**
     * @return string
     */
    public function getHorarioEntrega() {
        return $this->horario_entrega;
    }

    /**
     * @param string $value
     */
    public function setHorarioEntrega($value) {
        $this->horario_entrega = $value;
    }

    /**
     * @return string
     */
    public function getDataEntregaStr() {
        $diaEntrega = strtotime($this->getDiaEntrega());
        if ($diaEntrega > 0) {
            return date("d/m/Y", $diaEntrega) . " " . $this->getHorarioEntrega();
        }
        return "";
    }

    /**
     * @return float
     */
	public function getLatitude() {
	    return $this->latitude;
    }

    /**
     * @param float $value
     */
    public function setLatitude($value) {
	    $this->latitude = $value;
    }

    /**
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * @param float $value
     */
    public function setLongitude($value) {
        $this->longitude = $value;
    }

    /**
     * @return int
     */
    public function getDistancia() {
        return $this->distancia;
    }

    /**
     * @param int $value
     */
    public function setDistancia($value) {
        $this->distancia = $value;
    }

    /**
     * @return string
     */
    public function getDistanciaStr() {
        return $this->distancia_str;
    }

    /**
     * @param string $value
     */
    public function setDistanciaStr($value) {
        $this->distancia_str = $value;
    }

    /**
     * @return int
     */
    public function getTempo() {
        return $this->tempo;
    }

    /**
     * @param int $value
     */
    public function setTempo($value) {
        $this->tempo = $value;
    }

    /**
     * @return string
     */
    public function getTempoStr() {
        return $this->tempo_str;
    }

    /**
     * @param string $value
     */
    public function setTempoStr($value) {
        $this->tempo_str = $value;
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
    public function getComentario() {
        return $this->comentario;
    }

    /**
     * @param string $value
     */
    public function setComentario($value) {
        $this->comentario = $value;
    }

    /**
     * @return bool
     */
    public function getAvisar() {
        return $this->avisar;
    }

    /**
     * @param bool $value
     */
    public function setAvisar($value) {
        $this->avisar = $value;
    }

    /**
     * @return string
     */
    public function getDataInclusaoStr() {
        return date("d/m/Y H:i", strtotime($this->getDataInclusao()));
    }

    /**
     * @return string
     */
    public function getUltimaAlteracaoStr() {
        return date("d/m/Y H:i", strtotime($this->getUltimaAlteracao()));
    }

    /**
     * @throws Exception
     * @return LojaInfo
     */
    public function getLoja() {
        if (is_null($this->loja) && $this->getIdLoja() > 0) {
            $bll = new LojaBLL();
            $this->loja = $bll->pegar($this->getIdLoja());
        }
        return $this->loja;
    }

    /**
     * @throws Exception
     * @return UsuarioInfo
     */
    public function getUsuario() {
        if (is_null($this->usuario) && $this->getIdUsuario() > 0) {
            $bll = new UsuarioBLL();
            $this->usuario = $bll->pegar($this->getIdUsuario());
        }
        return $this->usuario;
    }

    /**
     * @throws Exception
     * @return PagamentoInfo
     */
    public function getPagamento() {
        if (is_null($this->pagamento) && $this->getIdPagamento() > 0) {
            $bll = PagamentoFactory::create();
            $this->pagamento = $bll->pegar($this->getIdPagamento());
        }
        return $this->pagamento;
    }

	/**
	 * @return PedidoItemInfo[]
	 */
	public function listarItens() {
		if (is_null($this->itens)) {
		    if ($this->getId() > 0) {
                $dal = new PedidoItemDAL();
                $this->itens = $dal->listarPorPedido($this->getId());
            }
			else {
		        $this->itens = array();
            }
		}
		return $this->itens;
	}

	/**
	 * Limpa todos os Itens do Pedido relacionados com Pedidos.
	 */
	public function limparItens() {
		$this->itens = array();
	}

	/**
	 * @param PedidoItemInfo $item
	 */
	public function adicionarItem($item) {
		$this->listarItens();
		$this->itens[] = $item;
	}

    /**
     * @return float
     */
	public function getTotal() {
	    $total = $this->getValorFrete();
	    foreach ($this->listarItens() as $pedido) {
	        $produto = $pedido->getProduto();
	        if ($produto->getValorPromocao() > 0) {
                $valorProduto = $produto->getValorPromocao();
            }
            else {
                $valorProduto = $produto->getValor();
            }
	        $total += $valorProduto * $pedido->getQuantidade();
        }
        return $total;
    }

    /**
     * @return double
     */
    public function getTroco() {
	    return $this->getTrocoPara() - $this->getTotal();
    }

    /**
     * @return string
     */
    public function getValorFreteStr() {
        return number_format($this->getValorFrete(), 2, ",", ".");
    }

    /**
     * @return string
     */
    public function getTrocoParaStr() {
        return number_format($this->getTrocoPara(), 2, ",", ".");
    }

    /**
     * @return string
     */
    public function getTrocoStr() {
        return number_format($this->getTroco(), 2, ",", ".");
    }

    /**
     * @return string
     */
    public function getTotalStr() {
	    return number_format($this->getTotal(), 2, ",", ".");
    }


    /**
     * @return string
     */
    public function getPosicao() {
        if ($this->getLatitude() != 0 && $this->getLongitude() != 0) {
            $posicao  = number_format($this->getLatitude(), 5, ".", "");
            $posicao .= ",";
            $posicao .= number_format($this->getLongitude(), 5, ".", "");
            return $posicao;
        }
        return "";
    }

    /**
     * @var bool $cep
     * @var bool $posicao
     * @return string
     */
    public function getEnderecoCompleto($cep = true, $posicao = true) {
        $endereco = array();
        if (!isNullOrEmpty($this->getLogradouro())) {
            $endereco[] = $this->getLogradouro();
        }
        if (!isNullOrEmpty($this->getComplemento())) {
            $endereco[] = $this->getComplemento();
        }
        if (!isNullOrEmpty($this->getNumero())) {
            $endereco[] = $this->getNumero();
        }
        if (!isNullOrEmpty($this->getBairro())) {
            $endereco[] = $this->getBairro();
        }
        if (!isNullOrEmpty($this->getCidade())) {
            $endereco[] = $this->getCidade();
        }
        if (!isNullOrEmpty($this->getUf())) {
            $endereco[] = $this->getUf();
        }
        if ($cep == true && !isNullOrEmpty($this->getCep())) {
            $endereco[] = $this->getCep();
        }
        if ($posicao == true && !isNullOrEmpty($this->getPosicao())) {
            $endereco[] = $this->getPosicao();
        }
        return implode(", ", $endereco);
    }

	/**
     * @throws Exception
	 * @return stdClass
	 */
	public function jsonSerialize() {
		$value = new stdClass();
		$value->id_pedido = intval($this->getId());
		$value->id_loja = intval($this->getIdLoja());
		if ($this->getIdLoja() > 0 && !is_null($this->getLoja())) {
		    $value->loja = $this->getLoja()->jsonSerialize();
        }
		$value->id_usuario = intval($this->getIdUsuario());
        $value->usuario = $this->getUsuario();
		$value->id_pagamento = $this->getIdPagamento();
		if ($this->getIdPagamento() > 0 && !is_null($this->getPagamento())) {
            $value->pagamento = $this->getPagamento()->jsonSerialize();
        }
        $value->data_inclusao = $this->getDataInclusao();
        $value->data_inclusao_str = $this->getDataInclusaoStr();
        $value->ultima_alteracao = $this->getUltimaAlteracao();
        $value->ultima_alteracao_str = $this->getUltimaAlteracaoStr();
        $value->cod_entrega = $this->getCodEntrega();
        $value->entrega_str = $this->getEntregaStr();
		$value->cod_pagamento = $this->getCodPagamento();
		$value->pagamento_str = $this->getPagamentoStr();
		$value->cod_situacao = $this->getCodSituacao();
        $value->situacao_str = $this->getSituacaoStr();
        $value->nota = $this->getNota();
		$value->valor_frete = $this->getValorFrete();
        $value->valor_frete_str = $this->getValorFreteStr();
        $value->troco_para = $this->getTrocoPara();
        $value->troco_para_str = $this->getTrocoParaStr();
		$value->cep = $this->getCep();
		$value->logradouro = $this->getLogradouro();
		$value->complemento = $this->getComplemento();
		$value->numero = $this->getNumero();
		$value->bairro = $this->getBairro();
		$value->cidade = $this->getCidade();
		$value->uf = $this->getUf();
        $value->dia_entrega = $this->getDiaEntrega();
		$value->horario_entrega = $this->getHorarioEntrega();
		$value->latitude = $this->getLatitude();
		$value->longitude = $this->getLongitude();
		$value->tempo = $this->getTempo();
        $value->tempo_str = $this->getTempoStr();
        $value->distancia = $this->getDistancia();
        $value->distancia_str = $this->getDistanciaStr();
		$value->observacao = $this->getObservacao();
		$value->comentario = $this->getComentario();
		$value->avisar = $this->getAvisar();
		$value->itens = array();
		foreach ($this->listarItens() as $item) {
			$value->itens[] = $item->jsonSerialize();
		}
		$value->total = $this->getTotal();
        $value->total_str = $this->getTotalStr();
		//$value->itens = $this->listarItens();
		return $value;
	}

	/**
	 * @param stdClass $value
	 * @return PedidoInfo
	 */
	public static function fromJson($value) {
		$pedido = new PedidoInfo();
		$pedido->setId($value->id_pedido);
		$pedido->setIdLoja($value->id_loja);
		$pedido->setIdUsuario($value->id_usuario);
		$pedido->setIdPagamento($value->id_pagamento);
        $pedido->setCodEntrega($value->cod_entrega);
		$pedido->setCodPagamento($value->cod_pagamento);
		$pedido->setCodSituacao($value->cod_situacao);
		$pedido->setNota($value->nota);
		$pedido->setValorFrete($value->valor_frete);
		$pedido->setTrocoPara($value->troco_para);
		$pedido->setCep($value->cep);
		$pedido->setLogradouro($value->logradouro);
		$pedido->setComplemento($value->complemento);
		$pedido->setNumero($value->numero);
		$pedido->setBairro($value->bairro);
		$pedido->setCidade($value->cidade);
		$pedido->setUf($value->uf);
		$pedido->setDiaEntrega($value->dia_entrega);
		$pedido->setHorarioEntrega($value->horario_entrega);
		$pedido->setLatitude($value->latitude);
		$pedido->setLongitude($value->longitude);
		$pedido->setObservacao($value->observacao);
		$pedido->setComentario($value->comentario);
		$pedido->setAvisar($value->avisar);
		if (isset($value->itens)) {
			$pedido->limparItens();
			foreach ($value->itens as $item) {
				$pedido->adicionarItem(PedidoItemInfo::fromJson($item));
			}
		}
		return $pedido;
	}

}

