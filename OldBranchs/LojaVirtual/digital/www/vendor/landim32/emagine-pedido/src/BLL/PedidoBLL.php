<?php

namespace Emagine\Pedido\BLL;

use Emagine\Pagamento\Factory\PagamentoFactory;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pedido\Model\PedidoMensagemInfo;
use Emagine\Social\Factory\MensagemFactory;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Base\BLL\EmailBLL;
use Emagine\Grafico\BLL\GraficoLinhaBLL;
use Emagine\Grafico\BLL\GraficoPizzaBLL;
use Emagine\Produto\BLL\LojaFreteBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Pedido\DAL\PedidoItemDAL;
use Emagine\Pedido\DAL\PedidoDAL;
use Emagine\Pedido\Model\PedidoItemInfo;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Pedido\Model\PedidoEnvioInfo;
use Emagine\Pedido\Model\PedidoRetornoInfo;
use Emagine\Produto\Model\LojaFreteInfo;
use Emagine\Grafico\Model\EstatisticaInfo;
use Emagine\Pedido\Model\PedidoEstatisticaInfo;
use Emagine\Pedido\Model\PedidoSituacaoInfo;
use Emagine\Pedido\Model\ProdutoVendidoInfo;
use Landim32\GoogleDirectionApi\BLL\GoogleDirectionApi;
use Landim32\GoogleDirectionApi\Model\GDResponse;

/**
 * Class PedidoBLL
 * @package EmaginePedido\BLL
 * @tablename pedido
 * @author EmagineCRUD
 */
class PedidoBLL {

    /**
     * @return string
     */
    public static function getRetiradaMapeadaTexto() {
        if (defined("RETIRADA_MAPEADA_TEXTO")) {
            return RETIRADA_MAPEADA_TEXTO;
        }
        return "Retirada Mapeada";
    }

    /**
     * @return bool
     */
    public function podeEnviarEmail() {
        if (defined("PEDIDO_ENVIAR_EMAIL")) {
            return (PEDIDO_ENVIAR_EMAIL == true);
        }
        return true;
    }

    /**
     * @return array<string,string>
     */
    public function listarEntrega() {
        return array(
            PedidoInfo::ENTREGAR => 'Entregar',
            PedidoInfo::RETIRAR_NA_LOJA => 'Retirar na Loja',
            //PedidoInfo::RETIRADA_MAPEADA => "Retirada mapeada"
            PedidoInfo::RETIRADA_MAPEADA => PedidoBLL::getRetiradaMapeadaTexto()
        );
    }

	/**
	 * @return array<string,string>
	 */
	public function listarPagamento() {
		return array(
			PedidoInfo::DINHEIRO => 'Dinheiro',
			PedidoInfo::CREDITO_ONLINE => 'Cartão de crédito',
			PedidoInfo::DEBITO_ONLINE => 'Cartão de débito',
            PedidoInfo::BOLETO => 'Boleto Bancário',
            PedidoInfo::CARTAO_OFFLINE => 'Vale/Ticket/Cartão',
		);
	}

	/**
	 * @return array<string,string>
	 */
	public function listarSituacao() {
		return array(
		    PedidoInfo::AGUARDANDO_PAGAMENTO => "Aguardando Pagamento",
            PedidoInfo::PREPARANDO => "Preparando",
			PedidoInfo::PENDENTE => "Pendente",
			PedidoInfo::ENTREGANDO => "Entregando",
			PedidoInfo::ENTREGUE => "Entregue",
			PedidoInfo::FINALIZADO => "Finalizado",
            PedidoInfo::CANCELADO => "Cancelado"
		);
	}

	/**
     * @throws Exception
     * @param int $id_loja
     * @param int $cod_situacao
	 * @return PedidoInfo[]
	 */
	public function listar($id_loja, $cod_situacao = 0) {
		$dal = new PedidoDAL();
		$situacoes = array();
		if ($cod_situacao > 0) {
            $situacoes[] = $cod_situacao;
        }
		return $dal->listar($id_loja, 0, 0, $situacoes);
	}

