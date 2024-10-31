<?php
namespace Emagine\Produto\Test;

use Exception;
use PHPUnit\Framework\TestCase;
use Emagine\Produto\BLL\CategoriaBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Login\Test\UsuarioUtils;

final class ProdutoTest extends TestCase {

    /**
     * ProdutoTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        set_time_limit(600);
    }

    /**
     * @throws Exception
     */
    public function testCriandoNovaLoja() {
        $loja = ProdutoUtils::gerarLoja($this);
        $regraLoja = new LojaBLL();
        $id_loja = $regraLoja->inserir($loja);
        $this->assertGreaterThanOrEqual(0, $id_loja);

        $novaLoja = $regraLoja->pegar($id_loja);
        $this->assertNotNull($novaLoja);
    }

    /**
     * @throws Exception
     */
    public function testAlterandoLoja() {
        $loja = ProdutoUtils::pegarLojaAleatoria();
        $id_loja = $loja->getId();

        $regraLoja = new LojaBLL();
        $loja = $regraLoja->pegar($id_loja);
        $this->assertNotNull($loja);

        $nome = "Teste - " . ProdutoUtils::gerarPalavra(3);
        $loja->setNome($nome);

        $regraLoja->alterar($loja);

        $loja = $regraLoja->pegar($id_loja);
        $this->assertNotNull($loja);
        $this->assertEquals($loja->getNome(), $nome);
    }

    /**
     * @throws Exception
     */
    public function testCriandoNovasCategorias() {
        $loja = ProdutoUtils::pegarLojaAleatoria();

        for ($i = 1; $i <= 3; $i++) {
            $categoria = ProdutoUtils::gerarCategoria($loja);

            $regraCategoria = new CategoriaBLL();
            $id_categoria = $regraCategoria->inserir($categoria);
            $this->assertGreaterThanOrEqual(0, $id_categoria);

            $novaCategoria = $regraCategoria->pegar($id_categoria);
            $this->assertNotNull($novaCategoria);
        }
    }

    /**
     * @throws Exception
     */
    public function testCriandoNovaSubCategoria() {
        $loja = ProdutoUtils::pegarLojaAleatoria();

        for ($i = 1; $i <= 2; $i++) {
            $categoriaPai = ProdutoUtils::gerarCategoria($loja);
            $categoria = ProdutoUtils::gerarCategoria($loja);
            $categoria->setIdPai($categoriaPai->getId());

            $regraCategoria = new CategoriaBLL();
            $id_categoria = $regraCategoria->inserir($categoria);
            $this->assertGreaterThanOrEqual(0, $id_categoria);

            $novaCategoria = $regraCategoria->pegar($id_categoria);
            $this->assertNotNull($novaCategoria);
        }
    }

    /**
     * @throws Exception
     */
    public function testAlterandoCategoria() {
        $categoria = null;
        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $categoria = ProdutoUtils::pegarCategoriaAleatoria($loja);
            $i++;
        } while ($i < 50 && is_null($categoria));

        $this->assertNotNull($categoria);
        $id_categoria = $categoria->getId();
        $this->assertGreaterThanOrEqual(0, $id_categoria);

        $regraCategoria = new CategoriaBLL();
        $categoria = $regraCategoria->pegar($id_categoria);
        $this->assertNotNull($categoria);

        $nome = ProdutoUtils::gerarPalavra(3);
        $nome .= "*";
        $categoria->setNome($nome);

        $regraCategoria->alterar($categoria);

