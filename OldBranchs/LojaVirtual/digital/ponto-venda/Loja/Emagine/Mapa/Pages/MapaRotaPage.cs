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
using Emagine.GPS.Utils;
using FormsPlugin.Iconize;
using Emagine.Base.Controls;

namespace Emagine.Mapa.Pages
{
    public class MapaRotaPage : ContentPage
    {
        private bool _listaNaTela = false;
        private CustomMap _Mapa;
        private SearchBar _palavraChaveSearchBar;
        private ListView _resultadoBuscaListView;
        private AbsoluteLayout _mainLayout;
        private StackLayout _mapaLayout;
        private StackLayout _barraLateralLayout;
        private CircleIconButton _excluirPontoButton;
        private CircleIconButton _limparButton;
        private CircleIconButton _visualizarButton;
        private Button _confirmarButton;

        public event EventHandler<IList<Position>> AoSelecionar;
        public string TituloPadrao { get; set; }
        public bool IniciarEmPosicaoAtual { get; set; } = false;

        public IList<Position> Polyline {
            get {
                var polyline = new List<Position>();
                foreach (var ponto in _Mapa.Pins)
                {
                    polyline.Add(ponto.Position);
                }
                return polyline;
            }
        }

        public MapaRotaPage()
        {
            Title = "Selecione o local";
            inicializarComponente();
            Content = _mainLayout;
            _mainLayout.Children.Add(gerarTutorial());
        }

        private AbsoluteLayout gerarTutorial() {
            var tutorialLayout = new AbsoluteLayout {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                BackgroundColor = Color.Black,
                Opacity = 0.5
            };
            AbsoluteLayout.SetLayoutBounds(tutorialLayout, new Rectangle(0, 0, 1, 1));
            AbsoluteLayout.SetLayoutFlags(tutorialLayout, AbsoluteLayoutFlags.All);

            var mensagemLayout = new StackLayout {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    new Label {
                        Text = "Após marcar os pontos no mapa, clique em confirmar para continuar o cadastro do atendimento.",
                        HorizontalTextAlignment = TextAlignment.Center,
                        TextColor = Color.White
                    },
                    new IconImage {
                        Icon = "fa-arrow-down",
                        IconColor = Color.White,
                        IconSize = 20
                    }
                }
            };
            AbsoluteLayout.SetLayoutBounds(mensagemLayout, new Rectangle(0.5, 0.87, 0.5, 0.2));
            AbsoluteLayout.SetLayoutFlags(mensagemLayout, AbsoluteLayoutFlags.All);
            tutorialLayout.Children.Add(mensagemLayout);

