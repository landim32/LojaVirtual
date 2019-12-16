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
    public class BairroBuscaPage : ContentPage
    {
        private SearchBar _palavraChaveSearchBar;
        private ListView _BairroListView;

        public event EventHandler<BairroInfo> AoSelecionar;

        public BairroBuscaPage()
        {
            Title = "Busque seu bairro";
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _palavraChaveSearchBar,
                    _BairroListView
                }
            };
        }

        public int IdCidade { get; set; }

        private void inicializarComponente() {
            _palavraChaveSearchBar = new SearchBar
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Placeholder = "Buscar bairro...",
                Style = Estilo.Current[Estilo.SEARCH_BAR],
                SearchCommand = new Command(() => {
                    executarBusca(_palavraChaveSearchBar.Text, IdCidade);
                })
            };
            _BairroListView = new ListView
            {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.Default,
                SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(BairroCell))
            };
            _BairroListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _BairroListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                var bairro = (BairroInfo)((ListView)sender).SelectedItem;
                _BairroListView.SelectedItem = null;

                AoSelecionar?.Invoke(this, bairro);
            };
        }

        private async void executarBusca(string palavraChave, int IdCidade) {
            try
            {
                UserDialogs.Instance.ShowLoading("Buscando...");
                var regraCep = CepFactory.create();
                _BairroListView.ItemsSource = await regraCep.buscarPorBairro(palavraChave, IdCidade);
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                //UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                await DisplayAlert("Erro", erro.Message, "Fechar");
            }
        }
    }
}