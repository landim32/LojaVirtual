<?php
namespace Emagine\Pedido\Model;

class ProdutoVendidoInfo
{
    private $id_loja;
    private $loja_slug;
    private $loja;
    private $id_produto;
    private $produto_slug;
    private $produto;
    private $preco;
    private $vendas;
    private $quantidade;
    private $valor_total;

    /**
     * @return string
     */
    public function getIdLoja() {
        return $this->id_loja;
    }

    /**
     * @param string $value
     */
    public function setIdLoja($value) {
        $this->id_loja = $value;
    }

    /**
     * @return string
     */
    public function getLojaSlug() {
        return $this->loja_slug;
    }

    /**
     * @param string $value
     */
    public function setLojaSlug($value) {
        $this->loja_slug = $value;
    }

    /**
     * @return string
     */
    public function getLoja() {
        return $this->loja;
    }

    /**
     * @param string $value
     */
    public function setLoja($value) {
        $this->loja = $value;
    }

    /**
     * @return int
     */
    public function getIdProduto() {
        return $this->id_produto;
    }

    /**
     * @param int $value
     */
    public function setIdProduto($value) {
        $this->id_produto = $value;
    }

    /**
     * @return string
     */
    public function getProdutoSlug() {
        return $this->produto_slug;
    }

    /**
     * @param string $value
     */
    public function setProdutoSlug($value) {
        $this->produto_slug = $value;
    }

    /**
     * @return string
     */
    public function getProduto() {
        return $this->produto;
    }

    /**
     * @param string $value
     */
    public function setProduto($value) {
        $this->produto = $value;
    }

    /**
     * @return double
     */
    public function getPreco() {
        return $this->preco;
    }

    /**
     * @param double $value
     */
    public function setPreco($value) {
        $this->preco = $value;
    }

    /**
     * @return string
     */
    public function getPrecoStr() {
        return number_format($this->getPreco(), 2, ",", ".");
    }

    /**
     * @return int
     */
    public function getVendas() {
        return $this->vendas;
    }

    /**
     * @param int $value
     */
    public function setVendas($value) {
        $this->vendas = $value;
    }

    /**
     * @return int
     */
    public function getQuantidade() {
        return $this->quantidade;
    }

    /**
     * @param int $value
     */
    public function setQuantidade($value) {
        $this->quantidade = $value;
    }

    /**
     * @return double
     */
    public function getValorTotal() {
        return $this->valor_total;
    }

    /**
     * @param double $value
     */
    public function setValorTotal($value) {
        $this->valor_total = $value;
    }

    /**
     * @return string
     */
    public function getValorTotalStr() {
        return number_format($this->getValorTotal(), 2, ",", ".");
    }
}