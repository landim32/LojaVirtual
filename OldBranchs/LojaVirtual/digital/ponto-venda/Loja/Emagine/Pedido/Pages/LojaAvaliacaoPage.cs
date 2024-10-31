using Emagine.Base.Estilo;
using Emagine.Endereco.Cells;
using Emagine.Pedido.Cells;
using Emagine.Pedido.Model;
using Emagine.Produto.Cells;
using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pedido.Pages
{
    public class LojaAvaliacaoPage : ContentPage
    {
        private IList<PedidoInfo> _pedidos;
        private ListView _pedidoListView;
        private Label _empresaLabel;

        public IList<PedidoInfo> Pedidos {
            get {
                return _pedidos;
            }
            set {
                _pedidos = value;
                _pedidoListView.ItemsSource = null;
                _pedidoListView.ItemsSource = _pedidos;
            }
        }

        public LojaAvaliacaoPage()
        {
            Title = "Avaliações";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            inicializarComponente();

            Content = new StackLayout
            {
                //Margin = new Thickness(3, 3),
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Spacing = 0,
                Children = {
                    _pedidoListView,
                    _empresaLabel
                }
            };
        }

        private void inicializarComponente()
        {
            _pedidoListView = new ListView
            {
                Style = Estilo.Current[Estilo.LISTA_PADRAO],
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.Default,
                //SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(AvaliacaoCell))
            };
            _pedidoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _pedidoListView.ItemTapped += (sender, e) =>
            {
                _pedidoListView.SelectedItem = null;
            };
            _empresaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.Center,
                Margin = new Thickness(0, 5),
                Text = "Smart Tecnologia ®"
            };
        }
    }
}