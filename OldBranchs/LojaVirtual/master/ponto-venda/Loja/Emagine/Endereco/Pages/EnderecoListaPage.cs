using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Endereco.Cells;
using Emagine.Endereco.Model;
using Emagine.Endereco.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Endereco.Pages
{
    public class EnderecoListaPage : ContentPage
    {
        private IList<EnderecoInfo> _enderecos;

        private ListView _enderecoListView;

        public event EventHandler<IList<EnderecoInfo>> AoAtualizar;
        public event EventHandler<EnderecoInfo> AoSelecionar;

        public EnderecoListaPage()
        {
            Title = "Meus Endereços";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            _enderecos = new List<EnderecoInfo>();

            ToolbarItems.Add(new IconToolbarItem
            {
                Text = "Adicionar",
                Icon = "fa-plus",
                IconColor = Estilo.Current.BarTitleColor,
                Order = ToolbarItemOrder.Primary,
                Command = new Command(() => {
                    var cepPage = EnderecoUtils.gerarBuscaPorCep((endereco) =>
                    {
                        _enderecos.Add(endereco);
                        AoAtualizar?.Invoke(this, _enderecos);
                        Navigation.PopAsync();
                    }, false);
                    Navigation.PushAsync(cepPage);
                })
            });

            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _enderecoListView
                }
            };
        }

        public IList<EnderecoInfo> Enderecos {
            get {
                return _enderecos;
            }
            set {
                _enderecoListView.ItemsSource = null;
                _enderecos = value;
                _enderecoListView.ItemsSource = _enderecos;
            }
        }

        public void excluir(EnderecoInfo endereco) {
            if (_enderecos != null)
            {
                _enderecoListView.ItemsSource = null;
                _enderecos.Remove(endereco);
                _enderecoListView.ItemsSource = _enderecos;
                AoAtualizar?.Invoke(this, _enderecos);
            }
        }

        private void inicializarComponente() {
            _enderecoListView = new ListView
            {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.None,
                //ItemTemplate = new DataTemplate(typeof(EnderecoControl))
                ItemTemplate = new DataTemplate(typeof(EnderecoCell))
            };
            _enderecoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _enderecoListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                var estado = (EnderecoInfo)((ListView)sender).SelectedItem;
                _enderecoListView.SelectedItem = null;

                AoSelecionar?.Invoke(this, estado);
            };
        }
    }
}