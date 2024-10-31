<?php
namespace Emagine\Pedido\Test;

use Exception;
use PHPUnit\Framework\TestCase;
use Emagine\Pagamento\Factory\PagamentoFactory;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Pedido\Model\PedidoItemInfo;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pagamento\Model\PagamentoItemInfo;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Pagamento\Test\PagamentoUtils;
use Emagine\Produto\Test\ProdutoUtils;
use Emagine\Login\Test\UsuarioUtils;

final class PedidoTest extends TestCase {

    /**
     * PedidoTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        set_time_limit(60);
    }

    /**
     * @throws Exception
     */
    public function testCriarNovoPedido() {
        $regraProduto = new ProdutoBLL();

        $usuario = UsuarioUtils::pegarAleatorio();

        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $produtos = $regraProduto->listar($loja->getId());
            $i++;
        } while ($i <= 50 && count($produtos) == 0);
        $this->assertGreaterThanOrEqual(0, count($produtos));

        shuffle($produtos);
        $produtos = array_slice($produtos, 0, rand(1,10));

        $pedido = PedidoUtils::gerarPedido($this, $loja, $usuario);
        $this->assertNotNull($pedido);

        foreach ($produtos as $produto) {
            $item = new PedidoItemInfo();
            $item->setIdProduto($produto->getId());
            $item->setQuantidade(rand(1,10));
            $pedido->adicionarItem($item);
        }

        $pedido->setCodEntrega(PedidoInfo::ENTREGAR);
        $pedido->setCodSituacao(PedidoInfo::PENDENTE);

        $pedido->setCodPagamento(PedidoInfo::DINHEIRO);
        $trocoPara = $pedido->getTotal();
        $trocoPara = ceil($trocoPara / 50.0) * 50.0;
        $pedido->setTrocoPara($trocoPara);

        $regraPedido = new PedidoBLL();
        $id_pedido = $regraPedido->inserir($pedido);
        $this->assertGreaterThanOrEqual(0, $id_pedido);

