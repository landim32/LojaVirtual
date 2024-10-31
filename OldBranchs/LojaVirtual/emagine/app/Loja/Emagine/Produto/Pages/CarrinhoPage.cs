using Acr.UserDialogs;
using Emagine.Banner.Utils;
using Emagine.Base.Estilo;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Emagine.Produto.Cells;
using Emagine.Produto.Controls;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class CarrinhoPage : ContentPage
    {
        private StackLayout _valorMinimoLayout;
        private StackLayout _rodapeLayout;
        private ListView _ProdutoListView;
        private TotalCarrinhoView _totalView;
        protected Type _itemTemplate = null;
        protected Label _empresaLabel;
        protected Label _valorMinimoLabel;
        private Button _continuarCompraButton;
        private Button _finalizarCompraButton;

        public event EventHandler<IList<ProdutoInfo>> AoFinalizar;

        public Type ItemTemplate {
            get {
                if (_itemTemplate != null) {
                    return _itemTemplate;
                }
                else if (ProdutoUtils.CarrinhoItemTemplate != null) {
                    return ProdutoUtils.CarrinhoItemTemplate;
                }
                else {
                    return typeof(ProdutoCell);
                }
            }
            set {
                _itemTemplate = value;
                _ProdutoListView.ItemTemplate = new DataTemplate(_itemTemplate);
            }
        }

        public CarrinhoPage()
        {
            Title = "Meu Carrinho";

            ToolbarItems.Add(new IconToolbarItem
            {
                Text = "Limpar",
                Icon = "fa-trash",
                IconColor = Estilo.Current.BarTitleColor,
                Order = ToolbarItemOrder.Primary,
                Command = new Command(() => {
                    UserDialogs.Instance.Confirm(new ConfirmConfig
                    {
                        Title = "Aviso",
                        Message = "Tem certeza?",
                        OkText = "Sim",
                        CancelText = "Não",
                        OnAction = (confirmado) =>
                        {
                            if (confirmado)
                            {
                                var regraCarrinho = CarrinhoFactory.create();
                                regraCarrinho.limpar();
                                _ProdutoListView.ItemsSource = regraCarrinho.listar();
                            }
                        }
                    });
                })
            });

            Style = Estilo.Current[Estilo.TELA_PADRAO];
            inicializarComponente();
            _valorMinimoLayout = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                Margin = new Thickness(5, 0),
                Spacing = 0,
                Children = {
                    new Label {
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Start,
                        HorizontalTextAlignment = TextAlignment.Center,
                        Text = "Valor Mínimo:",
                        FontSize = 10
                    },
                    _valorMinimoLabel
                }
            };
            _rodapeLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(5, 0),
                Spacing = 0,
                Children = {
                    _valorMinimoLayout,
                    new Label {
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Fill,
                        HorizontalTextAlignment = TextAlignment.Center,
                        Text = "Você está comprando em:",
                        FontSize = 10
                    },
                    _empresaLabel
                }
            };
            Content = new StackLayout
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _continuarCompraButton,
                    _ProdutoListView,
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Fill,
                        Margin = new Thickness(5, 0),
                        Children = {
                            _totalView,
                            _finalizarCompraButton
                        }
                    },
                    _rodapeLayout
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();

            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            if (loja != null) {
                _empresaLabel.Text = loja.Nome;
                if (loja.ValorMinimo > 0)
                {
                    _valorMinimoLabel.Text = loja.ValorMinimo.ToString("N2");
                }
                else {
                    _rodapeLayout.Children.Remove(_valorMinimoLayout);
                }
            }

            var regraCarrinho = CarrinhoFactory.create();
            if (regraCarrinho.Loja != null) {
                _empresaLabel.Text = regraCarrinho.Loja.Nome;
            }
            _ProdutoListView.ItemsSource = regraCarrinho.listar();
            _totalView.vincularComCarrinho();
        }

        protected override void OnDisappearing()
        {
            _totalView.desvincularComCarrinho();
            base.OnDisappearing();
        }

        private void inicializarComponente() {
            _ProdutoListView = new ListView {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.None,
                ItemTemplate = new DataTemplate(ItemTemplate)
            };
            _ProdutoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));

            _totalView = new TotalCarrinhoView {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                ExibeQuantidade = true,
                ExibeTotal = true,
                QuantidadeTitulo = "Qtde:"
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

            _valorMinimoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 10,
                FontAttributes = FontAttributes.Bold,
                Margin = new Thickness(0, 0, 0, 3),
                Text = "0,00"
            };

            _continuarCompraButton = new Button {
                Style = Estilo.Current[EstiloProduto.PRODUTO_CARRINHO_BOTAO],
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Text = "Continuar Compras",
                FontSize = 11,
                HeightRequest = 40,
                Margin = new Thickness(4, 3, 4, 0)
            };
            _continuarCompraButton.Clicked += (sender, e) => {
                var categoriaPage = CategoriaPageFactory.create();
                categoriaPage.BannerVisivel = BannerUtils.Ativo;
                categoriaPage.Title = "Categorias";
                Navigation.PushAsync(categoriaPage);
            };

            _finalizarCompraButton = new Button {
                //HorizontalOptions = LayoutOptions.FillAndExpand,
                //VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Text = "Finalizar Compra",
                FontSize = 11,
                HeightRequest = 40,
                //Margin = new Thickness(4, 0, 4, 3),
                //Style = Estilo.Current[Estilo.BTN_SUCESSO]
                Style = Estilo.Current[EstiloProduto.PRODUTO_CARRINHO_BOTAO]
            };
            _finalizarCompraButton.Clicked += FinalizarCompraButtonClicked;
        }

        public void excluir(ProdutoInfo produto)
        {
            var regraCarrinho = CarrinhoFactory.create();
            regraCarrinho.excluir(produto);
            var produtos = regraCarrinho.listar();
            if (produtos != null && produtos.Count == 0)
            {
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();
                _empresaLabel.Text = loja.Nome;
            }
            _ProdutoListView.ItemsSource = produtos;
        }

        private async void FinalizarCompraButtonClicked(object sender, EventArgs e)
        {
            if (AoFinalizar != null)
            {
                var regraLoja = LojaFactory.create();
                var regraCarrinho = CarrinhoFactory.create();
                var loja = regraLoja.pegarAtual();
                if (loja.ValorMinimo > 0 && regraCarrinho.getTotal() < loja.ValorMinimo)
                {
                    var mensagem = string.Format("Sua compra precisa ter o valor mínimo de R$ {0}", loja.ValorMinimo.ToString("N2"));
                    await UserDialogs.Instance.AlertAsync(mensagem, "Aviso", "Entendi");
                }
                else {
                    var produtos = regraCarrinho.listar();
                    AoFinalizar(this, produtos);
                }
            }
        }
    }
}