using System;
using Emagine.Base.Estilo;
using Emagine.Mapa.Utils;
using Plugin.Geolocator;
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace Emagine.Frete.Pages
{
    public class FretePageOld : ContentPage
    {
        private Map _Mapa;
        private Label _TempoLabel;
        private Button _EntregarButton;

        public FretePageOld()
        {
            Title = "Encomenda em Transito";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    gerarTempo(),
                    _Mapa,
                    new StackLayout {
                        Orientation = StackOrientation.Vertical,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Padding = 5,
                        Children = {
                            _EntregarButton
                        }
                    }
                }
            };
        }

        private StackLayout gerarTempo() {
            return new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Padding = new Thickness(3, 2),
                Children = {
                    new Label {
                        Text = "Tempo previsto:",
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.Start,
                        FontSize = 11,
                    },
                    _TempoLabel
                }
            };
        }

        public bool IsLocationAvailable()
        {

            return MapsUtils.IsLocationAvailable();
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (IsLocationAvailable())
            {
                var posicao = await CrossGeolocator.Current.GetPositionAsync();
                var mapaPosicao = new Position(posicao.Latitude, posicao.Longitude);
                _Mapa.MoveToRegion(MapSpan.FromCenterAndRadius(mapaPosicao, Distance.FromMiles(0.3)));
                var pin = new Pin
                {
                    Type = PinType.Place,
                    Position = mapaPosicao,
                    Label = "Rodrigo Landim",
                    Address = "Carro - 3454543"
                };
                /*
                pin.Clicked += (sender, e) => {
                    Navigation.PushAsync(new PagamentoPage());
                };
                */

                _Mapa.Pins.Add(pin);
            }
        }

        private void inicializarComponente() {
            _Mapa = new Map
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                IsShowingUser = true,
            };
            _TempoLabel = new Label
            {
                Text = "15min",
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.End,
                WidthRequest = 100,
                FontSize = 11,
                FontAttributes = FontAttributes.Bold
            };
            _EntregarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "RECEBI A ENCOMENDA"
            };
        }
    }
}