        $pedido = $regraPedido->pegar($id_pedido);
        $this->assertNotNull($pedido);
    }

    /**
     * @throws Exception
     */
    public function testAlterarPedidoExistente() {

        $pedido = null;

        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $pedido = PedidoUtils::pegarPedidoAleatorio($loja, PedidoInfo::PENDENTE);
            $i++;
        } while ($i <= 50 && is_null($pedido));
        if (!is_null($pedido)) {
            $id_pedido = $pedido->getId();

            $this->assertGreaterThanOrEqual(0, $id_pedido);
            $observacao = PedidoUtils::gerarPalavra(3);

            $pedido->setObservacao($observacao);
            $regraPedido = new PedidoBLL();
            $regraPedido->alterar($pedido);

            $pedido = $regraPedido->pegar($id_pedido);
            $this->assertNotNull($pedido);
            $this->assertEquals($observacao, $pedido->getObservacao());
        }
    }

    /**
     * @throws Exception
     */
    public function testIncluirPagamentoNoPedido() {
        $pedido = null;

        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $pedido = PedidoUtils::pegarPedidoAleatorio($loja, PedidoInfo::PENDENTE);
            $i++;
        } while ($i <= 50 && is_null($pedido));
        $this->assertNotNull($pedido);

        $id_pedido = $pedido->getId();

        $usuario = $pedido->getUsuario();

        $pagamento = PagamentoUtils::gerarPagamento($usuario);
        $pagamento->setCodTipo(PagamentoInfo::DINHEIRO);
        $pagamento->limparItem();
        foreach ($pedido->listarItens() as $item) {
            $produto = $item->getProduto();
            $valor = ($produto->getValorPromocao() > 0) ? $produto->getValorPromocao() : $produto->getValor();
            $pgtoItem = new PagamentoItemInfo();
            $pgtoItem->setDescricao($produto->getNome());
            $pgtoItem->setQuantidade($item->getQuantidade());
            $pgtoItem->setValor($valor);
            $pagamento->adicionarItem($pgtoItem);
        }

        $regraPagamento = PagamentoFactory::create();
        $id_pagamento = $regraPagamento->inserir($pagamento);
        $this->assertGreaterThanOrEqual(0, $id_pagamento);

        $pagamento = $regraPagamento->pegar($id_pagamento);
        $this->assertNotNull($pagamento);

        $pedido->setIdPagamento($id_pagamento);

        $regraPedido = new PedidoBLL();
        $regraPedido->alterar($pedido);

        $pedido = $regraPedido->pegar($id_pedido);
        $this->assertNotNull($pedido);
        $this->assertGreaterThanOrEqual(0, $pedido->getCodPagamento());
    }

    /**
     * @throws Exception
     */
    public function testEfetuarPagamentoDePedido() {
        $regraPedido = new PedidoBLL();
        $regraPagamento = PagamentoFactory::create();

        $pedido = null;

        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $pedidos = $regraPedido->listar($loja->getId(), PedidoInfo::PENDENTE);
            shuffle($pedidos);
            foreach ($pedidos as $p) {
                if ($p->getIdPagamento() > 0) {
                    $pedido = $p;
                    break;
                }
            }
            $i++;
        } while ($i <= 50 && is_null($pedido));
        if (!is_null($pedido)) {
            $id_pedido = $pedido->getId();

            $pagamento = $regraPagamento->pegar($pedido->getIdPagamento());
            $this->assertNotNull($pagamento);
            $this->assertEquals(PagamentoInfo::SITUACAO_ABERTO, $pagamento->getCodSituacao());

            $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_PAGO);

            $regraPagamento->alterar($pagamento);

            $pagamento = $regraPagamento->pegar($pedido->getIdPagamento());
            $this->assertEquals(PagamentoInfo::SITUACAO_PAGO, $pagamento->getCodSituacao());

            $regraPedido->alterarSituacao($id_pedido, PedidoInfo::PREPARANDO);

            $pedido = $regraPedido->pegar($id_pedido);
            $this->assertNotNull($pedido);
            $this->assertEquals(PedidoInfo::PREPARANDO, $pedido->getCodSituacao());
        }
    }

    /**
     * @throws Exception
     */
    public function testIniciandoEntregaDoPedido() {
        $regraPedido = new PedidoBLL();
        $pedido = null;

        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $pedidos = $regraPedido->listar($loja->getId(), PedidoInfo::PREPARANDO);
            shuffle($pedidos);
            foreach ($pedidos as $p) {
                if ($p->getIdPagamento() > 0) {
                    $pedido = $p;
                    break;
                }
            }
            $i++;
        } while ($i <= 50 && is_null($pedido));
        if (!is_null($pedido)) {
            $id_pedido = $pedido->getId();

            $regraPedido->alterarSituacao($id_pedido, PedidoInfo::ENTREGANDO);

            $pedido = $regraPedido->pegar($id_pedido);
            $this->assertNotNull($pedido);
            $this->assertEquals(PedidoInfo::ENTREGANDO, $pedido->getCodSituacao());
        }
    }

    /**
     * @throws Exception
     */
    public function testEntregandoOPedido() {
        $regraPedido = new PedidoBLL();
        $pedido = null;

        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $pedidos = $regraPedido->listar($loja->getId(), PedidoInfo::ENTREGANDO);
            shuffle($pedidos);
            foreach ($pedidos as $p) {
                if ($p->getIdPagamento() > 0) {
                    $pedido = $p;
                    break;
                }
            }
            $i++;
        } while ($i <= 50 && is_null($pedido));
        if (!is_null($pedido)) {
            $id_pedido = $pedido->getId();

            $regraPedido->alterarSituacao($id_pedido, PedidoInfo::ENTREGUE);

            $pedido = $regraPedido->pegar($id_pedido);
            $this->assertNotNull($pedido);
            $this->assertEquals(PedidoInfo::ENTREGUE, $pedido->getCodSituacao());
        }
    }

    /**
     * @throws Exception
     */
    public function testFinalizandoEntregaDoPedido() {
        $regraPedido = new PedidoBLL();
        $pedido = null;

        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $pedidos = $regraPedido->listar($loja->getId(), PedidoInfo::ENTREGUE);
            shuffle($pedidos);
            foreach ($pedidos as $p) {
                if ($p->getIdPagamento() > 0) {
                    $pedido = $p;
                    break;
                }
            }
            $i++;
        } while ($i <= 50 && is_null($pedido));
        if (!is_null($pedido)) {
            $id_pedido = $pedido->getId();

            $regraPedido->alterarSituacao($id_pedido, PedidoInfo::FINALIZADO);

            $pedido = $regraPedido->pegar($id_pedido);
            $this->assertNotNull($pedido);
            $this->assertEquals(PedidoInfo::FINALIZADO, $pedido->getCodSituacao());
        }
    }

}