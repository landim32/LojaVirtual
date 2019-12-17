using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Produto.Cells;
using Emagine.Produto.Controls;
using Emagine.Produto.Events;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Xamarin.Forms.Extended;

namespace Emagine.Produto.Pages
{
    public class ProdutoGridPage: ProdutoBasePage
    {
        private const int TAMANHO_PAGINA = 9;

        protected bool _buscando = false;

        protected StackLayout _mainLayout;
        protected ActivityIndicator _carregando;
        //protected ScrollView _gridScrollview;
        //protected Grid _produtoGrid;

        public InfiniteScrollCollection<ProdutoColunaInfo> Items { get; set; }

        public ProdutoGridPage()
        {
            _mainLayout = new StackLayout
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
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

        protected InfiniteScrollCollection<ProdutoColunaInfo> criarListaInfinita() {
            return new InfiniteScrollCollection<ProdutoColunaInfo>
            {
                OnLoadMore = async () => {
                    var produtos = new List<ProdutoColunaInfo>();
                    if (!_buscando) {
                        return produtos;
                    }
                    var paginaAtual = ((Items.Count * 3) / TAMANHO_PAGINA) + 1;

                    _produtoListView.Footer = _carregando;
                    _carregando.IsRunning = true;
                    _carregando.IsVisible = true;
                    var regraProduto = ProdutoFactory.create();
                    Filtro.Pagina = paginaAtual;
                    Filtro.TamanhoPagina = TAMANHO_PAGINA;
                    var retorno = await regraProduto.buscar(Filtro);
                    ProdutoColunaInfo coluna = null;
                    foreach (var produto in retorno.Produtos)
                    {
                        if (coluna == null)
                        {
                            coluna = new ProdutoColunaInfo();
                        }
                        if (coluna.Coluna1 == null)
                        {
                            coluna.Coluna1 = produto;
                        }
                        else if (coluna.Coluna2 == null)
                        {
                            coluna.Coluna2 = produto;
                        }
                        else if (coluna.Coluna3 == null)
                        {
                            coluna.Coluna3 = produto;
                            produtos.Add(coluna);
                            coluna = null;
                        }
                    }
                    if (coluna != null)
                    {
                        produtos.Add(coluna);
                    }
                    _carregando.IsRunning = false;
                    _carregando.IsVisible = false;
                    _produtoListView.Footer = null;
                    var qtdePordutoBaixado = (Items.Count + produtos.Count) * 3;
                    if (qtdePordutoBaixado >= retorno.Total) {
                        _buscando = false;
                    }
                    if (retorno.Total == 0) {
                        _mainLayout.Children.Insert(1, _vazioFrame);
                    }
                    return produtos;
                }
            };
        }

        protected override void inicializarComponente()
        {
            base.inicializarComponente();
            _carregando = new ActivityIndicator {
                IsRunning = true,
                WidthRequest = 20,
                HeightRequest = 20,
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Center,
                Color = Estilo.Current.SuccessColor
            };

            _produtoListView.ItemTemplate = new DataTemplate(typeof(ProdutoColunaCell));
            //_produtoListView.ItemTapped -= produtoItemTapped;
            _produtoListView.Behaviors.Add(new InfiniteScrollBehavior());
            //_produtoListView.Footer = _carregando;
            Items = criarListaInfinita();
            _produtoListView.ItemsSource = Items;
        }

        protected override async Task carregarProduto()
        {
            if (_mainLayout.Children.Contains(_vazioFrame)) {
                _mainLayout.Children.Remove(_vazioFrame);
            }
            UserDialogs.Instance.ShowLoading("Carregando...");
            try
            {
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();
                if (loja != null)
                {
                    _empresaLabel.Text = loja.Nome;
                }
                _buscando = true;
                await Items.LoadMoreAsync();
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }

        protected override void produtoItemTapped(object sender, ItemTappedEventArgs e)
        {
            if (e == null)
                return;
            _produtoListView.SelectedItem = null;
        }

        protected override void inicializarMenu()
        {
            ToolbarItems.Add(new IconToolbarItem
            {
                Text = "Buscar",
                Icon = "fa-search",
                IconColor = Estilo.Current.BarTitleColor,
                Order = ToolbarItemOrder.Primary,
                Command = new Command(() =>
                {
                    var buscaPage = ProdutoUtils.gerarProdutoBusca();
                    Navigation.PushAsync(buscaPage);
                })
            });
            base.inicializarMenu();
        }
    }
}
