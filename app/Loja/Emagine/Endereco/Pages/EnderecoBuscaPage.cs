using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Endereco.Cells;
using Emagine.Endereco.Model;
using Emagine.Login.Factory;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Endereco.Pages
{
    public class EnderecoBuscaPage : ContentPage
    {
        private SearchBar _palavraChaveSearchBar;
        private ListView _EnderecoListView;

        public event EventHandler<EnderecoInfo> AoSelecionar;

        public EnderecoBuscaPage()
        {
            Title = "Busque seu Endereço";
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _palavraChaveSearchBar,
                    _EnderecoListView
                }
            };
        }

        public int IdBairro { get; set; }

        private void inicializarComponente() {
            _palavraChaveSearchBar = new SearchBar
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Placeholder = "Buscar endereço...",
                SearchCommand = new Command(() => {
                    executarBusca(_palavraChaveSearchBar.Text, IdBairro);
                })
            };
            _EnderecoListView = new ListView
            {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.Default,
                SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(LogradouroCell))
            };
            _EnderecoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _EnderecoListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                var endereco = (EnderecoInfo)((ListView)sender).SelectedItem;
                _EnderecoListView.SelectedItem = null;

                AoSelecionar?.Invoke(this, endereco);
            };
        }

        private async void executarBusca(string palavraChave, int idBairro) {
            try
            {
                UserDialogs.Instance.ShowLoading("Buscando...");
                var regraCep = CepFactory.create();
                _EnderecoListView.ItemsSource = await regraCep.buscarPorLogradouro(palavraChave, idBairro);
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
                //UserDialogs.Instance.ShowError(erro.Message, 8000);
            }
        }
    }
}