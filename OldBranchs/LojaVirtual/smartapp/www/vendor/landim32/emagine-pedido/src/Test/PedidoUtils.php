<?php
namespace Emagine\Pedido\Test;

use Emagine\Base\Test\TesteUtils;
use Emagine\Pedido\BLL\PedidoBLL;
use Exception;
use joshtronic\LoremIpsum;
use PHPUnit\Framework\TestCase;
use Emagine\Login\Model\UsuarioEnderecoInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Endereco\Test\EnderecoUtils;

class PedidoUtils
{

    /**
     * @param $quantidade
     * @return string
     */
    public static function gerarPalavra($quantidade) {
        $lipsum = new LoremIpsum();
        $palavras = $lipsum->words(30, false, true);
        shuffle($palavras);
        $palavras = array_slice($palavras, 0, $quantidade);
        return implode("," , $palavras);
    }

    /**
     * @throws Exception
     * @param TestCase $testCase
     * @param LojaInfo $loja
     * @param UsuarioInfo $usuario
     * @return PedidoInfo
     */
    public static function gerarPedido(TestCase $testCase, LojaInfo $loja, UsuarioInfo $usuario) {
        $lipsum = new LoremIpsum();

        $enderecos = $usuario->listarEndereco();
        shuffle($enderecos);
        /** @var UsuarioEnderecoInfo $endereco */
        $endereco = array_values($enderecos)[0];

        if (is_null($endereco)) {
            $uf = EnderecoUtils::pegarUf($testCase);
            //$cidade = EnderecoUtils::pegarCidade($testCase, $uf->getUf());
            $endereco = EnderecoUtils::pegarEnderecoAleatorio($uf->getUf());
            $endereco->setNumero(TesteUtils::gerarNumeroAleatorio(3));
        }

        $pedido = new PedidoInfo();
        $pedido->setIdUsuario($usuario->getId());
        $pedido->setIdLoja($loja->getId());
        $pedido->setCodPagamento(PedidoInfo::DINHEIRO);
        $pedido->setCodEntrega(PedidoInfo::ENTREGAR);
        $pedido->setCodSituacao(PedidoInfo::PENDENTE);
        $pedido->setCep($endereco->getCep());
        $pedido->setLogradouro($endereco->getLogradouro());
        $pedido->setComplemento($endereco->getComplemento());
        $pedido->setNumero($endereco->getNumero());
        $pedido->setBairro($endereco->getBairro());
        $pedido->setCidade($endereco->getCidade());
        $pedido->setUf($endereco->getUf());
        $pedido->setLatitude($endereco->getLatitude());
        $pedido->setLongitude($endereco->getLongitude());
        $pedido->setObservacao($lipsum->paragraphs(2));

        return $pedido;
    }

    /**
     * @param LojaInfo $loja
     * @param int $cod_situacao
     * @return PedidoInfo
     * @throws Exception
     */
    public static function pegarPedidoAleatorio(LojaInfo $loja, $cod_situacao = 0) {
        $regraPedido = new PedidoBLL();
        $pedidos = $regraPedido->listar($loja->getId(), $cod_situacao);
        shuffle($pedidos);

        /** @var PedidoInfo $pedido */
        $pedido = array_values($pedidos)[0];
        return $pedido;
    }
}