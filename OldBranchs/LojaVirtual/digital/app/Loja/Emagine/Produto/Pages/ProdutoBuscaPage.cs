using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class ProdutoBuscaPage : ProdutoBasePage
    {
        private SearchBar _palavraChaveSearchBar;

        public ProdutoBuscaPage()
        {
            Content = new StackLayout
            {
                //Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _palavraChaveSearchBar,
                    _ProdutoListView,
                    _totalView
                }
            };
        }

        protected override void inicializarComponente() {
            base.inicializarComponente();
            _palavraChaveSearchBar = new SearchBar
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Placeholder = "Buscar produto...",
                SearchCommand = new Command(() => {
                    executarBusca(_palavraChaveSearchBar.Text);
                })
            };
        }

        private async void executarBusca(string palavraChave)
        {
            try {
                UserDialogs.Instance.ShowLoading("Buscando...");
                var regraProduto = ProdutoFactory.create();
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();
                _ProdutoListView.ItemsSource = await regraProduto.buscar(loja.Id, palavraChave);
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }
    }
}