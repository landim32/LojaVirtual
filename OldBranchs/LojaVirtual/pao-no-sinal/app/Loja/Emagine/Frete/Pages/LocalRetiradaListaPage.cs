using System;
using Xamarin.Forms;
using Emagine.Base.Estilo;
using Acr.UserDialogs;
using System.Collections.Generic;
using Emagine.Frete.Cells;
using Emagine.Frete.Model;
using Emagine.Mapa.Pages;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Endereco.Model;

namespace Emagine.Frete.Pages
{
    public class LocalRetiradaListaPage : ContentPage
    {
        private ListView _EnderecoList;
        private bool _Inicio = true;
        private Button _Novo;
        public event EventHandler<EnderecoInfo> Selected;

        public LocalRetiradaListaPage()
        {
            Title = "Locais de retirada";
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = 10,
                Children = {
                    _EnderecoList,
                    _Novo
                }
            };
        }


        private void inicializarComponente()
        {
            _EnderecoList = new ListView()
            {
                Style = Estilo.Current[Estilo.LISTA_PADRAO]
            };
            _EnderecoList.HasUnevenRows = true;
            _EnderecoList.RowHeight = -1;
            _EnderecoList.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _EnderecoList.ItemTemplate = new DataTemplate(typeof(LocalRetiradaCell));
            _EnderecoList.ItemTapped += async (sender, e) =>
            {
                if (e == null)
                    return;

                var ret = await DisplayAlert("", "Como gostaria de prosseguir ?", "Utilizar este", "Editar");

                if(ret){
                    Selected?.Invoke(this, (UsuarioEnderecoInfo)((ListView)sender).SelectedItem);
                    Navigation.PopAsync();
                } else {
                    var endrVw = new EnderecoRetiradaPage((UsuarioEnderecoInfo)((ListView)sender).SelectedItem);
                    endrVw.Finished += (sender2, e2) =>
                    {
                        Selected?.Invoke(this, e2);
                        Navigation.PopAsync();
                    };
                    Navigation.PushAsync(endrVw);  
                }

                _EnderecoList.SelectedItem = null;
            };

            _Novo = new Button()
            {
                Text = "Novo",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };
            _Novo.Clicked += (sender, e) =>
            {
                var endrVw = new EnderecoRetiradaPage();
                endrVw.Finished += (sender2, e2) =>
                {
                    Selected?.Invoke(this, e2);
                    Navigation.PopAsync();
                };
                Navigation.PushAsync(endrVw);
            };

        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (_Inicio)
            {
                _Inicio = false;
                UserDialogs.Instance.ShowLoading("carregando...");
                _EnderecoList.ItemsSource = UsuarioFactory.create().pegarAtual().Enderecos;
                UserDialogs.Instance.HideLoading();
            }
        }
    }
}

