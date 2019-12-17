using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Utils
{
    public static class ProdutoUtils
    {
        public static Type ListaItemTemplate { get; set; } = null;
        public static Type CarrinhoItemTemplate { get; set; } = null;
        public static bool? ListaAbreJanela { get; set; } = null;

        public static ProdutoBuscaPage gerarProdutoBusca()
        {
            var produtoPage = new ProdutoBuscaPage
            {
                Title = "Buscar produto"
            };
            return produtoPage;
        }

        public static ProdutoListaPage gerarProdutoListaDestaque() {
            var produtoPage = new ProdutoListaPage
            {
                Title = "Em Destaque"
            };
            produtoPage.AoCarregar += async (object sender, ProdutoListaEventArgs produtoArgs) =>
            {
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();
                var regraProduto = ProdutoFactory.create();
                var filtro = new ProdutoFiltroInfo
                {
                    IdLoja = loja.Id,
                    Situacao = SituacaoEnum.Ativo,
                    Destaque = true,
                };
                if (loja.ControleEstoque) {
                    filtro.ApenasEstoque = true;
                }
                produtoArgs.Produtos = await regraProduto.listarPorFiltro(filtro);
            };
            return produtoPage;
        }

        public static ProdutoListaPage gerarProdutoListaPromocao()
        {
            var produtoPage = new ProdutoListaPage
            {
                Title = "Em Promoção"
            };
            produtoPage.AoCarregar += async (object sender, ProdutoListaEventArgs produtoArgs) =>
            {
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();
                var regraProduto = ProdutoFactory.create();
                var filtro = new ProdutoFiltroInfo
                {
                    IdLoja = loja.Id,
                    Situacao = SituacaoEnum.Ativo,
                    ApenasEstoque = loja.ControleEstoque ? true : false,
                    ApenasPromocao = true
                };
                /*
                if (loja.ControleEstoque)
                {
                    filtro.ApenasEstoque = true;
                }
                */
                produtoArgs.Produtos = await regraProduto.listarPorFiltro(filtro);
            };
            return produtoPage;
        }

        public static ProdutoListaPage gerarProdutoListaPorCategoria(CategoriaInfo categoria)
        {
            var produtoPage = new ProdutoListaPage
            {
                Title = categoria.Nome
            };
            produtoPage.AoCarregar += async (object sender, ProdutoListaEventArgs produtoArgs) =>
            {
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();
                var filtro = new ProdutoFiltroInfo
                {
                    IdLoja = loja.Id,
                    IdCategoria = categoria.Id,
                    Situacao = SituacaoEnum.Ativo
                };
                if (loja.ControleEstoque)
                {
                    filtro.ApenasEstoque = true;
                }
                var regraProduto = ProdutoFactory.create();
                produtoArgs.Produtos = await regraProduto.listarPorFiltro(filtro);
            };
            return produtoPage;
        }
    }
}
