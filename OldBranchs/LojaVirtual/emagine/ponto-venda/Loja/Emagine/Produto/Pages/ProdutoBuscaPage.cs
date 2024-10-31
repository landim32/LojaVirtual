using Acr.UserDialogs;
using Emagine.Banner.Factory;
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
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class ProdutoBuscaPage : ProdutoGridPage
    {
        private SearchBar _palavraChaveSearchBar;

        public ProdutoBuscaPage()
        {
            _mainLayout = new StackLayout
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _palavraChaveSearchBar,
                    _produtoListView,
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Fill,
                        Margin = new Thickness(5, 0),
                        Children = {
                            _totalView,
                            _carrinhoButton
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Vertical,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Fill,
                        Margin = new Thickness(5, 0),
                        Spacing = 0,
                        Children = {
                            new Label {
                                VerticalOptions = LayoutOptions.Start,
                                HorizontalOptions = LayoutOptions.Fill,
                                HorizontalTextAlignment = TextAlignment.Center,
                                Text = "Você está comprando em:",
                                FontSize = 10
                            },
                            _empresaLabel
                        }
                    }
                }
            };
            _totalView.QuantidadeTitulo = "Qtde:";
            Content = _mainLayout;
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

        protected override void adicionarAvisoVazio()
        {
            _mainLayout.Children.Insert(1, _vazioFrame);
        }

        protected override Task carregarProduto() {
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            if (loja != null) {
                _empresaLabel.Text = loja.Nome;
            }
            return (new TaskFactory()).StartNew(() => { });
        }

        private async void executarBusca(string palavraChave)
        {
            try {
                UserDialogs.Instance.ShowLoading("Buscando...");
                var regraProduto = ProdutoFactory.create();
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();

                Filtro.PalavraChave = palavraChave;
                Items = criarListaInfinita();
                _produtoListView.ItemsSource = null;
                _produtoListView.ItemsSource = Items;
                _buscando = true;
                await Items.LoadMoreAsync();

                //_produtoListView.ItemsSource = await regraProduto.buscar(loja.Id, palavraChave);
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                //UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                await DisplayAlert("Erro", erro.Message, "Entendi");
            }
        }
    }
}