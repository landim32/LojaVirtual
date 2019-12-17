using Acr.UserDialogs;
using Emagine.Base.Estilo;
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
    public class ProdutoBasePage : ContentPage
    {
        protected ListView _ProdutoListView;
        protected TotalCarrinhoView _totalView;
        protected Type _itemTemplate = null;

        public event ProdutoListaEventHandler AoCarregar;

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
                _ProdutoListView.ItemTemplate = new DataTemplate(_itemTemplate);
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
            _ProdutoListView = new ListView
            {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.None,
                ItemTemplate = new DataTemplate(ItemTemplate)
            };
            _ProdutoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _ProdutoListView.ItemTapped += (sender, e) =>
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
                    _ProdutoListView.SelectedItem = null;
                }
            };

            _totalView = new TotalCarrinhoView {
                ExibeQuantidade = true,
                ExibeTotal = true
            };
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            _totalView.vincularComCarrinho();
            if (AoCarregar != null)
            {
                UserDialogs.Instance.ShowLoading("Carregando...");
                try
                {
                    var args = new ProdutoListaEventArgs();
                    await AoCarregar?.Invoke(this, args);
                    if (args.Produtos != null)
                    {
                        _ProdutoListView.ItemsSource = args.Produtos;
                    }
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                }
            }
        }

        protected override void OnDisappearing()
        {
            _totalView.desvincularComCarrinho();
            base.OnDisappearing();
        }
    }
}