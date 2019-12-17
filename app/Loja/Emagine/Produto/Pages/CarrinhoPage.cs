using Acr.UserDialogs;
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
        private ListView _ProdutoListView;
        private TotalCarrinhoView _totalView;
        protected Type _itemTemplate = null;
        private Button _FinalizarCompraButton;

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
            Content = new StackLayout
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _ProdutoListView,
                    _totalView,
                    _FinalizarCompraButton
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();

            var regraCarrinho = CarrinhoFactory.create();
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
                ItemTemplate = new DataTemplate(typeof(ProdutoCell))
            };
            _ProdutoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));

            _totalView = new TotalCarrinhoView {
                ExibeQuantidade = true,
                ExibeTotal = true
            };

            _FinalizarCompraButton = new Button {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.End,
                Text = "Finalizar Compra",
                Margin = new Thickness(4, 0, 4, 3),
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };
            _FinalizarCompraButton.Clicked += FinalizarCompraButtonClicked;
        }

        private void FinalizarCompraButtonClicked(object sender, EventArgs e)
        {
            if (AoFinalizar != null)
            {
                var regraCarrinho = CarrinhoFactory.create();
                var produtos = regraCarrinho.listar();
                AoFinalizar(this, produtos);
            }
        }
    }
}