            var barraLateralLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 20,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new Label {
                                Text = "Para apagar qualquer ponto da rota clique no X ao lado.",
                                HorizontalTextAlignment = TextAlignment.End,
                                TextColor = Color.White
                            },
                            new IconImage {
                                Icon = "fa-arrow-right",
                                IconColor = Color.White,
                                IconSize = 20
                            }
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new Label {
                                Text = "Você pode limpar toda a rota clicando na lixeira.",
                                HorizontalTextAlignment = TextAlignment.End,
                                TextColor = Color.White
                            },
                            new IconImage {
                                Icon = "fa-arrow-right",
                                IconColor = Color.White,
                                IconSize = 20
                            }
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new Label {
                                Text = "Ao clicar no olho, você poderá visualizar toda a rota.",
                                HorizontalTextAlignment = TextAlignment.End,
                                TextColor = Color.White
                            },
                            new IconImage {
                                Icon = "fa-arrow-right",
                                IconColor = Color.White,
                                IconSize = 20
                            }
                        }
                    }
                }
            };
            AbsoluteLayout.SetLayoutBounds(barraLateralLayout, new Rectangle(0.5, 0.6, 0.6, 0.4));
            AbsoluteLayout.SetLayoutFlags(barraLateralLayout, AbsoluteLayoutFlags.All);
            tutorialLayout.Children.Add(barraLateralLayout);

            var tapGestureRecognizer = new TapGestureRecognizer();
            tapGestureRecognizer.Tapped += (s, e) => {
                _mainLayout.Children.Remove(tutorialLayout);
            };
            tutorialLayout.GestureRecognizers.Add(tapGestureRecognizer);

            return tutorialLayout;
        }

        private void fixarPontoNoMapa(Pin pin) {
            _Mapa.Pins.Add(pin);
            _Mapa.MoveToRegion(MapSpan.FromCenterAndRadius(pin.Position, Distance.FromMiles(0.5)));
            _Mapa.Polyline = Polyline;
        }

        private Pin gerarMapaLocal(double latitude, double longitude, string endereco) {
            var posicao = new Position(latitude, longitude);
            var pin = new Pin
            {
                Type = PinType.Place,
                Position = posicao,
                Address = !string.IsNullOrEmpty(endereco) ? endereco : TituloPadrao,
                Label = !string.IsNullOrEmpty(endereco) ? endereco : TituloPadrao
            };
            return pin;
        }

        private async Task<Pin> carregarLocalDaPosicao() {
            var posicao = await GPSUtils.Current.pegarUltimaPosicao();
            var endereco = await GPSUtils.Current.pegarEnderecoTextoPorPosicao(posicao);
            return gerarMapaLocal(posicao.Latitude, posicao.Longitude, endereco);
        }

        private async void atualizarPontoNoMapa() {
            UserDialogs.Instance.ShowLoading("Carregando...");
            try
            {
                var pin = await carregarLocalDaPosicao();
                fixarPontoNoMapa(pin);
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
            }
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            _palavraChaveSearchBar.Focus();
            if (IniciarEmPosicaoAtual) {
                if (!GPSUtils.UsaLocalizacao) {
                    return;
                }
                await GPSUtils.Current.inicializar();
                if (GPSUtils.Current.estaDisponivel()) {
                    atualizarPontoNoMapa();
                }
            }
        }

        private async Task buscarLocalporPalavraChave(string palavraChave)
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
                            new KeyValuePair<string, string>("input", palavraChave),
                            new KeyValuePair<string, string>("location", posicao.Latitude + "," + posicao.Longitude),
                            new KeyValuePair<string, string>("language", "pt_BR"),
                            new KeyValuePair<string, string>("key", MapaUtils.MAPA_API_KEY)
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
                    _resultadoBuscaListView.ItemsSource = lstRetorno;
                    if (!_listaNaTela)
                    {
                        _listaNaTela = true;
                        _mapaLayout.Children.Remove(_Mapa);
                        _mainLayout.Children.Remove(_barraLateralLayout);
                        _mainLayout.Children.Remove(_confirmarButton);
                        _mapaLayout.Children.Add((_resultadoBuscaListView));
                    }
                    UserDialogs.Instance.HideLoading();
                }
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
            }

        }

        private void inicializarComponente() {

            _mainLayout = new AbsoluteLayout
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand
            };

            _mapaLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Spacing = 0
            };
            AbsoluteLayout.SetLayoutBounds(_mapaLayout, new Rectangle(0, 0, 1, 1));
            AbsoluteLayout.SetLayoutFlags(_mapaLayout, AbsoluteLayoutFlags.All);
            _mainLayout.Children.Add(_mapaLayout);

            _barraLateralLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.End,
                VerticalOptions = LayoutOptions.Center,
                Spacing = 4,
                //BackgroundColor = Color.Green
            };
            AbsoluteLayout.SetLayoutBounds(_barraLateralLayout, new Rectangle(0.98, 0.5, 0.2, 0.6));
            AbsoluteLayout.SetLayoutFlags(_barraLateralLayout, AbsoluteLayoutFlags.All);
            _mainLayout.Children.Add(_barraLateralLayout);

            _palavraChaveSearchBar = new SearchBar()
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                BackgroundColor = Color.White,
                Placeholder = "Pesquisar...",
                FontSize = 20,
                HeightRequest = 42
            };
            _palavraChaveSearchBar.SearchButtonPressed += async (sender, e) => {
                await buscarLocalporPalavraChave(_palavraChaveSearchBar.Text);
            };

            _mapaLayout.Children.Add(_palavraChaveSearchBar);
            _mapaLayout.Children.Add(new BoxView
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                BackgroundColor = Estilo.Current.PrimaryColor,
                HeightRequest = 1
            });
                                
            _resultadoBuscaListView = new ListView {
                HasUnevenRows = true,
                RowHeight = -1,
                //SeparatorVisibility = SeparatorVisibility.None,
                SeparatorVisibility = SeparatorVisibility.Default,
                SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(ResultadoBuscaCell))
            };
            _resultadoBuscaListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _resultadoBuscaListView.ItemTapped += resultadoBuscaItemTapped;

            _Mapa = new CustomMap()
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                IsShowingUser = false
            };
            _Mapa.aoClicar += (sender, e) => {
                try
                {
                    fixarPontoNoMapa(new Pin
                    {
                        Type = PinType.Place,
                        Position = e.Position,
                        Label = TituloPadrao,
                        Address = TituloPadrao
                    });
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
                }
            };
            _mapaLayout.Children.Add(_Mapa);

            _excluirPontoButton = new CircleIconButton
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Center,
                BackgroundColor = Estilo.Current.DangerColor,
                TextColor = Color.White,
                WidthRequest = 50,
                HeightRequest = 50,
                FontSize = 22,
                BorderRadius = 50,
                Text = "fa-remove"
            };
            _excluirPontoButton.Clicked += (sender, e) => {
                try
                {
                    if (_Mapa.Pins.Count() > 0)
                    {
                        var pins = new List<Pin>();
                        foreach (var pin in _Mapa.Pins)
                        {
                            pins.Add(pin);
                        }
                        pins.RemoveAt(pins.Count() - 1);
                        if (pins.Count() > 0)
                        {
                            var pin = pins[pins.Count() - 1];
                            _Mapa.MoveToRegion(MapSpan.FromCenterAndRadius(pin.Position, Distance.FromMiles(0.5)));
                        }
                        _Mapa.Pins.Clear();
                        foreach (var pin in pins)
                        {
                            _Mapa.Pins.Add(pin);
                        }
                        _Mapa.Polyline = Polyline;
                    }
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
                }
            };
            _barraLateralLayout.Children.Add(_excluirPontoButton);

            _limparButton = new CircleIconButton
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Center,
                BackgroundColor = Estilo.Current.WarningColor,
                TextColor = Color.White,
                WidthRequest = 50,
                HeightRequest = 50,
                FontSize = 22,
                BorderRadius = 50,
                Text = "fa-trash"
            };
            _limparButton.Clicked += async (sender, e) => {
                if (await UserDialogs.Instance.ConfirmAsync("Deseja limpar a rota?", "Aviso", "Sim", "Não")) {
                    try {
                        _Mapa.Pins.Clear();
                        _Mapa.Polyline = Polyline;
                    }
                    catch (Exception erro) {
                        UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
                    }
                }
            };
            _barraLateralLayout.Children.Add(_limparButton);

            _visualizarButton = new CircleIconButton
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Center,
                BackgroundColor = Estilo.Current.SuccessColor,
                TextColor = Color.White,
                WidthRequest = 50,
                HeightRequest = 50,
                FontSize = 22,
                BorderRadius = 50,
                Text = "fa-eye"
            };
            _visualizarButton.Clicked += (sender, e) => {
                try
                {
                    _Mapa.zoomPolyline(true);
                }
                catch (Exception erro) {
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
                }
            };
            _barraLateralLayout.Children.Add(_visualizarButton);

            _confirmarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.End,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                BorderRadius = 45,
                Text = "Confirmar"
            };
            _confirmarButton.Pressed += (sender, e) => {
                if (_Mapa.Pins.Count() >= 2) {
                    AoSelecionar?.Invoke(this, Polyline);
                } else {
                    DisplayAlert("Atenção", "Você precisa selecionar pelomenos uma origem e um destino.", "Entendi");
                }

            };
            AbsoluteLayout.SetLayoutBounds(_confirmarButton, new Rectangle(0.5, 0.98, 0.5, 0.1));
            AbsoluteLayout.SetLayoutFlags(_confirmarButton, AbsoluteLayoutFlags.All);
            _mainLayout.Children.Add(_confirmarButton);
        }

        private async void resultadoBuscaItemTapped(object sender, ItemTappedEventArgs e)
        {
            if (e == null)
                return;
            var item = (PesquisaLocalizacaoInfo)((ListView)sender).SelectedItem;
            if (_listaNaTela)
            {
                _listaNaTela = false;
                _mapaLayout.Children.Remove(_resultadoBuscaListView);
                _mapaLayout.Children.Add(_Mapa);
                _mainLayout.Children.Add(_barraLateralLayout);
                _mainLayout.Children.Add(_confirmarButton);
            }
            UserDialogs.Instance.ShowLoading("Carregando...");
            try
            {
                using (var client = new HttpClient())
                {
                    HttpResponseMessage response = await client.GetAsync(HttpUtils.montaLinkGet(
                        "https://maps.googleapis.com/maps/api/geocode/json",
                        new List<KeyValuePair<string, string>>() {
                        new KeyValuePair<string, string>("address", item.Text),
                        new KeyValuePair<string, string>("language", "pt_BR"),
                        new KeyValuePair<string, string>("key", MapaUtils.MAPA_API_KEY)
                        }
                    ));
                    response.EnsureSuccessStatusCode();
                    var strResposta = await response.Content.ReadAsStringAsync();
                    var retGeocoder = JsonConvert.DeserializeObject<GoogleGeocodingInfo>(strResposta);

                    if (retGeocoder != null && retGeocoder.status == "OK")
                    {
                        var mapaPosicao = new Position(
                            retGeocoder.results.First().geometry.location.lat, 
                            retGeocoder.results.First().geometry.location.lng
                        );
                        UserDialogs.Instance.HideLoading();
                        _Mapa.MoveToRegion(MapSpan.FromCenterAndRadius(mapaPosicao, Distance.FromMiles(0.5)));
                    }
                    else
                    {
                        UserDialogs.Instance.HideLoading();
                        await DisplayAlert("Erro", "Ocorreu uma falha, tente novamente.", "Entendi");
                    }
                }
            }
            catch (Exception erro) {
                UserDialogs.Instance.HideLoading();
                await DisplayAlert("Erro", erro.Message, "Entendi");
            }
        }
    }
}

