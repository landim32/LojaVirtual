using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Plugin.Geolocator;
using Xamarin.Forms;
using Xamarin.Forms.Maps;
using System.Linq;
using Acr.UserDialogs;
using System.Net.Http;
using Newtonsoft.Json;
using System.Collections;
using Emagine.Mapa.Controls;
using Emagine.Base.Estilo;
using Emagine.Mapa.Model;
using Emagine.Mapa.Utils;
using Emagine.Frete.Cells;
using Emagine.Base.Utils;

namespace Emagine.Mapa.Pages
{
    [Obsolete("Está muito vinculado do modulo de Frete. Use o MapaLocalPage")]
    public class MapaPageOld : ContentPage
    {
        private CustomMap _Mapa;
        private SearchBar _BarraBusca;
        private Button _EntregarButton;
        private ListView _LocaisLista;
        private bool _ListaNaTela = false;
        private string _PinLabel;
        private PontoTransporteInfo _ponto;
        private List<PontoTransporteInfo> _Pai;
        private ListView _ListaLocais;

        public MapaPageOld(string pinLabel, ref PontoTransporteInfo ponto, ref List<PontoTransporteInfo> pai, ref ListView lista)
        {
            _ponto = ponto;
            _Pai = pai;
            _ListaLocais = lista;
            _PinLabel = pinLabel;
            Title = "Selecione o local";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    new ContentView(){
                        Content = _BarraBusca     
                    },
                    _Mapa,
                    _EntregarButton
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
            _BarraBusca.Focus();
            if(_ponto.Posicao != null){
                _Mapa.MoveToRegion(MapSpan.FromCenterAndRadius((Position)_ponto.Posicao, Distance.FromMiles(0.3)));
                var pin = new Pin
                {
                    Type = PinType.Place,
                    Position = (Position)_ponto.Posicao,
                    Address = _ponto.Text,
                    Label = _PinLabel
                };
                _Mapa.Pins.Clear();
                _Mapa.Pins.Add(pin);
            } else {
                if (IsLocationAvailable())
                {
                    var posicao = await CrossGeolocator.Current.GetLastKnownLocationAsync();
                    var mapaPosicao = new Position(posicao.Latitude, posicao.Longitude);
                    UserDialogs.Instance.ShowLoading("Carregando...");
                    var retGeocoder = await new Geocoder().GetAddressesForPositionAsync(mapaPosicao);
                    UserDialogs.Instance.HideLoading();
                    if(retGeocoder != null && retGeocoder.Count() > 0){
                        _Mapa.MoveToRegion(MapSpan.FromCenterAndRadius(mapaPosicao, Distance.FromMiles(0.3)));
                        var pin = new Pin
                        {
                            Type = PinType.Place,
                            Position = mapaPosicao,
                            Label = _PinLabel,
                            Address = retGeocoder.LastOrDefault()
                        };
                        _Mapa.Pins.Clear();
                        _Mapa.Pins.Add(pin);    
                    }
                }   
            }
        }

        private async Task iniciaBuscaAsync()
        {
            UserDialogs.Instance.ShowLoading("Pesquisando...");
            var lstRetorno = new List<PesquisaLocalizacaoInfo>();
            var posicao = await CrossGeolocator.Current.GetLastKnownLocationAsync();
            try
            {
                using (var client = new HttpClient())
                {

                    HttpResponseMessage response = await client.GetAsync(HttpUtils.montaLinkGet(
                        "https://maps.googleapis.com/maps/api/place/autocomplete/json",
                        new List<KeyValuePair<string, string>>() {
                        new KeyValuePair<string, string>("input", _BarraBusca.Text),
                        new KeyValuePair<string, string>("location", posicao.Latitude + "," + posicao.Longitude),
                        new KeyValuePair<string, string>("language", "pt_BR"),
                        new KeyValuePair<string, string>("key", "AIzaSyCYqp1ZAiX0M8Lqth1kFYRh6wju0Y4vAm8")
                        }
                    ));
                    response.EnsureSuccessStatusCode();
                    var strResposta = await response.Content.ReadAsStringAsync();
                    var retGoogle = JsonConvert.DeserializeObject<GoogleLocationInfo>(strResposta);
                    foreach (var itemLoc in retGoogle.predictions)
                    {
                        lstRetorno.Add(new PesquisaLocalizacaoInfo()
                        {
                            Text = itemLoc.description
                        });
                    }
                    _LocaisLista.ItemsSource = lstRetorno;
                    if (!_ListaNaTela)
                    {
                        _ListaNaTela = true;
                        ((StackLayout)this.Content).Children.Remove((_Mapa));
                        ((StackLayout)this.Content).Children.Remove((_EntregarButton));
                        ((StackLayout)this.Content).Children.Add((_LocaisLista));
                    }
                    UserDialogs.Instance.HideLoading();
                }
            }
            catch (Exception e)
            {
                UserDialogs.Instance.HideLoading();
                await DisplayAlert("Falha", "Tente novamente mais tarde.", "Entendi");
            }

        }

