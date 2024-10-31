<?php
namespace Emagine\Produto\Test;

use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\BLL\CategoriaBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Produto\Model\CategoriaInfo;
use Emagine\Produto\Model\ProdutoInfo;
use Exception;
use joshtronic\LoremIpsum;
use PHPUnit\Framework\TestCase;
use Emagine\Base\BLL\ValidaCpfCnpj;
use Emagine\Base\Test\TesteUtils;
use Emagine\Endereco\Test\EnderecoUtils;
use Emagine\Produto\Model\LojaInfo;

class ProdutoUtils
{
    /**
     * @param int $quantidade
     * @return string
     */
    public static function gerarPalavra($quantidade) {
        $lipsum = new LoremIpsum();
        $palavras = $lipsum->words(30, false, true);
        shuffle($palavras);
        $palavras = array_slice($palavras, 0, $quantidade);
        return implode(" ", $palavras);
    }

    /**
     * @param TestCase $testCase
     * @return LojaInfo
     * @throws Exception
     */
    public static function gerarLoja(TestCase $testCase) {
        $lipsum = new LoremIpsum();

        $uf = EnderecoUtils::pegarUf($testCase);
        //$cidade = EnderecoUtils::pegarCidade($testCase, $uf->getUf());
        $endereco = EnderecoUtils::pegarEnderecoAleatorio($uf->getUf());
        $testCase->assertNotNull($endereco);

        $validaCPF = new ValidaCpfCnpj();

        $loja = new LojaInfo();
        $loja->setNome("Teste - " . ProdutoUtils::gerarPalavra(5));
        $loja->setEmail(TesteUtils::gerarEmail($loja->getNome()));
        $loja->setCep($endereco->getCep());
        $loja->setUf($endereco->getUf());
        $loja->setCidade($endereco->getCidade());
        $loja->setBairro($endereco->getBairro());
        $loja->setLogradouro($endereco->getLogradouro());
        $loja->setComplemento($endereco->getComplemento());
        $loja->setNumero(TesteUtils::gerarNumeroAleatorio(2));
        $loja->setLatitude($endereco->getLatitude());
        $loja->setLongitude($endereco->getLongitude());
        $loja->setCnpj($validaCPF->cnpjAleatorio(0));
        $loja->setDescricao($lipsum->paragraphs(1));
        $loja->setCodSituacao(LojaInfo::ATIVO);
        $loja->setUsaEntregar(rand(0, 1));
        $loja->setUsaRetirar(rand(0, 1));
        $loja->setUsaRetiradaMapeada(rand(0, 1));
        $loja->setUsaGateway(rand(0, 1));
        $loja->setAceitaCartaoOffline(rand(0, 1));
        $loja->setAceitaDinheiro(rand(0, 1));
        $loja->setAceitaBoleto(rand(0, 1));
        $loja->setAceitaDebitoOnline(rand(0, 1));
        $loja->setAceitaCreditoOnline(rand(0, 1));
        return $loja;
    }

    /**
     * @param LojaInfo $loja
     * @return CategoriaInfo
     */
    public static function gerarCategoria(LojaInfo $loja) {
        $categoria = new CategoriaInfo();
        $categoria->setIdLoja($loja->getId());
        $categoria->setNome(self::gerarPalavra(3));
        $categoria->setNomeCompleto(self::gerarPalavra(5));
        return $categoria;
    }

    /**
     * @param LojaInfo $loja
     * @param CategoriaInfo $categoria
     * @param UsuarioInfo $usuario
     * @return ProdutoInfo
     */
    public static function gerarProduto(LojaInfo $loja, CategoriaInfo $categoria, UsuarioInfo $usuario) {
        $lipsum = new LoremIpsum();

        $regraProduto = new ProdutoBLL();

        $unidades = $regraProduto->listarUnidade();
        $unidadeKeys = array_keys($unidades);
        shuffle($unidadeKeys);
        $unidade = array_values($unidadeKeys)[0];

        $produto = new ProdutoInfo();
        $produto->setIdLoja($loja->getId());
        $produto->setIdUsuario($usuario->getId());
        $produto->setIdCategoria($categoria->getId());
        $produto->setNome(self::gerarPalavra(4));
        $produto->setDescricao($lipsum->paragraphs(rand(1,3)));
        $produto->setValor((rand(200, 10000) / 2));
        if (rand(0, 1) == 1) {
            $produto->setValorPromocao((rand(1, ($produto->getValor() * 100)) / 2));
        }
        $produto->setUnidade($unidade);
        $produto->setVolume((rand(100, 1000) / 2));
        $produto->setCodigo(TesteUtils::gerarNumeroAleatorio(10));
        $produto->setDestaque(rand(0, 1));
        $produto->setQuantidade(rand(10, 30));
        $produto->setCodSituacao(ProdutoInfo::ATIVO);
        return $produto;
    }

    /**
     * @throws Exception
     * @return LojaInfo
     */
    public static function pegarLojaAleatoria() {
        $regraLoja = new LojaBLL();
        $lojas = $regraLoja->buscarPorPalavra("Teste");
        shuffle($lojas);
        /** @var LojaInfo $loja */
        $loja = array_values($lojas)[0];
        return $loja;
    }

    /**
     * @param LojaInfo $loja
     * @return CategoriaInfo
     */
    public static function pegarCategoriaAleatoria(LojaInfo $loja) {
        $regraCategoria = new CategoriaBLL();
        $categorias = $regraCategoria->listar($loja->getId());
        shuffle($categorias);
        /** @var CategoriaInfo $categoria */
        $categoria = array_values($categorias)[0];
        return $categoria;
    }

    /**
     * @param LojaInfo $loja
     * @return ProdutoInfo
     * @throws Exception
     */
    public static function pegarProdutoAleatorio(LojaInfo $loja) {
        $regraProduto = new ProdutoBLL();
        $produtos = $regraProduto->listar($loja->getId());
        shuffle($produtos);
        /** @var ProdutoInfo $produto */
        $produto = array_values($produtos)[0];
        return $produto;
    }
}