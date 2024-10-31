using Emagine.Produto.Events;
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
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            var produtoPage = new ProdutoBuscaPage
            {
                Title = "Buscar produto",
                Filtro = new ProdutoFiltroInfo
                {
                    IdLoja = loja.Id,
                    Situacao = SituacaoEnum.Ativo,
                    //Destaque = false,
                }
            };
            return produtoPage;
        }

        public static ProdutoBasePage gerarProdutoListaDestaque() {
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();

            var produtoPage = ProdutoListaPageFactory.create();
            produtoPage.Title = "Em Promoção";
            produtoPage.Filtro = new ProdutoFiltroInfo
            {
                IdLoja = loja.Id,
                Situacao = SituacaoEnum.Ativo,
                Destaque = true,
            };
            if (loja.ControleEstoque) {
                produtoPage.Filtro.ApenasEstoque = true;
            }
            /*
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
            */
            return produtoPage;
        }

        public static ProdutoBasePage gerarProdutoListaPromocao() {
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();

            var produtoPage = ProdutoListaPageFactory.create();
            produtoPage.Title = "Em Promoção";
            produtoPage.Filtro = new ProdutoFiltroInfo {
                IdLoja = loja.Id,
                Situacao = SituacaoEnum.Ativo,
                ApenasEstoque = loja.ControleEstoque ? true : false,
                ApenasPromocao = true
            };
            /*
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
                produtoArgs.Produtos = await regraProduto.listarPorFiltro(filtro);
            };
            */
            return produtoPage;
        }

        public static ProdutoBasePage gerarProdutoListaPorCategoria(CategoriaInfo categoria)
        {
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();

            var produtoPage = ProdutoListaPageFactory.create();
            produtoPage.Title = categoria.Nome;
            produtoPage.Filtro = new ProdutoFiltroInfo {
                IdLoja = loja.Id,
                IdCategoria = categoria.Id,
                Situacao = SituacaoEnum.Ativo
            };
            if (loja.ControleEstoque)
            {
                produtoPage.Filtro.ApenasEstoque = true;
            }
            /*
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
            */
            return produtoPage;
        }
    }
}