    /**
     * @param string $dataInicio
     * @param string $dataFim
     * @param int $id_loja
     * @return PedidoInfo[]
     * @throws Exception
     */
    public function listarVendidoPorData($dataInicio, $dataFim, $id_loja = 0) {
        $dal = new PedidoDAL();
        $situacoes = array(PedidoInfo::ENTREGUE, PedidoInfo::FINALIZADO);
        return $dal->listar($id_loja, 0, 0, $situacoes, $dataInicio, $dataFim);
    }

    /**
     * @param int $id_loja
     * @return PedidoInfo[]
     * @throws Exception
     */
    public function listarEntregando($id_loja = 0) {
        $dal = new PedidoDAL();
        $situacoes = array(
            PedidoInfo::PREPARANDO,
            PedidoInfo::ENTREGANDO,
            PedidoInfo::PENDENTE
        );
        return $dal->listar($id_loja, 0, PedidoInfo::ENTREGAR, $situacoes);
    }

    /**
     * @param int $id_loja
     * @return PedidoInfo[]
     * @throws Exception
     */
    public function listarRetiradaMapeada($id_loja = 0) {
        $dal = new PedidoDAL();
        $situacoes = array(
            PedidoInfo::PREPARANDO,
            PedidoInfo::ENTREGANDO,
            PedidoInfo::PENDENTE
        );
        return $dal->listar($id_loja, 0, PedidoInfo::RETIRADA_MAPEADA, $situacoes);
    }

	/**
     * @throws Exception
	 * @param int $id_usuario
     * @param int $cod_situacao
	 * @return PedidoInfo[]
	 */
	public function listarPorUsuario($id_usuario, $cod_situacao = 0) {
		$dal = new PedidoDAL();
        $situacoes = array();
        if ($cod_situacao > 0) {
            $situacoes[] = $cod_situacao;
        }
		return $dal->listar(0, $id_usuario, 0, $situacoes);
	}

    /**
     * @throws Exception
     * @param int $id_loja
     * @param int $id_usuario
     * @return PedidoInfo[]
     */
    public function listarAvaliacao($id_loja = 0, $id_usuario = 0) {
        $dal = new PedidoDAL();
        $situacoes = array(
            PedidoInfo::ENTREGUE,
            PedidoInfo::RETIRADA_MAPEADA,
            PedidoInfo::FINALIZADO
        );
        return $dal->listarAvaliacao($id_loja, $id_usuario, $situacoes);
    }

	/**
     * @throws Exception
	 * @param int $id_pedido
	 * @return PedidoInfo
	 */
	public function pegar($id_pedido) {
		$dal = new PedidoDAL();
		return $dal->pegar($id_pedido);
	}

