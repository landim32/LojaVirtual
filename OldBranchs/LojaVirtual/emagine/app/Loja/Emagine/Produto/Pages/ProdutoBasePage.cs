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
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class ProdutoBasePage : ContentPage
    {
        protected ListView _produtoListView;
        protected Frame _vazioFrame;
        protected TotalCarrinhoView _totalView;
        protected Type _itemTemplate = null;
        protected Button _carrinhoButton;
        protected Label _empresaLabel;
        protected bool _inicializado = false;

        public ProdutoFiltroInfo Filtro { get; set; }

        public bool? AbreJanela { get; set; } = null;

        public Type ItemTemplate {
            get {
                if (_itemTemplate != null) {
                    return _itemTemplate;
                }
                else if (ProdutoUtils.ListaItemTemplate != null) {
                    return ProdutoUtils.ListaItemTemplate;
                }
                else {
                    return typeof(ProdutoCell);
                }
            }
            set {
                _itemTemplate = value;
                _produtoListView.ItemTemplate = new DataTemplate(_itemTemplate);
            }
        }

        public ProdutoBasePage()
        {
            inicializarMenu();
            inicializarComponente();

            Style = Estilo.Current[Estilo.TELA_PADRAO];
        }

        protected virtual void inicializarMenu()
        {
            ToolbarItems.Add(new IconToolbarItem
            {
                Text = "Carrinho",
                Icon = "fa-shopping-cart",
                IconColor = Estilo.Current.BarTitleColor,
                Order = ToolbarItemOrder.Primary,
                Command = new Command(() =>
                {
                    Navigation.PushAsync(CarrinhoUtils.gerarCarrinhoParaEntrega());
                })
            });
        }

        private bool abreJanela() {
            if (AbreJanela.HasValue)
            {
                return AbreJanela.Value;
            }
            else if (ProdutoUtils.ListaAbreJanela.HasValue)
            {
                return ProdutoUtils.ListaAbreJanela.Value;
            }
            else {
                return false;
            }
        }

        protected virtual void inicializarComponente()
        {
            _produtoListView = new ListView
            {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.None,
                ItemTemplate = new DataTemplate(ItemTemplate)
            };
            _produtoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _produtoListView.ItemTapped += produtoItemTapped;

            _vazioFrame = new Frame
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[EstiloProduto.PRODUTO_FRAME],
                Margin = new Thickness(7, 3),
                Content = new StackLayout {
                    Orientation = StackOrientation.Horizontal,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.FillAndExpand,
                    Spacing = 2,
                    Children = {
                        new IconImage {
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.Start,
                            IconColor = Estilo.Current.Produto.Label.TextColor,
                            Margin = new Thickness(0, 2),
                            Icon = "fa-warning",
                            IconSize = 18
                        },
                        new Label {
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.Start,
                            FontSize = 18,
                            Margin = new Thickness(0, 0, 0, 3),
                            TextColor = Estilo.Current.Produto.Label.TextColor,
                            Text = " Desculpe, no momento não temos esse produto em estoque. Agradecemos sua compreensão."
                        }
                    }
                }
            };

            _totalView = new TotalCarrinhoView {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.EndAndExpand,
                //HeightRequest = 50,
                ExibeQuantidade = true,
                ExibeTotal = true
            };

            _carrinhoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[EstiloProduto.PRODUTO_CARRINHO_BOTAO],
                HeightRequest = 40,
                Text = "MEU CARRINHO"
            };
            _carrinhoButton.Clicked += (sender, e) => {
                Navigation.PushAsync(CarrinhoUtils.gerarCarrinhoParaEntrega());
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

        protected virtual void produtoItemTapped(object sender, ItemTappedEventArgs e)
        {
            if (e == null)
                return;
            if (abreJanela())
            {
                var produto = (ProdutoInfo)((ListView)sender).SelectedItem;
                Navigation.PushAsync(new ProdutoPage
                {
                    Title = produto.Nome,
                    Produto = produto
                });
            }
            else
            {
                _produtoListView.SelectedItem = null;
            }
        }

        /*
        protected virtual void atualizarProduto(IList<ProdutoInfo> produtos) {
            _produtoListView.ItemsSource = produtos;
        }
        */

        protected virtual async Task carregarProduto() {
            UserDialogs.Instance.ShowLoading("Carregando...");
            try
            {
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();
                if (loja != null)
                {
                    _empresaLabel.Text = loja.Nome;
                }

                var args = new ProdutoListaEventArgs(Filtro);
                //await AoCarregar?.Invoke(this, args);
                var regraProduto = ProdutoFactory.create();
                var retorno = await regraProduto.buscar(Filtro);
                _produtoListView.ItemsSource = retorno.Produtos;
                //atualizarProduto(retorno.Produtos);
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            _totalView.vincularComCarrinho();
            if (!_inicializado)
            {
                await carregarProduto();
                _inicializado = true;
            }
        }

        protected override void OnDisappearing()
        {
            _totalView.desvincularComCarrinho();
            base.OnDisappearing();
        }
    }
}