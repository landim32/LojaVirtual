<?php
namespace Emagine\Pagamento\Test;

use Exception;
use joshtronic\LoremIpsum;
use PHPUnit\Framework\TestCase;
use Emagine\Base\Test\TesteUtils;
use Emagine\Login\Test\UsuarioUtils;
use Emagine\Pagamento\BLL\PagamentoBLL;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Pagamento\Factory\CartaoFactory;
use Emagine\Pagamento\Factory\PagamentoFactory;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Pagamento\Model\CartaoInfo;

class PagamentoTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testEfetuandoPagamentoPorBoleto() {
        $lipsum = new LoremIpsum();

        $regraPagamento = PagamentoFactory::create();

        $usuario = UsuarioUtils::pegarAleatorio();
        $this->assertNotNull($usuario);

        $pagamento = PagamentoUtils::gerarPagamento($usuario);
        //$pagamento->setCep(TesteUtils::gerarNumeroAleatorio(8));
        $pagamento->setCep("70866120");
        $pagamento->setLogradouro($lipsum->words(4));
        $pagamento->setComplemento($lipsum->words(2));
        $pagamento->setNumero(TesteUtils::gerarNumeroAleatorio(3));
        $pagamento->setBairro($lipsum->words(3));
        $pagamento->setCidade($lipsum->words(3));
        $pagamento->setUf(strtoupper(substr($lipsum->words(1), 0, 2)));
        $pagamento->setCodTipo(PagamentoInfo::BOLETO);

        $id_pagamento = $regraPagamento->inserir($pagamento);
        $pagamento->setId($id_pagamento);
        $this->assertGreaterThanOrEqual(0, $id_pagamento);

        $regraPagamento->pagar($pagamento);

        $pagamento = $regraPagamento->pegar($id_pagamento);