	/**
	 * @throws Exception
	 * @param PedidoInfo $pedido
	 */
	protected function validar(&$pedido) {
        if ($pedido->getIdLoja() == 0) {
            throw new Exception('Selecione a loja.');
        }
		if ($pedido->getIdUsuario() == 0) {
			throw new Exception('Selecione o usuário.');
		}
		if (isNullOrEmpty($pedido->getCodPagamento())) {
			throw new Exception('Selecione a forma de pagamento.');
		}
		if (isNullOrEmpty($pedido->getCodSituacao())) {
			throw new Exception('Selecione a situação.');
		}
        $pedido->setTrocoPara(floatvalx($pedido->getTrocoPara()));
		if ($pedido->getCodEntrega() == PedidoInfo::ENTREGAR) {
            $cep = $pedido->getCep();
            $cep = preg_replace("/[^0-9]/", "", $cep);
            if (strlen($cep) > 10) {
                $cep = cortarTexto( $cep, 10);
            }
            $pedido->setCep($cep);

            if (strlen($pedido->getLogradouro()) > 60) {
                $pedido->setLogradouro(cortarTexto($pedido->getLogradouro(), 60));
            }
            if (strlen($pedido->getComplemento()) > 40) {
                $pedido->setComplemento(cortarTexto($pedido->getComplemento(), 40));
            }
            if (strlen($pedido->getNumero()) > 20) {
                $pedido->setNumero(cortarTexto($pedido->getNumero(), 20));
            }
            if (strlen($pedido->getBairro()) > 60) {
                $pedido->setBairro(cortarTexto($pedido->getBairro(), 60));
            }
            if (strlen($pedido->getCidade()) > 50) {
                $pedido->setCidade(cortarTexto($pedido->getCidade(), 50));
            }
            $pedido->setUf(strtoupper(cortarTexto($pedido->getUf(), 2)));
		    if (isNullOrEmpty($pedido->getLogradouro())) {
			    throw new Exception('Preencha o logradouro.');
		    }
		    if (isNullOrEmpty($pedido->getNumero())) {
			    throw new Exception('Preencha o número.');
		    }
		    if (isNullOrEmpty($pedido->getBairro())) {
			    throw new Exception('Preencha o bairro.');
		    }
		    if (isNullOrEmpty($pedido->getCidade())) {
			    throw new Exception('Preencha a cidade.');
		    }
		    if (isNullOrEmpty($pedido->getUf())) {
			    throw new Exception('Preencha a UF.');
		    }
            if ($pedido->getCodPagamento() == PedidoInfo::DINHEIRO) {
                if (!($pedido->getTrocoPara() > 0)) {
                    throw new Exception('Preencha o troco.');
                }
            }
        }
        if (strlen($pedido->getObservacao()) > 300) {
            $pedido->setObservacao(cortarTexto($pedido->getObservacao(), 300));
        }
	}

    /**
     * @param PedidoItemInfo $item
     * @throws Exception
     */
	private function validarItem(PedidoItemInfo &$item) {
	    if (!($item->getIdProduto() > 0)) {
            throw new Exception('Item do pedido não está vinculado a nenhum produto.');
        }
        if (!($item->getQuantidade() > 0)) {
            throw new Exception('A quantidade de produtos no pedido precisa ser maior que 0.');
        }
    }

	/**
	 * @throws Exception
	 * @param PedidoInfo $pedidoOrig
	 * @return int
	 */
	public function inserir($pedidoOrig) {
		//$id_pedido = 0;
		$this->validar($pedidoOrig);
        if ($pedidoOrig->getCodEntrega() == PedidoInfo::ENTREGAR) {
            $this->carregarValorFrete($pedidoOrig);
        }

		$dal = new PedidoDAL();
		$dalItem = new PedidoItemDAL();
		try{
		    DB::beginTransaction();
			$id_pedido = $dal->inserir($pedidoOrig);
			foreach ($pedidoOrig->listarItens() as $item) {
			    $this->validarItem($item);
				$item->setIdPedido($id_pedido);
                $dalItem->inserir($item);
			}
            $pedido = $this->pegar($id_pedido);
			$loja = $pedido->getLoja();
			if ($loja->getControleEstoque() == true) {
			    $this->debitarQuantidade($pedido);
            }
			DB::commit();
		}
		catch (Exception $e){
		    DB::rollBack();
            //$id_pedido = 0;
			throw $e;
		}
		if ($this->podeEnviarEmail() && $pedidoOrig->getAvisar() == true && $id_pedido > 0) {
		    $pedido = $this->pegar($id_pedido);
		    $this->enviarEmail($pedido);
        }
		return $id_pedido;
	}