        $categoria = $regraCategoria->pegar($id_categoria);
        $this->assertNotNull($categoria);
        $this->assertEquals($categoria->getNome(), $nome);
    }

    /**
     * @throws Exception
     */
    public function testCriandoNovoProduto() {
        $usuario = UsuarioUtils::pegarAleatorio();
        $this->assertNotNull($usuario);

        $loja = null;
        $categoria = null;
        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $categoria = ProdutoUtils::pegarCategoriaAleatoria($loja);
            $i++;
        } while ($i < 50 && is_null($categoria));
        $this->assertNotNull($categoria);

        for ($i = 1; $i <= 10; $i++) {

            $produto = ProdutoUtils::gerarProduto($loja, $categoria, $usuario);

            $regraProduto = new ProdutoBLL();
            $id_produto = $regraProduto->inserir($produto);
            $this->assertGreaterThanOrEqual(0, $id_produto);

            $novaProduto = $regraProduto->pegar($id_produto);
            $this->assertNotNull($novaProduto);
        }
    }

    /**
     * @throws Exception
     */
    public function testAlterandoProduto() {
        $produto = null;
        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $produto = ProdutoUtils::pegarProdutoAleatorio($loja);
            $i++;
        } while ($i < 50 && is_null($produto));
        $this->assertNotNull($produto);
        $id_produto = $produto->getId();

        $regraProduto = new ProdutoBLL();
        $produto = $regraProduto->pegar($id_produto);
        $this->assertNotNull($produto);

        $nome = ProdutoUtils::gerarPalavra(3);
        $nome .= "*";
        $produto->setNome($nome);

        $regraProduto->alterar($produto);

        $produto = $regraProduto->pegar($id_produto);
        $this->assertNotNull($produto);
        $this->assertEquals($produto->getNome(), $nome);
    }

    /**
     * @throws Exception
     */
    public function testExcluindoProduto() {
        $produto = null;
        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $produto = ProdutoUtils::pegarProdutoAleatorio($loja);
            $i++;
        } while ($i < 50 && is_null($produto));

        $this->assertNotNull($produto);
        $id_produto = $produto->getId();
        $this->assertGreaterThanOrEqual(0, $id_produto);

        $regraProduto = new ProdutoBLL();
        $regraProduto->excluir($id_produto);

        $produto = $regraProduto->pegar($id_produto);
        $this->assertNull($produto);
    }

    /**
     * @throws Exception
     */
    public function testExcluindoCategoria() {
        $regraProduto = new ProdutoBLL();
        $regraCategoria = new CategoriaBLL();

        $categoria = null;
        $i = 0;
        do {
            $loja = ProdutoUtils::pegarLojaAleatoria();
            $categoria = ProdutoUtils::pegarCategoriaAleatoria($loja);
            $i++;
        } while ($i < 50 && is_null($categoria));

        $this->assertNotNull($categoria);
        $id_categoria = $categoria->getId();
        $this->assertGreaterThanOrEqual(0, $id_categoria);

        $quantidadeCategoria = $regraCategoria->pegarQuantidadeFilho($id_categoria);
        $quantidadeProduto = $regraProduto->pegarQuantidadePorCategoria($id_categoria);

        if ($quantidadeCategoria > 0 || $quantidadeProduto > 0) {
            $this->expectException("Emagine\\Produto\\Ex\\CategoriaException");
        }
        $regraCategoria->excluir($id_categoria);

        $categoria = $regraCategoria->pegar($id_categoria);
        $this->assertNull($categoria);
    }

    /**
     * @throws Exception
     */
    public function testExcluindoLoja() {
        $regraLoja = new LojaBLL();
        $regraProduto = new ProdutoBLL();
        $regraCategoria = new CategoriaBLL();


        $loja = ProdutoUtils::pegarLojaAleatoria();
        $this->assertNotNull($loja);
        $id_loja = $loja->getId();
        $this->assertGreaterThanOrEqual(0, $id_loja);

        $quantidadeCategoria = $regraCategoria->pegarQuantidadePorLoja($id_loja);
        $quantidadeProduto = $regraProduto->pegarQuantidadePorLoja($id_loja);

        if ($quantidadeCategoria > 0 || $quantidadeProduto > 0) {
            $this->expectException("Emagine\\Produto\\Ex\\LojaException");
        }

        $regraLoja->excluir($id_loja);

        $loja = $regraLoja->pegar($id_loja);
        $this->assertNull($loja);
    }

}