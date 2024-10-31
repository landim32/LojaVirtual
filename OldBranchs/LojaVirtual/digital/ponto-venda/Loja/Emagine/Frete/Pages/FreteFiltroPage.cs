using System;
using System.Collections.Generic;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Frete.Model;
using Emagine.Frete.Pages;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class FreteFiltroPage : ContentPage
    {
        private List<String> _Estados = new List<string>();
        public List<string> Estados { 
            get{
                return _Estados;
            } 
            set{
                _Estados = value;
                _Origem.ItemsSource = _Estados;
                _Destino.ItemsSource = _Estados;
            } }
        private Picker _Origem;
        private Picker _Destino;
        private DropDownList _TipoVeiculoEntry;
        private Button _Aplicar;
        public EventHandler<FreteFiltroInfo> Filtered;


        public FreteFiltroPage()
        {
            Title = "Filtro";
            inicializarComponentes();
            Content = new StackLayout
            {
                Margin = 10,
                Children = {
                    _TipoVeiculoEntry,
                    _Origem,
                    _Destino,
                    _Aplicar
                }
            };
        }


        private void inicializarComponentes()
        {

            _Origem = new Picker
            {
                Title = "Estado de origem",
                ItemsSource = Estados
            };

            _Destino = new Picker
            {
                Title = "Estado de destino",
                ItemsSource = Estados
            };

            _TipoVeiculoEntry = new DropDownList
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                //Margin = 5,
                Placeholder = "Tipo de Veículo",
                TextColor = Color.Black,
                PlaceholderColor = Color.Silver
            };
            _TipoVeiculoEntry.Clicked += (sender, e) =>
            {
                var tipoVeiculoPage = new TipoVeiculoSelecionaPage();
                tipoVeiculoPage.AoSelecionar += (object s2, Model.TipoVeiculoInfo e2) =>
                {
                    _TipoVeiculoEntry.Value = e2;
                };
                Navigation.PushAsync(tipoVeiculoPage);
            };

            _Aplicar = new Button()
            {
                Text = "FILTRAR",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };
            _Aplicar.Clicked += (sender, e) =>
            {
                Filtered?.Invoke(this, new FreteFiltroInfo
                {
                    Tipo = (TipoVeiculoInfo)_TipoVeiculoEntry.Value,
                    Destino = (string)_Destino.SelectedItem,
                    Origem = (string)_Origem.SelectedItem
                });
                Navigation.PopAsync();
            };

        }
    }
}