	/**
	 * @throws Exception
	 * @param PedidoInfo $pedidoOrig
	 */
	public function alterar($pedidoOrig) {
		$this->validar($pedidoOrig);
        if ($pedidoOrig->getCodSituacao() == PedidoInfo::CANCELADO) {
            throw new Exception("Não é possível alterar um pedido cancelado.");
        }
		$dal = new PedidoDAL();
		$dalItem = new PedidoItemDAL();
		try{
		    DB::beginTransaction();
			$dal->alterar($pedidoOrig);
			$id_pedido = $pedidoOrig->getId();
            $pedidoOrig->listarItens();
            $dalItem->limparPorPedido($pedidoOrig->getId());
			foreach ($pedidoOrig->listarItens() as $item) {
                $this->validarItem($item);
				$item->setIdPedido($id_pedido);
                $dalItem->inserir($item);
			}
            //$this->enviarEmail($pedido);
			DB::commit();
		}
		catch (Exception $e){
		    DB::rollBack();
			throw $e;
		}
        if ($this->podeEnviarEmail() && $pedidoOrig->getAvisar() == true && $id_pedido > 0) {
            $pedido = $this->pegar($id_pedido);
            $this->enviarEmail($pedido);
        }
	}

    /**
     * @throws Exception
     * @param PedidoSituacaoInfo $situacao
     */
	public function alterarSituacao(PedidoSituacaoInfo $situacao) {
	    $pedido = $this->pegar($situacao->getIdPedido());
	    $loja = $pedido->getLoja();
	    if ($pedido->getCodSituacao() == PedidoInfo::CANCELADO) {
	        throw new Exception("Não é possível alterar um pedido cancelado.");
        }
        if ($situacao->getCodSituacao() == PedidoInfo::CANCELADO) {
            if ($pedido->getCodSituacao() == PedidoInfo::ENTREGANDO) {
                throw new Exception("Não é possível cancelar um pedido a caminho da entrega.");
            }
            if ($pedido->getCodSituacao() == PedidoInfo::ENTREGUE) {
                throw new Exception("Não é possível cancelar um pedido entregue.");
            }
            if ($pedido->getCodSituacao() == PedidoInfo::FINALIZADO) {
                throw new Exception("Não é possível cancelar um pedido finalizado.");
            }
            if ($pedido->getCodPagamento() == PedidoInfo::CREDITO_ONLINE) {
                throw new Exception("Não é possível cancelar um pedido pago com cartão de crédito.");
            }
            if ($pedido->getCodPagamento() == PedidoInfo::DEBITO_ONLINE) {
                throw new Exception("Não é possível cancelar um pedido pago com cartão de débito.");
            }
        }
        $dal = new PedidoDAL();
        $regraPagamento = PagamentoFactory::create();
        try{
            DB::beginTransaction();
            $dal->alterarSituacao($situacao->getIdPedido(), $situacao->getCodSituacao());

            if ($pedido->getIdPagamento() > 0) {
                $pagamento = $regraPagamento->pegar($pedido->getIdPagamento());
                $situacoesPgto = array(
                    PagamentoInfo::SITUACAO_ABERTO,
                    PagamentoInfo::SITUACAO_VERIFICANDO,
                    PagamentoInfo::SITUACAO_AGUARDANDO_PAGAMENTO
                );
                $situacoesPedido = array(
                    PedidoInfo::PREPARANDO,
                    PedidoInfo::ENTREGANDO,
                    PedidoInfo::ENTREGUE,
                    PedidoInfo::FINALIZADO
                );
                if (
                    in_array($pagamento->getCodSituacao(), $situacoesPgto) &&
                    in_array($situacao->getCodSituacao(), $situacoesPedido)
                ) {
                    $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_PAGO);
                    $regraPagamento->alterar($pagamento, false);
                }
                elseif ($situacao->getCodSituacao() == PedidoInfo::CANCELADO) {
                    $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_CANCELADO);
                    $regraPagamento->alterar($pagamento, false);
                }
            }