        $this->assertNotNull($pagamento, "Pagamento não cadastrado.");
        $this->assertNotNull($pagamento->getBoletoUrl(), "Url do boleto não pode ser nula.");
        $this->assertNotEmpty($pagamento->getBoletoLinhaDigitavel(), "Linha gisitavel não pode ser nula.");
        $this->assertEquals(PagamentoInfo::SITUACAO_AGUARDANDO_PAGAMENTO, $pagamento->getCodSituacao());
    }

    /**
     * @throws Exception
     */
    public function testEfetuandoPagamentoCartaoDeCredito() {

        $regraPagamento = PagamentoFactory::create();
        switch (PagamentoBLL::getTipoPagamento()) {
            case PagamentoBLL::MUNDI_PAGG:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this, 1000);
                break;
            case PagamentoBLL::IUGU:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this);
                $pagamento->setNumeroCartao("4111111111111111");
                break;
            default:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this);
                $pagamento->setNumeroCartao("0000000000000001");
                break;
        }

        $id_pagamento = $regraPagamento->inserir($pagamento);
        $pagamento->setId($id_pagamento);
        $this->assertGreaterThanOrEqual(0, $id_pagamento);

        $regraPagamento->pagar($pagamento);

        $pagamento = $regraPagamento->pegar($id_pagamento);
        $this->assertNotEmpty($pagamento->getMensagem());
        $this->assertEquals(PagamentoInfo::SITUACAO_PAGO, $pagamento->getCodSituacao());
    }

    /**
     * @throws Exception
     */
    public function testTimeoutAoEfetuarPagamentoCartaoDeCredito() {

        $regraPagamento = PagamentoFactory::create();
        switch (PagamentoBLL::getTipoPagamento()) {
            case PagamentoBLL::MUNDI_PAGG:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this, 1051.70);
                break;
            case PagamentoBLL::IUGU:
                $this->expectException("\\Emagine\\Pagamento\\Exceptions\\PagamentoException");
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this);
                $pagamento->setNumeroCartao("4012888888881881");
                break;
            case PagamentoBLL::CIELO:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this);
                $pagamento->setNumeroCartao("0000000000000006");
                break;
            default:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this);
                break;
        }

        $id_pagamento = $regraPagamento->inserir($pagamento);
        $pagamento->setId($id_pagamento);
        $this->assertGreaterThanOrEqual(0, $id_pagamento);

        $regraPagamento->pagar($pagamento);

        $pagamento = $regraPagamento->pegar($id_pagamento);
        $this->assertNotEmpty($pagamento->getMensagem());
        $this->assertEquals(PagamentoInfo::SITUACAO_ABERTO, $pagamento->getCodSituacao());
    }

    /**
     * @throws Exception
     */
    public function testPagamentoComCartaoDeCreditoNegadoPorFaltaDeCredito() {

        $regraPagamento = PagamentoFactory::create();
        switch (PagamentoBLL::getTipoPagamento()) {
            case PagamentoBLL::MUNDI_PAGG:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this, 1250.0);
                break;
            case PagamentoBLL::IUGU:
                $this->expectException("\\Emagine\\Pagamento\\Exceptions\\PagamentoException");
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this);
                $pagamento->setNumeroCartao("371449635398431");
                $pagamento->setCVV(TesteUtils::gerarNumeroAleatorio(4));
                break;
            case PagamentoBLL::CIELO:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this);
                $pagamento->setNumeroCartao("0000000000000008");
                break;
            default:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this);
                break;
        }

        $id_pagamento = $regraPagamento->inserir($pagamento);
        $pagamento->setId($id_pagamento);
        $this->assertGreaterThanOrEqual(0, $id_pagamento);

        $regraPagamento->pagar($pagamento);

        $pagamento = $regraPagamento->pegar($id_pagamento);
        $this->assertNotEmpty($pagamento->getMensagem());
        $this->assertEquals(PagamentoInfo::SITUACAO_ABERTO, $pagamento->getCodSituacao());
    }

    /**
     * @throws Exception
     */
    public function testPagamentoComCartaoDeCreditoNaoAutorizado() {

        //$this->expectException("\\Emagine\\Pagamento\\Exceptions\\PagamentoException");

        $regraPagamento = PagamentoFactory::create();
        switch (PagamentoBLL::getTipoPagamento()) {
            case PagamentoBLL::MUNDI_PAGG:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this, 1300.0);
                break;
            case PagamentoBLL::IUGU:
                $this->expectException("\\Emagine\\Pagamento\\Exceptions\\PagamentoException");
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this);
                $pagamento->setNumeroCartao("38520000023237");
                break;
            case PagamentoBLL::CIELO:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this);
                $pagamento->setNumeroCartao("0000000000000002");
                break;
            default:
                $pagamento = PagamentoUtils::gerarPagamentoCredito($this);
                break;
        }

        $id_pagamento = $regraPagamento->inserir($pagamento);
        $pagamento->setId($id_pagamento);
        $this->assertGreaterThanOrEqual(0, $id_pagamento);

        $regraPagamento->pagar($pagamento);

        $pagamento = $regraPagamento->pegar($id_pagamento);
        $this->assertNotEmpty($pagamento->getMensagem());
        $this->assertEquals(PagamentoInfo::SITUACAO_ABERTO, $pagamento->getCodSituacao());
    }

    /**
     * @throws Exception
     */
    public function testListandoCartoesDeCredito() {
        $regraUsuario = new UsuarioBLL();
        $usuarios = $regraUsuario->listar(UsuarioInfo::ATIVO);

        $encontrado = false;
        foreach ($usuarios as $usuario) {
            $this->assertNotNull($usuario);

            $regraCartao = CartaoFactory::create();
            $cartoes = $regraCartao->listar($usuario->getId());

            $this->assertGreaterThanOrEqual(0, count($cartoes));
            foreach ($cartoes as $cartao) {
                $encontrado = true;
                $this->assertInstanceOf(CartaoInfo::class, $cartao);
            }
        }
        $this->assertTrue($encontrado, "Nenhum cartão encontrado.");
    }

    /**
     * @throws Exception
     */
    public function testEfetuandoPagamentoCartaoViaToken() {

        $regraUsuario = new UsuarioBLL();
        $usuarios = $regraUsuario->listar(UsuarioInfo::ATIVO);
        shuffle($usuarios);

        $usuarioAtual = null;
        $cartao = null;
        foreach ($usuarios as $usuario) {
            $this->assertNotNull($usuario);

            $regraCartao = CartaoFactory::create();
            $cartoes = $regraCartao->listar($usuario->getId());

            if (count($cartoes) > 0) {
                shuffle($cartoes);
                /** @var CartaoInfo $cartao */
                $cartao = array_values($cartoes)[0];
                $this->assertInstanceOf(CartaoInfo::class, $cartao);
                $usuarioAtual = $usuario;
                break;
            }
        }

        $this->assertNotNull($cartao);

        $pagamento = PagamentoUtils::gerarPagamento($usuarioAtual, 1051.70);
        $pagamento->setCodBandeira($cartao->getBandeira());
        $pagamento->setIdUsuario($usuarioAtual->getId());
        $pagamento->setCodTipo(PagamentoInfo::CREDITO_ONLINE);
        $pagamento->setNomeCartao($usuarioAtual->getNome());
        $pagamento->setDataExpiracao("2020-01-01");
        $pagamento->setToken($cartao->getToken());
        $pagamento->setCVV($cartao->getCVV());

        $regraPagamento = PagamentoFactory::create();
        $id_pagamento = $regraPagamento->inserir($pagamento);
        $pagamento->setId($id_pagamento);
        $this->assertGreaterThanOrEqual(0, $id_pagamento);

        $regraPagamento->pagarComToken($pagamento);

        $pagamento = $regraPagamento->pegar($id_pagamento);
        $this->assertNotEmpty($pagamento->getMensagem());
        $this->assertEquals(PagamentoInfo::SITUACAO_PAGO, $pagamento->getCodSituacao());
    }

    /**
     * @throws Exception
     */
    public function testExcluindoCartaoDeCredito() {
        $regraUsuario = new UsuarioBLL();
        $usuarios = $regraUsuario->listar(UsuarioInfo::ATIVO);
        shuffle($usuarios);

        $encontrado = false;
        foreach ($usuarios as $usuario) {
            $this->assertNotNull($usuario);

            $regraCartao = CartaoFactory::create();
            $cartoes = $regraCartao->listar($usuario->getId());
            if (count($cartoes) > 0) {
                $encontrado = true;
                shuffle($cartoes);
                /** @var CartaoInfo $cartao */
                $cartao = array_values($cartoes)[0];
                $regraCartao->excluir($cartao->getId());
                $cartaoExcluido = $regraCartao->pegar($cartao->getId());
                $this->assertNull($cartaoExcluido, "Cartão não foi excluído.");
                break;
            }
        }
        $this->assertTrue($encontrado, "Nenhum cartão encontrado.");
    }
}