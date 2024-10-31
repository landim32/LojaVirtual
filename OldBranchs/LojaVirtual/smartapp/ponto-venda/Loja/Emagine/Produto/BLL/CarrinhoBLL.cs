using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.BLL
{
    public class CarrinhoBLL
    {
        private Dictionary<int, ProdutoInfo> _produtos;

        public LojaInfo Loja { get; set; }
        public event CarrinhoEventHandler AoAtualizar;

        public CarrinhoBLL() {
            _produtos = new Dictionary<int, ProdutoInfo>();
        }

        public int getQuantidade() {
            int quantidade = 0;
            foreach (var produto in _produtos) {
                quantidade += produto.Value.QuantidadeCarrinho;
            }
            return quantidade;
        }

        public double getTotal() {
            double total = 0;
            foreach (var produto in _produtos) {
                if (produto.Value.ValorPromocao > 0)
                {
                    total += produto.Value.ValorPromocao * produto.Value.QuantidadeCarrinho;
                }
                else {
                    total += produto.Value.Valor * produto.Value.QuantidadeCarrinho;
                }
            }
            return total;
        }

        public IList<ProdutoInfo> listar() {
            return (
                from produto in _produtos
                select produto.Value
            ).ToList();
        }

        public ProdutoInfo pegar(int idProduto) {
            if (_produtos.ContainsKey(idProduto)) {
                return _produtos[idProduto];
            }
            return null;
        }

        public bool temProdutoDeOutraLoja(int idLoja) {
            return ((from p in _produtos where (p.Value.IdLoja != idLoja) select p).Count() > 0);
        }

        public int adicionar(ProdutoInfo produto) {
            if (temProdutoDeOutraLoja(produto.IdLoja)) {
                throw new Exception("Já existem produtos no carrinho de outra loja.");
            }
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            /*
            if (!_produtos.ContainsKey(produto.Id)) {
                _produtos.Add(produto.Id, produto);
            }
            */
            ProdutoInfo produtoAtual = null;
            if (_produtos.ContainsKey(produto.Id)) {
                produtoAtual = _produtos[produto.Id];
            }
            else {
                produtoAtual = produto;
                _produtos.Add(produtoAtual.Id, produtoAtual);
            }
            if (loja.ControleEstoque) {
                if (produtoAtual.QuantidadeCarrinho < produtoAtual.Quantidade) {
                    produtoAtual.QuantidadeCarrinho++;
                }
            }
            else {
                produtoAtual.QuantidadeCarrinho++;
            }
            this.Loja = loja;
            AoAtualizar?.Invoke(this, new CarrinhoEventArgs( getQuantidade(), getTotal()));
            return produtoAtual.QuantidadeCarrinho;
        }

        /*
        public int remover(ProdutoInfo produto) {
            if (_produtos.ContainsKey(produto.Id))
            {
                produto.QuantidadeCarrinho--;
                if (produto.QuantidadeCarrinho <= 0) {
                    _produtos.Remove(produto.Id);
                }
            }
            if (_produtos.Count == 0) {
                this.Loja = null;
            }
            AoAtualizar?.Invoke(this, new CarrinhoEventArgs(getQuantidade(), getTotal()));
            return produto.QuantidadeCarrinho;
        }
        */
        public int remover(int idProduto)
        {
            if (_produtos.ContainsKey(idProduto))
            {
                var produto = _produtos[idProduto];
                produto.QuantidadeCarrinho--;
                if (produto.QuantidadeCarrinho <= 0)
                {
                    _produtos.Remove(produto.Id);
                }
                if (_produtos.Count == 0)
                {
                    this.Loja = null;
                }
                AoAtualizar?.Invoke(this, new CarrinhoEventArgs(getQuantidade(), getTotal()));
                return produto.QuantidadeCarrinho;
            }
            return 0;
        }

        public int excluir(ProdutoInfo produto)
        {
            if (_produtos.ContainsKey(produto.Id))
            {
                produto.QuantidadeCarrinho = 0;
                _produtos.Remove(produto.Id);
            }
            if (_produtos.Count == 0)
            {
                this.Loja = null;
            }
            AoAtualizar?.Invoke(this, new CarrinhoEventArgs(getQuantidade(), getTotal()));
            return 0;
        }

        public void limpar() {
            _produtos = new Dictionary<int, ProdutoInfo>();
            AoAtualizar?.Invoke(this, new CarrinhoEventArgs(getQuantidade(), getTotal()));
        }
    }
}
