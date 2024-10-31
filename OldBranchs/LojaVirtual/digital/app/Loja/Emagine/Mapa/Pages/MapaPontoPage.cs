using System;
using Emagine.Mapa.Controls;
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace Emagine.Mapa.Pages
{
    public class MapaPontoPage : ContentPage
    {
        private CustomMap _Mapa;

        public MapaPontoPage(string title, string textoPin, double Latitude, double Longitude)
        {
            Title = title;

            _Mapa = new CustomMap()
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand
            };

            var posicao = new Position(Latitude, Longitude);
            _Mapa.MoveToRegion(MapSpan.FromCenterAndRadius(posicao, Distance.FromMiles(0.3)));
            var pin = new Pin
            {
                Type = PinType.Generic,
                Position = posicao,
                Label = textoPin
            };
            _Mapa.Pins.Clear();
            _Mapa.Pins.Add(pin);

            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    _Mapa
                }
            };
        }
    }
}

