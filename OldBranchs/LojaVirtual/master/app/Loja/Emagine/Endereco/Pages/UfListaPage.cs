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
    public class UfListaPage : ContentPage
    {
        private ListView _UfListView;

        public event EventHandler<UfInfo> AoSelecionar;

        public UfListaPage()
        {
            Title = "Selecione o Estado";
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _UfListView
                }
            };
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            try
            {
                UserDialogs.Instance.ShowLoading("Buscando...");
                var regraCep = CepFactory.create();
                _UfListView.ItemsSource = await regraCep.listarUf();
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                //UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                await DisplayAlert("Erro", erro.Message, "Fechar");
            }
        }

        private void inicializarComponente() {
            _UfListView = new ListView
            {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.Default,
                SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(UfCell))
            };
            _UfListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _UfListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                var estado = (UfInfo)((ListView)sender).SelectedItem;
                _UfListView.SelectedItem = null;

                AoSelecionar?.Invoke(this, estado);
            };
        }
    }
}