            $regraMensagem = MensagemFactory::create();
            $mensagem = "";
            if (!isNullOrEmpty($situacao->getMensagem())) {
                $mensagem = $situacao->getMensagem();
            }
            else {
                switch ($situacao->getCodSituacao()) {
                    case PedidoInfo::PENDENTE:
                        $mensagem = "Pedido #%s está sendo verificado";
                        break;
                    case PedidoInfo::AGUARDANDO_PAGAMENTO:
                        $mensagem = "Estamos aguardadno o pagamento do pedido #%s";
                        break;
                    case PedidoInfo::ENTREGANDO:
                        $mensagem = "Pedido #%s saiu para entrega";
                        break;
                    case PedidoInfo::ENTREGUE:
                        $mensagem = "Pedido #%s foi entregue";
                        break;
                    case PedidoInfo::FINALIZADO:
                        $mensagem = "Pedido #%s teve sua entrega confirmada";
                        break;
                    case PedidoInfo::PREPARANDO:
                        $mensagem = "Pedido #%s está sendo preparado";
                        break;
                    case PedidoInfo::CANCELADO:
                        $mensagem = "Pedido #%s foi cancelado";
                        break;
                }
                $mensagem = sprintf($mensagem, $situacao->getIdPedido());
            }
            $url = SITE_URL . "/" . $loja->getSlug() . "/pedido/id/" . $situacao->getIdPedido();
            $regraMensagem->inserir(
                (new PedidoMensagemInfo())
                    ->setIdUsuario($situacao->getIdUsuario())
                    ->setIdPedido($situacao->getIdPedido())
                    ->setMensagem($mensagem)
                    ->setUrl($url)
                    ->setLido(false)
            );
            if ($loja->getControleEstoque() == true && $situacao->getCodSituacao() == PedidoInfo::CANCELADO) {
                $this->reporQuantidade($pedido);
            }
            DB::commit();
        }
        catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
        if ($this->podeEnviarEmail() && $situacao->getIdPedido() > 0) {
            $pedido = $this->pegar($situacao->getIdPedido());
            $this->enviarEmail($pedido, $situacao->getMensagem());
        }
    }

    /**
     * @throws Exception
     * @param PedidoInfo $pedido
     */
    public function debitarQuantidade($pedido) {
        $regraProduto = new ProdutoBLL();
        foreach ($pedido->listarItens() as $item) {
            $quantidade = $regraProduto->pegarQuantidade($item->getIdProduto());
            if ($quantidade < $item->getQuantidade()) {
                $mensagem = "%s possui apenas %s unidade(s), mas você está querendo %s.";
                throw new Exception(sprintf($mensagem, $item->getProduto()->getNome(), $quantidade, $item->getQuantidade()));
            }
            $quantidade -= $item->getQuantidade();
            $regraProduto->alterarQuantidade($item->getIdProduto(), $quantidade);
        }
    }

    /**
     * @param PedidoInfo $pedido
     * @throws Exception
     */
    public function reporQuantidade($pedido) {
        $regraProduto = new ProdutoBLL();
        foreach ($pedido->listarItens() as $item) {
            $quantidade = $regraProduto->pegarQuantidade($item->getIdProduto());
            $quantidade += $item->getQuantidade();
            $regraProduto->alterarQuantidade($item->getIdProduto(), $quantidade);
        }
    }

    /**
     * @throws Exception
     * @param PedidoInfo $pedido
     * @param string $mensagem
     * @return string
     */
	public function gerarEmail($pedido, $mensagem = null) {
        ob_start();
        include dirname(__DIR__) . "/templates/pedido-email.php";
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @throws Exception
     * @param PedidoInfo $pedido
     * @return string
     */
    public function gerarAssunto(PedidoInfo $pedido) {
	    $assunto = "";
        switch ($pedido->getCodSituacao()) {
            case PedidoInfo::PENDENTE:
                $assunto = "Pedido #%s recebido";
                break;
            case PedidoInfo::PREPARANDO:
                $assunto = "Pedido #%s está sendo preparado";
                break;
            case PedidoInfo::AGUARDANDO_PAGAMENTO:
                $assunto = "Pedido #%s aguardando pagamento";
                break;
            case PedidoInfo::ENTREGANDO:
                $assunto = "Pedido #%s saiu para entrega";
                break;
            case PedidoInfo::ENTREGUE:
                $assunto = "Pedido #%s foi entregue";
                break;
            case PedidoInfo::FINALIZADO:
                $assunto = "Pedido #%s foi finalizado";
                break;
            case PedidoInfo::CANCELADO:
                $assunto = "Pedido #%s foi cancelado";
                break;
        }
        return sprintf($assunto, $pedido->getId());
    }

    /**
     * @throws Exception
     * @param PedidoInfo $pedido
     * @param string $mensagem
     */
    public function enviarEmail($pedido, $mensagem = null) {
        $assunto = $this->gerarAssunto($pedido) . " - " . $pedido->getLoja()->getNome();
        $conteudo = $this->gerarEmail($pedido, $mensagem);
	    //$regraEmail = new MailJetBLL();
        $regraEmail = new EmailBLL();
        $regraEmail->sendmail($pedido->getUsuario()->getEmail(), $assunto, $conteudo, NOME_REMETENTE, EMAIL_REMETENTE);
        $regraEmail->sendmail($pedido->getLoja()->getEmail(), $assunto, $conteudo, NOME_REMETENTE, EMAIL_REMETENTE);
    }

    /**
     * @param string[] $rotas
     * @return GDResponse
     * @throws Exception
     */
    public function calcularRota($rotas) {
        if (!defined("GOOGLE_MAPS_API")) {
            throw new Exception("GOOGLE_MAPS_API não está definido.");
        }
        $gd = new GoogleDirectionApi(GOOGLE_MAPS_API);
        $gd->setOrigin($rotas[0]);
        $gd->setDestination($rotas[count($rotas) - 1]);

        $gd->clearWaypoints();
        if (count($rotas) > 2) {
            $waypoints = array_slice($rotas, 1, count($rotas) - 2);
            if (count($waypoints) > 0) {
                foreach ($waypoints as $waypoint) {
                    $gd->addWaypoint($waypoint);
                }
            }
        }
        return $gd->execute();
    }

    /**
     * @param PedidoEnvioInfo $envio
     * @return PedidoRetornoInfo
     * @throws Exception
     */
    public function atualizar($envio) {
        $retorno = new PedidoRetornoInfo();
        $pedido = $this->pegar($envio->getIdPedido());
        $pedido->setLatitude($envio->getLatitude());
        $pedido->setLongitude($envio->getLongitude());

        $retorno->setIdPedido($envio->getIdPedido());
        $retorno->setLatitude($envio->getLatitude());
        $retorno->setLongitude($envio->getLongitude());

        $rota = $this->calcularRota(array(
            $envio->getPosicaoStr(),
            $pedido->getLoja()->getPosicaoStr()
        ));
        if ($rota->getStatus() == "OK") {
            $pedido->setDistancia($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getValue());
            $pedido->setDistanciaStr($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getText());
            $pedido->setTempo($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getValue());
            $pedido->setTempoStr($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getText());

            $retorno->setCodSituacao($pedido->getCodSituacao());
            $retorno->setDistancia($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getValue());
            $retorno->setDistanciaStr($rota->getRoutes()[0]->getLegs()[0]->getDistance()->getText());
            $retorno->setTempo($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getValue());
            $retorno->setTempoStr($rota->getRoutes()[0]->getLegs()[0]->getDuration()->getText());
            $retorno->setPolyline($rota->getRoutes()[0]->getOverviewPolyline()->getPoints());
        }
        else {
            $retorno->setMensagem("Não foi possível calcular a rota.");
        }
        $this->alterar($pedido);
        return $retorno;
    }

    /**
     * @param PedidoInfo $pedido
     * @return LojaFreteInfo
     * @throws Exception
     */
    public function pegarFretePorEndereco(PedidoInfo $pedido) {
        $regraFrete = new LojaFreteBLL();
        return $regraFrete->pegarPorEndereco(
            $pedido->getIdLoja(),
            $pedido->getUf(),
            $pedido->getCidade(),
            $pedido->getBairro(),
            $pedido->getLogradouro()
        );
    }

    /**
     * @param PedidoInfo $pedido
     * @throws Exception
     */
    private function carregarValorFrete(PedidoInfo &$pedido) {
        $frete = $this->pegarFretePorEndereco($pedido);
        if ($frete->getEntrega() == false) {
            $endereco = array();
            if (!isNullOrEmpty($pedido->getLogradouro())) {
                $endereco[] = $pedido->getLogradouro();
            }
            if (!isNullOrEmpty($pedido->getBairro())) {
                $endereco[] = $pedido->getBairro();
            }
            if (!isNullOrEmpty($pedido->getCidade())) {
                $endereco[] = $pedido->getCidade();
            }
            if (!isNullOrEmpty($pedido->getUf())) {
                $endereco[] = $pedido->getUf();
            }
            $mensagem = "Não efetuamos entrega em %s.";
            throw new Exception(sprintf($mensagem, implode(", ", $endereco)));
        }
        $pedido->setValorFrete($frete->getValorFrete());
    }

    /**
     * @param int $id_loja
     * @return int
     * @throws Exception
     */
    public function pegarQuantidade($id_loja) {
        $dal = new PedidoDAL();
        return $dal->pegarQuantidade($id_loja);
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @return array<string,string>
     */
    public function listarMesAtividade($id_loja = 0) {
        $dal = new PedidoDAL();
        $retorno = array();
        foreach ($dal->listarMesAtividade($id_loja) as $data => $value) {
            $retorno[$data] = ucfirst(strftime("%B/%Y", strtotime($data)));
        }
        return $retorno;
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $dataInicio
     * @param string $dataFim
     * @param int $limite
     * @return ProdutoVendidoInfo[]
     */
    public function listarProdutoPorVenda($id_loja = 0, $dataInicio = null, $dataFim = null, $limite = 0) {
        $dal = new PedidoDAL();
        return $dal->listarProdutoPorVenda($id_loja, $dataInicio, $dataFim, $limite);
    }

    /**
     * @param int $ano
     * @param int $mes
     * @param int $id_loja
     * @param int $limite
     * @return ProdutoVendidoInfo[]
     * @throws Exception
     */
    public function listarProdutoVendaPorMes($ano, $mes, $id_loja, $limite) {
        $dataAtual = strtotime("$ano-$mes-01");
        $dataInicio = date("Y-m-01", $dataAtual);
        $dataFim = date("Y-m-t", $dataAtual);
        $dal = new PedidoDAL();
        return $dal->listarProdutoPorVenda($id_loja, $dataInicio, $dataFim, $limite);
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string|null $dataInicio
     * @param string|null $dataFim
     * @return PedidoEstatisticaInfo[]
     */
    public function listarVendaPorPeriodo($id_loja = 0, $dataInicio = null, $dataFim = null) {
        $dal = new PedidoDAL();
        $dados = $dal->listarVendaPorPeriodo($id_loja, $dataInicio, $dataFim);
        $dadoArray = array();
        foreach ($dados as $dado) {
            $dadoArray[$dado->getLegenda()] = $dado->getValor();
        }
        $retorno = array();
        $ini = strtotime($dataInicio);
        $fim = strtotime($dataFim);
        for ($i = $ini; $i <= $fim; $i = $i + (60 * 60 * 24)) {
            $data = date("Y-m-d", $i);
            $dia = date("d", $i);
            if (array_key_exists($data, $dadoArray)) {
                $retorno[] = (new PedidoEstatisticaInfo())
                    ->setLegenda($dia)
                    ->setValor($dadoArray[$data]);
            }
            else {
                $retorno[] = (new PedidoEstatisticaInfo())
                    ->setLegenda($dia)
                    ->setValor(0);
            }
        }
        return $retorno;
    }

    /**
     * @param int $ano
     * @param int $mes
     * @param int $id_loja
     * @return string
     * @throws Exception
     */
    public function gerarGraficoVendaMensal($ano, $mes, $id_loja = 0) {
        ob_start();
        try {

            $dataAtual = strtotime("$ano-$mes-01");
            $dataInicio = date("Y-m-01", $dataAtual);
            $dataFim = date("Y-m-t", $dataAtual);
            $mesAno = strftime("%B-%Y", $dataAtual);
            $dados = $this->listarVendaPorPeriodo($id_loja, $dataInicio, $dataFim);

            $graficoLinha = new GraficoLinhaBLL(900, 240);
            $graficoLinha->setTitulo("Vendas em " . $mesAno);
            $nomeArquivo = sprintf("grafico/venda-em-%s-%s.png", $mes, $ano);
            $graficoLinha->setNomeArquivo($nomeArquivo);
            foreach ($dados as $dado) {
                $graficoLinha->adicionarDado(new EstatisticaInfo($dado->getLegenda(), $dado->getValor()));
            }
            $graficoLinha->render();
        } catch (Exception $e) {
            $erro = $e->getMessage();
        }
        $content = ob_get_contents();
        ob_end_clean();
        if (isset($erro)) {
            throw new Exception($erro);
        }
        return $content;
    }

    /**
     * @param ProdutoVendidoInfo[] $produtos
     * @param string $titulo
     * @return string
     * @throws Exception
     */
    public function gerarGraficoProdutoMaisVendido($produtos, $titulo = "") {
        ob_start();
        try {
            $graficoPizza = (new GraficoPizzaBLL(900, 240))
                ->setLargura(900)
                ->setAltura(180)
                ->setLegendaY(20)
                ->setLegendaX(20)
                ->setPieX(450)
                ->setPieY(100)
                ->setTitulo($titulo)
                ->setDrawLabel(true)
                ->setLabelStacked(true)
                ->setShowLegenda(false)
                ->setNomeArquivo("grafico/mais-vendido.png");
            foreach ($produtos as $produto) {
                $legenda = $produto->getProduto();
                if (strlen($legenda) > 30) {
                    $legenda = substr($legenda, 0, 27) . "...";
                }
                $legenda .= sprintf(" (%s)", $produto->getQuantidade());
                $graficoPizza->adicionarDado(new EstatisticaInfo($legenda, $produto->getQuantidade()));
            }
            $graficoPizza->render();
        } catch (Exception $e) {
            $erro = $e->getMessage();
        }
        $content = ob_get_contents();
        ob_end_clean();
        if (isset($erro)) {
            throw new Exception($erro);
        }
        return $content;
    }

    /**
     * @param int $ano
     * @param int $mes
     * @param int $id_loja
     * @return string
     * @throws Exception
     */
    public function gerarGraficoProdutoMaisVendidoPorMes($ano, $mes, $id_loja = 0) {
        $dataAtual = strtotime("$ano-$mes-01");
        $dataInicio = date("Y-m-01", $dataAtual);
        $dataFim = date("Y-m-t", $dataAtual);
        $mesAno = strftime("%B-%Y", $dataAtual);
        $produtos = $this->listarProdutoPorVenda($id_loja, $dataInicio, $dataFim, 7);
        $titulo = "Mais vendidos em " . $mesAno;
        return $this->gerarGraficoProdutoMaisVendido($produtos, $titulo);
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @return double
     */
    public function pegarNota($id_loja) {
        $dal = new PedidoDAL();
        return $dal->pegarNota($id_loja);
    }
}

