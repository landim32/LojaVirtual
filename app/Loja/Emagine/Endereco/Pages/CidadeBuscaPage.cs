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
    public class CidadeBuscaPage : ContentPage
    {
        private SearchBar _palavraChaveSearchBar;
        private ListView _CidadeListView;

        public event EventHandler<CidadeInfo> AoSelecionar;

        public CidadeBuscaPage()
        {
            Title = "Busque sua Cidade";
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _palavraChaveSearchBar,
                    _CidadeListView
                }
            };
        }

        public string Uf { get; set; }

        private void inicializarComponente() {
            _palavraChaveSearchBar = new SearchBar
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Placeholder = "Buscar cidade...",
                Style = Estilo.Current[Estilo.SEARCH_BAR],
                SearchCommand = new Command(() => {
                    executarBusca(_palavraChaveSearchBar.Text, Uf);
                })
            };
            _CidadeListView = new ListView
            {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.Default,
                SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(CidadeCell))
            };
            _CidadeListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _CidadeListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                var cidade = (CidadeInfo)((ListView)sender).SelectedItem;
                _CidadeListView.SelectedItem = null;

                AoSelecionar?.Invoke(this, cidade);
            };
        }

        private async void executarBusca(string palavraChave, string uf) {
            try
            {
                UserDialogs.Instance.ShowLoading("Buscando...");
                var regraCep = CepFactory.create();
                _CidadeListView.ItemsSource = await regraCep.buscarPorCidade(palavraChave, uf);
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