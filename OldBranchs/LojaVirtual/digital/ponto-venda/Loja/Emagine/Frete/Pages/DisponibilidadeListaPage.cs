using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Frete.BLL;
using Emagine.Frete.Cells;
using Emagine.Frete.Model;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class DisponibilidadeListaPage : ContentPage
    {
        private ListView _disponibilidadeList;
        private bool _Inicio = true;
        private Button _Novo;

        public DisponibilidadeListaPage()
        {
            Title = "Estou livre";
            inicializarComponente();
            Content = new StackLayout{
                Margin = 10,
                Children = {
                    _disponibilidadeList,
                    _Novo
                }
            };
        }

        public async Task<List<DisponibilidadeInfo>> listarDisponibilidadeAsync()
        {
            var ret = await new MotoristaBLL().listarDisponibilidade();
            return ret;
        }

        private void inicializarComponente()
        {
            _disponibilidadeList = new ListView()
            {
                Style = Estilo.Current[Estilo.LISTA_PADRAO]
            };
            _disponibilidadeList.HasUnevenRows = true;
            _disponibilidadeList.RowHeight = -1;
            _disponibilidadeList.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _disponibilidadeList.ItemTemplate = new DataTemplate(typeof(DisponibilidadeCell));
            _disponibilidadeList.ItemTapped += async (sender, e) =>
            {
                if (e == null)
                    return;

                DisponibilidadeInfo dispInfo = (DisponibilidadeInfo)((ListView)sender).SelectedItem;
                var page = new DisponibilidadeFormPage(dispInfo);
                page._Refreshed += async (sender2, e2) => {
                    UserDialogs.Instance.ShowLoading("carregando...");
                    _disponibilidadeList.ItemsSource = await listarDisponibilidadeAsync();
                    UserDialogs.Instance.HideLoading();
                };
                Navigation.PushAsync(page);

            };

            _Novo = new Button()
            {
                Text = "Nova disponibilidade",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };
            _Novo.Clicked += (sender, e) =>
            {
                var page = new DisponibilidadeFormPage();
                page._Refreshed += async (sender2, e2) => {
                    UserDialogs.Instance.ShowLoading("carregando...");
                    _disponibilidadeList.ItemsSource = await listarDisponibilidadeAsync();
                    UserDialogs.Instance.HideLoading();
                };
                Navigation.PushAsync(page);
            };

        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (_Inicio)
            {
                _Inicio = false;
                UserDialogs.Instance.ShowLoading("carregando...");
                _disponibilidadeList.ItemsSource = await listarDisponibilidadeAsync();
                UserDialogs.Instance.HideLoading();
            }
        }
    }
}

