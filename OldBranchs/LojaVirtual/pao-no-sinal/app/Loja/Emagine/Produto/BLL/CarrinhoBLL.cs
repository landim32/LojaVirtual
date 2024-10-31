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

        public int adicionar(ProdutoInfo produto) {
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            if (!_produtos.ContainsKey(produto.Id)) {
                _produtos.Add(produto.Id, produto);
            }
            if (loja.ControleEstoque)
            {
                if (produto.QuantidadeCarrinho < produto.Quantidade)
                {
                    produto.QuantidadeCarrinho++;
                }
            }
            else {
                produto.QuantidadeCarrinho++;
            }
            AoAtualizar?.Invoke(this, new CarrinhoEventArgs( getQuantidade(), getTotal()));
            return produto.QuantidadeCarrinho;
        }

        public int remover(ProdutoInfo produto) {
            if (_produtos.ContainsKey(produto.Id))
            {
                produto.QuantidadeCarrinho--;
                if (produto.QuantidadeCarrinho <= 0) {
                    _produtos.Remove(produto.Id);
                }
            }
            AoAtualizar?.Invoke(this, new CarrinhoEventArgs(getQuantidade(), getTotal()));
            return produto.QuantidadeCarrinho;
        }

        public void limpar() {
            _produtos = new Dictionary<int, ProdutoInfo>();
            AoAtualizar?.Invoke(this, new CarrinhoEventArgs(getQuantidade(), getTotal()));
        }
    }
}