        private async Task pegarManualAsync(Position pos)
        {
            UserDialogs.Instance.ShowLoading("Carregando...");
            var retGeocoder = await new Geocoder().GetAddressesForPositionAsync(pos);
            UserDialogs.Instance.HideLoading();
            if (retGeocoder != null && retGeocoder.Count() > 0)
            {
                _Mapa.MoveToRegion(MapSpan.FromCenterAndRadius(pos, Distance.FromMiles(0.3)));
                var pin = new Pin
                {
                    Type = PinType.Place,
                    Position = pos,
                    Label = _PinLabel,
                    Address = retGeocoder.LastOrDefault()
                };
                _Mapa.Pins.Clear();
                _Mapa.Pins.Add(pin);
            }
        }

        private void inicializarComponente() {
            _BarraBusca = new SearchBar()
            {
                BackgroundColor = Color.White,
                Placeholder = "Pesquisar...",
                HeightRequest = 42
            };
            _BarraBusca.SearchButtonPressed += (sender, e) => {
                iniciaBuscaAsync();
            };

            _Mapa = new CustomMap()
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                IsShowingUser = true
            };
            _Mapa.aoClicar += (sender, e) => {
               pegarManualAsync(e.Position);
            };

            _EntregarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Confirmar"
            };
            _EntregarButton.Pressed += (sender, e) => {
                if(_Mapa.Pins.Count() > 0){
                    _ponto.Text = _Mapa.Pins.First().Address;
                    _ponto.Posicao = _Mapa.Pins.First().Position;
                    if(_ponto.Tipo == TipoPontoTransporteEnum.Add){
                        _ponto.Tipo = TipoPontoTransporteEnum.Trecho;
                        _Pai.Add(new PontoTransporteInfo() { Text = "", Tipo = TipoPontoTransporteEnum.Add });
                    }
                    _ListaLocais.ItemsSource = null;
                    _ListaLocais.ItemsSource = _Pai;
                    Navigation.PopAsync();    
                } else {
                    DisplayAlert("Atenção", "Selecione um ponto", "Entendi");
                }

            };
            _LocaisLista = new ListView();
            _LocaisLista.HasUnevenRows = true;
            _LocaisLista.RowHeight = -1;
            _LocaisLista.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _LocaisLista.ItemTemplate = new DataTemplate(typeof(ResultadoBuscaCell));
            _LocaisLista.ItemTapped += async (sender, e) =>
            {
                if (e == null)
                    return;
                var item = (PesquisaLocalizacaoInfo)((ListView)sender).SelectedItem;
                if (_ListaNaTela)
                {
                    _ListaNaTela = false;
                    ((StackLayout)this.Content).Children.Remove((_LocaisLista));
                    ((StackLayout)this.Content).Children.Add((_Mapa));
                    ((StackLayout)this.Content).Children.Add((_EntregarButton));
                }
                UserDialogs.Instance.ShowLoading("Carregando...");
                using (var client = new HttpClient())
                {
                    HttpResponseMessage response = await client.GetAsync(HttpUtils.montaLinkGet(
                        "https://maps.googleapis.com/maps/api/geocode/json",
                        new List<KeyValuePair<string, string>>() {
                        new KeyValuePair<string, string>("address", item.Text),
                        new KeyValuePair<string, string>("key", "AIzaSyCYqp1ZAiX0M8Lqth1kFYRh6wju0Y4vAm8")
                        }
                    ));
                    response.EnsureSuccessStatusCode();
                    var strResposta = await response.Content.ReadAsStringAsync();
                    var retGeocoder = JsonConvert.DeserializeObject<GoogleGeocodingInfo>(strResposta);
                    UserDialogs.Instance.HideLoading();
                    if (retGeocoder != null && retGeocoder.status == "OK")
                    {
                        var mapaPosicao = new Position(retGeocoder.results.First().geometry.location.lat, retGeocoder.results.First().geometry.location.lng);
                        _Mapa.MoveToRegion(MapSpan.FromCenterAndRadius(mapaPosicao, Distance.FromMiles(0.3)));
                        var pin = new Pin
                        {
                            Type = PinType.Place,
                            Position = mapaPosicao,
                            Label = "Partida",
                            Address = item.Text
                        };
                        _Mapa.Pins.Clear();
                        _Mapa.Pins.Add(pin);
                    }
                    else
                    {
                        await DisplayAlert("Falha", "Ocorreu uma falha, tente novamente.", "Entendi");
                    }
                }

            };
        }
    }
}

