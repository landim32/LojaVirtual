using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Mapa.Controls;
using Emagine.Mapa.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace Frete.Emagine.Frete.Pages
{
    public class FreteMapaRotaPage: ContentPage
    {
        private FreteInfo _frete;

        private CustomMap _CustomMap;
        private Label _distanciaLabel;
        private Label _tempoLabel;
        private Button _EnviarButton;

        public FreteInfo Frete {
            get {
                return _frete;
            }
            set {
                _frete = value;
            }
        }

        public FreteMapaRotaPage()
        {
            inicializarComponente();
            Title = "Resumo";
            Content = new StackLayout()
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _CustomMap,
                    new Label(){
                        Text = "Distância: " + info.DistanciaLabel,
                        VerticalOptions = LayoutOptions.End,
                        HorizontalOptions = LayoutOptions.Fill,
                        HeightRequest = 22,
                        Margin = new Thickness(8, 0),
                        FontSize = 18,
                        TextColor = Color.Black
                    },
                    new Label(){
                        Text = "Tempo: " + info.TempoLabel,
                        VerticalOptions = LayoutOptions.End,
                        HorizontalOptions = LayoutOptions.Fill,
                        Margin = new Thickness(8, 0),
                        HeightRequest = 22,
                        FontSize = 18,
                        TextColor = Color.Black
                    },
                    new Entry(){
                        Placeholder = "Preço",
                        VerticalOptions = LayoutOptions.End,
                        HorizontalOptions = LayoutOptions.Fill,
                        FontSize = 18,
                        TextColor = Color.Black,
                        Margin = new Thickness(8, 0, 8, 10)
                    },
                    _EnviarButton
                }
            };
        }

        private string getTextItem(FreteLocalTipoEnum tipo)
        {
            switch (tipo)
            {
                case FreteLocalTipoEnum.Saida:
                    return "Carga";
                case FreteLocalTipoEnum.Destino:
                    return "Destino";
                case FreteLocalTipoEnum.Parada:
                    return "Parada";
                default:
                    return "-";
            }
        }

        private void inicializarComponente()
        {
            _EnviarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Procurar Motorista"
            };
            _EnviarButton.Clicked += async (sender, e) => {
                UserDialogs.Instance.ShowLoading("Enviando...");
                _Info.Situacao = FreteSituacaoEnum.ProcurandoMotorista;
                await FreteFactory.create().alterar(_Info);
                UserDialogs.Instance.HideLoading();
                Navigation.PopToRootAsync();
            };
            _CustomMap = new CustomMap
            {
                MapType = MapType.Street,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill
            };
            var pontos = MapsUtils.DecodePolyline(_Info.Polyline);
            foreach (var ponto in pontos)
            {
                _CustomMap.RouteCoordinates.Add(ponto);
            }
            foreach (var pin in _Info.Locais)
            {
                _CustomMap.Pins.Add(new Pin()
                {
                    Position = new Position(pin.Latitude, pin.Longitude),
                    Label = getTextItem(pin.Tipo)
                });
            }
            var aux = _Info.Locais.First();
            var midleLat = pontos.Average(x => x.Latitude);
            var midleLon = pontos.Average((x => x.Longitude));
            var degressLat = Math.Abs(pontos.Max(x => x.Latitude) - pontos.Min(x => x.Latitude));
            var degressLon = Math.Abs(pontos.Max(x => x.Longitude) - pontos.Min(x => x.Longitude));
            _CustomMap.MoveToRegion(new MapSpan(new Position(midleLat, midleLon), degressLat + (degressLat * 0.2), degressLon + (degressLon * 0.2)));
        }
    }
}
