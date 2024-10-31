using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Endereco.Cells;
using Emagine.Endereco.Model;
using Emagine.Endereco.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Pedido.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pedido.Pages
{
    public class PedidoListaPage : ContentPage
    {
        private IList<PedidoInfo> _pedidos;

        private ListView _pedidoListView;

        public event EventHandler<PedidoInfo> AoSelecionar;

        public PedidoListaPage()
        {
            Title = "Meus Pedidos";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            _pedidos = new List<PedidoInfo>();

            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _pedidoListView
                }
            };
        }

        public IList<PedidoInfo> Pedidos {
            get {
                return _pedidos;
            }
            set {
                _pedidoListView.ItemsSource = null;
                _pedidos = value;
                _pedidoListView.ItemsSource = _pedidos;
            }
        }

        /*
        public void excluir(PedidoInfo pedido) {
            if (_pedidos != null)
            {
                _pedidoListView.ItemsSource = null;
                _pedidos.Remove(pedido);
                _pedidoListView.ItemsSource = _pedidos;
                AoAtualizar?.Invoke(this, _pedidos);
            }
        }
        */

        private void inicializarComponente() {
            _pedidoListView = new ListView
            {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.None,
                ItemTemplate = new DataTemplate(typeof(PedidoCell))
            };
            _pedidoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _pedidoListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                var pedido = (PedidoInfo)((ListView)sender).SelectedItem;
                _pedidoListView.SelectedItem = null;

                AoSelecionar?.Invoke(this, pedido);
            };
        }
    }
}