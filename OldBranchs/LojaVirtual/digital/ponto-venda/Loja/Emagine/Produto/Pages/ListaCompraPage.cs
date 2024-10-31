using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Produto.Cells;
using Emagine.Produto.Events;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class ListaCompraPage : ContentPage
    {
        private IList<string> _palavraChave;
        //private Grid _menuGrid;
        //private Button _destaqueButton;
        private Button _promocaoButton;
        private Button _pesquisarButton;
        protected SearchBar _buscaBar;
        private ListView _palavraChaveListView;
        protected Label _empresaLabel;

        public ListaCompraPage()
        {
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];

            _palavraChave = new List<string>();

            inicializarComponente();

            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    //_menuGrid,
                    _promocaoButton,
                    _buscaBar,
                    _palavraChaveListView,
                    _pesquisarButton,
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
        }

        public void adicionarPalavraChave(string palavraChave) {
            _palavraChave.Add(palavraChave);
            _palavraChaveListView.ItemsSource = null;
            _palavraChaveListView.ItemsSource = _palavraChave;
        }

        public void removerPalavraChave(string palavraChave) {
            if (_palavraChave.Contains(palavraChave)) {
                _palavraChave.Remove(palavraChave);
            }
            _palavraChaveListView.ItemsSource = null;
            _palavraChaveListView.ItemsSource = _palavraChave;
        }

        protected void inicializarComponente()
        {
            /*
            _destaqueButton = new Button
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Text = "Destaques",
                FontSize = 14,
                HeightRequest = 40,
                Style = Estilo.Current[EstiloProduto.PRODUTO_CARRINHO_BOTAO]
            };
            */

            _promocaoButton = new Button
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Text = "Promoções",
                FontSize = 14,
                HeightRequest = 40,
                Style = Estilo.Current[EstiloProduto.PRODUTO_CARRINHO_BOTAO]
            };
            _promocaoButton.Clicked += (sender, e) => {
                Navigation.PushAsync(ProdutoUtils.gerarProdutoListaPromocao());
            };

            _pesquisarButton = new Button
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Text = "PESQUISAR",
                FontSize = 14,
                HeightRequest = 40,
                Style = Estilo.Current[EstiloProduto.PRODUTO_CARRINHO_BOTAO]
            };
            _pesquisarButton.Clicked += (sender, e) => {
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();
                var resultadoPesquisaPage = ProdutoListaPageFactory.create();
                resultadoPesquisaPage.Title = "Lista de compras";
                resultadoPesquisaPage.Filtro = new ProdutoFiltroInfo {
                    IdLoja = loja.Id,
                    PalavraChave = string.Join(" ", _palavraChave),
                    Condicao = true,
                    Situacao = SituacaoEnum.Ativo
                };
                /*
                resultadoPesquisaPage.AoCarregar += async (object s, ProdutoListaEventArgs produtoArgs) =>
                {
                    var regraLoja = LojaFactory.create();
                    var loja = regraLoja.pegarAtual();
                    var regraProduto = ProdutoFactory.create();
                    var filtro = new ProdutoFiltroInfo
                    {
                        IdLoja = loja.Id,
                        PalavraChave = string.Join(" ", _palavraChave),
                        Situacao = SituacaoEnum.Ativo
                    };
                    produtoArgs.Produtos = await regraProduto.listarPorFiltro(filtro);
                };
                */
                Navigation.PushAsync(resultadoPesquisaPage);
            };

            /*
            _menuGrid = new Grid
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = 1,
                RowSpacing = 1,
                ColumnSpacing = 3
            };
            _menuGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _menuGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });

            _menuGrid.Children.Add(_destaqueButton, 0, 0);
            _menuGrid.Children.Add(_promocaoButton, 1, 0);
            */

            _buscaBar = new SearchBar
            {
                //Placeholder = "BUSQUE POR LOJAS EM SUA REGIÃO",
                Placeholder = "Adicione outra palavra-chave",
                SearchCommand = new Command(() => {
                    adicionarPalavraChave(_buscaBar.Text);
                    _buscaBar.Text = "";
                })
            };

            _palavraChaveListView = new ListView
            {
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.Default,
                SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(ListaCompraCell))
            };
            _palavraChaveListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _palavraChaveListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                //var categoria = (CategoriaInfo)((ListView)sender).SelectedItem;
                _palavraChaveListView.SelectedItem = null;

                //await abrirCategoria(categoria);
            };

            _empresaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.Center,
                FontAttributes = FontAttributes.Bold,
                Margin = new Thickness(0, 0, 0, 3),
                Text = "Smart Tecnologia ®"
            };

        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            if (loja != null) {
                _empresaLabel.Text = loja.Nome;
            }
        }
    }
}