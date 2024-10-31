using System;
using Xamarin.Forms;
using Emagine.Base.Estilo;
using Acr.UserDialogs;
using System.Collections.Generic;
using Emagine.Frete.Cells;
using Emagine.Frete.Model;
using Emagine.Mapa.Pages;
using Emagine.Frete.BLL;

namespace Emagine.Frete.Pages
{
    public class FreteHistoricoPage : ContentPage
    {
        private ListView _HistoricoList;
        private bool _Inicio = true;
        private Button _Atualizar;
        private int _Id;

        public FreteHistoricoPage(int id_frete)
        {
            _Id = id_frete;
            Title = "Histórico de posicionamento";
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = 10,
                Children = {
                    _HistoricoList,
                    _Atualizar
                }
            };
        }


        private void inicializarComponente()
        {
            _HistoricoList = new ListView()
            {
                Style = Estilo.Current[Estilo.LISTA_PADRAO]
            };
            _HistoricoList.HasUnevenRows = true;
            _HistoricoList.RowHeight = -1;
            _HistoricoList.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _HistoricoList.ItemTemplate = new DataTemplate(typeof(PosicionamentoCell));
            _HistoricoList.ItemTapped += async (sender, e) =>
            {
                if (e == null)
                    return;

                FreteHistoricoInfo info = (FreteHistoricoInfo)((ListView)sender).SelectedItem;

                Navigation.PushAsync(new MapaPontoPage("Posicionamento", info.TextoCell, info.Latitude, info.Longitude));
                _HistoricoList.SelectedItem = null;
            };

            _Atualizar = new Button()
            {
                Text = "Atualizar",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };
            _Atualizar.Clicked += (sender, e) =>
            {
                //Navigation.PushAsync(new DisponibilidadeFormPage());
            };

        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (_Inicio)
            {
                _Inicio = false;
                UserDialogs.Instance.ShowLoading("carregando...");
                _HistoricoList.ItemsSource = await new FreteBLL().listarHistorico(_Id);
                UserDialogs.Instance.HideLoading();
            }
        }
    }
}

