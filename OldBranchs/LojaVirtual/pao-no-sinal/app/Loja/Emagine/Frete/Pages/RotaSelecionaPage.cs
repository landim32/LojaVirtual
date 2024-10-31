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
using Emagine.Login.Factory;
using Emagine.Mapa.Factory;
using Emagine.Frete.Model;
using Emagine.Frete.Factory;
using Emagine.Endereco.Model;

namespace Emagine.Frete.Pages
{
    public class RotaSelecionaPage : ContentPage
    {
        private MapaAutoCompleteEnum _listaNaTela = MapaAutoCompleteEnum.Nenhum;
        private CustomMap _mapa;
        private SearchBar _origemSearchBar;
        private SearchBar _destinoSearchBar;
        private ListView _resultadoBuscaListView;
        private AbsoluteLayout _mainLayout;
        private StackLayout _mapaLayout;
        private StackLayout _barraLateralLayout;
        private Frame _rodapeFrame;
        private Label _tempoLabel;
        private Label _distanciaLabel;
        private Label _precoLabel;
        //private CircleIconButton _excluirPontoButton;
        private CircleIconButton _limparButton;
        private CircleIconButton _visualizarButton;
        private Button _agendarButton;
        private Button _solicitarButton;

        private FreteInfo _frete;
        private Pin _origem = null;
        private Pin _destino = null;

        public event EventHandler<FreteInfo> AoSolicitar;
        public event EventHandler<FreteInfo> AoAgendar;
        public bool IniciarEmPosicaoAtual { get; set; } = false;

        public FreteInfo Frete {
            get {
                if (_frete == null) {
                    _frete = new FreteInfo();
                }
                return _frete;
            }
            set {
                _frete = value;
                if (_frete != null) {
                    if (_frete.TemOrigem()) {
                        _origemSearchBar.Text = _frete.EnderecoOrigem;
                    }
                    if (_frete.TemDestino()) {
                        _destinoSearchBar.Text = _frete.EnderecoDestino;
                    }
                }
            }
        }

        public IList<Position> Polyline {
            get {
                var polyline = new List<Position>();
                foreach (var ponto in _mapa.Pins)
                {
                    polyline.Add(ponto.Position);
                }
                return polyline;
            }
        }

        public RotaSelecionaPage()
        {
            Title = "Selecione a rota";
            inicializarComponente();
            inicializarRodape();
            Content = _mainLayout;
            //_mainLayout.Children.Add(gerarTutorial());
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

        private string converterEnderecoParaTexto(EnderecoInfo endereco) {
            var retorno = new List<string>();
            if (!string.IsNullOrEmpty(endereco.Logradouro)) {
                retorno.Add(endereco.Logradouro);
            }
            /*
            if (!string.IsNullOrEmpty(endereco.Bairro)) {
                retorno.Add(endereco.Bairro);
            }
            */
            if (!string.IsNullOrEmpty(endereco.Cidade)) {
                retorno.Add(endereco.Cidade);
            }
            /*
            if (!string.IsNullOrEmpty(endereco.Uf)) {
                retorno.Add(endereco.Uf);
            }
            */
            return string.Join(", ", retorno);
        }

        private async void definirOrigem(FreteLocalInfo local, string palavraChave = "") {
            Frete.LocalOrigem = local;
            var posicao = new Position(local.Latitude.GetValueOrDefault(), local.Longitude.GetValueOrDefault());
            if (string.IsNullOrEmpty(palavraChave))
            {
                var endereco = await GPSUtils.Current.pegarEnderecoPorPosicao(posicao.Latitude, posicao.Longitude);
                palavraChave = converterEnderecoParaTexto(endereco);
                Frete.EnderecoOrigem = palavraChave;
            }
            _origemSearchBar.Text = palavraChave;
            /*
            if (_origem != null) {
                _mapa.Pins.Remove(_origem);
            }
            */
            _mapa.Pins.Clear();
            _origem = new Pin
            {
                Type = PinType.Place,
                Position = posicao,
                Address = palavraChave,
                Label = "Origem"
            };
            _mapa.Pins.Add(_origem);
            if (Frete.TemDestino()) {
                var localDestino = Frete.LocalDestino;
                if (_destino != null) {
                    _mapa.Pins.Add(_destino);
                }
                var regraFrete = FreteFactory.create();
                var frete = await regraFrete.orcar(Frete);
                Frete.Preco = frete.Preco;
                _tempoLabel.Text = frete.TempoStr;
                _distanciaLabel.Text = frete.DistanciaStr;
                _precoLabel.Text = "R$" + frete.Preco.ToString("N2");
                _mapa.Polyline = MapaUtils.decodePolyline(frete.Polyline);
                //_mapa.zoomPolyline(true);
                _mapa.MoveToRegion(MapSpan.FromCenterAndRadius(posicao, Distance.FromMiles(0.5)));
                if (!_mainLayout.Children.Contains(_rodapeFrame)) {
                    _mainLayout.Children.Add(_rodapeFrame);
                }
            }
            else {
                _mapa.MoveToRegion(MapSpan.FromCenterAndRadius(posicao, Distance.FromMiles(0.5)));
            }
        }

        private async void definirDestino(FreteLocalInfo local, string palavraChave = "")
        {
            Frete.LocalDestino = local;
            var posicao = new Position(local.Latitude.GetValueOrDefault(), local.Longitude.GetValueOrDefault());
            if (string.IsNullOrEmpty(palavraChave))
            {
                var endereco = await GPSUtils.Current.pegarEnderecoPorPosicao(posicao.Latitude, posicao.Longitude);
                palavraChave = converterEnderecoParaTexto(endereco);
                Frete.EnderecoDestino = palavraChave;
            }
            _destinoSearchBar.Text = palavraChave;
            if (_destino != null) {
                _mapa.Pins.Clear();
            }
            _destino = new Pin
            {
                Type = PinType.Place,
                Position = posicao,
                Address = palavraChave,
                Label = "Destino"
            };
            _mapa.Pins.Add(_destino);
            if (Frete.TemOrigem())
            {
                var localOrigem = Frete.LocalOrigem;
                if (_origem != null) {
                    _mapa.Pins.Add(_origem);
                }
                var regraFrete = FreteFactory.create();
                var frete = await regraFrete.orcar(Frete);
                Frete.Preco = frete.Preco;
                _tempoLabel.Text = frete.TempoStr;
                _distanciaLabel.Text = frete.DistanciaStr;
                _precoLabel.Text = "R$" + frete.Preco.ToString("N2");
                _mapa.Polyline = MapaUtils.decodePolyline(frete.Polyline);
                //_mapa.zoomPolyline(false);
                _mapa.MoveToRegion(MapSpan.FromCenterAndRadius(posicao, Distance.FromMiles(0.5)));
                if (!_mainLayout.Children.Contains(_rodapeFrame)) {
                    _mainLayout.Children.Add(_rodapeFrame);
                }
            }
            else
            {
                _mapa.MoveToRegion(MapSpan.FromCenterAndRadius(posicao, Distance.FromMiles(0.5)));
            }
        }

        /*
        private void fixarPontoNoMapa(Pin pin) {
            _mapa.Pins.Add(pin);
            _mapa.MoveToRegion(MapSpan.FromCenterAndRadius(pin.Position, Distance.FromMiles(0.5)));
            _mapa.Polyline = Polyline;
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
        */

        /*
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
        */

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (IniciarEmPosicaoAtual) {
                if (!GPSUtils.UsaLocalizacao) {
                    return;
                }
                await GPSUtils.Current.inicializar();
                if (GPSUtils.Current.estaDisponivel()) {
                    UserDialogs.Instance.ShowLoading("Carregando...");
                    try
                    {
                        var posicao = await GPSUtils.Current.pegarUltimaPosicao();
                        var origem = new FreteLocalInfo {
                            Tipo = FreteLocalTipoEnum.Origem,
                            Latitude = posicao.Latitude,
                            Longitude = posicao.Longitude
                        };
                        definirOrigem(origem);
                        /*
                        Frete.EnderecoOrigem = endereco;
                        _origemSearchBar.Text = endereco;
                        var pin = gerarMapaLocal(posicao.Latitude, posicao.Longitude, endereco);
                        fixarPontoNoMapa(pin);
                        */
                        UserDialogs.Instance.HideLoading();
                    }
                    catch (Exception erro)
                    {
                        UserDialogs.Instance.HideLoading();
                        await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                    }
                }
                _destinoSearchBar.Focus();
            }
            else {
                _origemSearchBar.Focus();
            }
        }

        private void inicializarRodape() {
            _tempoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                HorizontalTextAlignment = TextAlignment.Center,
                TextColor = Color.White,
                FontSize = 18,
                Text = "0s"
            };
            _distanciaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                HorizontalTextAlignment = TextAlignment.Center,
                TextColor = Color.White,
                FontSize = 18,
                Text = "0km"
            };
            _precoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                HorizontalTextAlignment = TextAlignment.Center,
                TextColor = Color.White,
                FontSize = 18,
                Text = "R$0,00"
            };

            _agendarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO],
                Text = "Agendar"
            };
            _agendarButton.Pressed += (sender, e) => {
                if (_mapa.Pins.Count() < 2)
                {
                    DisplayAlert("Atenção", "Você precisa selecionar pelomenos uma origem e um destino.", "Entendi");
                }
                AoAgendar?.Invoke(this, Frete);
            };
            _solicitarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Confirmar"
            };
            _solicitarButton.Pressed += (sender, e) => {
                if (_mapa.Pins.Count() < 2)
                {
                    DisplayAlert("Atenção", "Você precisa selecionar pelomenos uma origem e um destino.", "Entendi");
                }
                AoSolicitar?.Invoke(this, Frete);
            };

            var gridValorLayout = new Grid
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
            };
            gridValorLayout.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalTextAlignment = TextAlignment.Center,
                        FontAttributes = FontAttributes.Italic,
                        TextColor = Color.White,
                        FontSize = 10,
                        Text = "Duração"
                    },
                    _tempoLabel
                }
            }, 0, 0);
            gridValorLayout.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalTextAlignment = TextAlignment.Center,
                        FontAttributes = FontAttributes.Italic,
                        TextColor = Color.White,
                        FontSize = 10,
                        Text = "Distância"
                    },
                    _distanciaLabel
                }
            }, 1, 0);
            gridValorLayout.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalTextAlignment = TextAlignment.Center,
                        FontAttributes = FontAttributes.Italic,
                        TextColor = Color.White,
                        FontSize = 10,
                        Text = "Preço"
                    },
                    _precoLabel
                }
            }, 2, 0);
            var gridBotaoLayout = new Grid
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                ColumnSpacing = 15
            };
            gridBotaoLayout.Children.Add(_agendarButton, 0, 0);
            gridBotaoLayout.Children.Add(_solicitarButton, 1, 0);
            //gridValorLayout.Children.Add(_confirmarButton, 0, 1);
            //Grid.SetColumnSpan(_confirmarButton, 3);
            _rodapeFrame = new Frame
            {
                //Opacity = 0.7,
                Padding = new Thickness(10, 7),
                BackgroundColor = Color.FromRgba(0, 0, 0, 120),
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.End,
                //Style = Estilo.Current[Estilo.FRAME_DANGER],
                Content = new StackLayout {
                    Orientation = StackOrientation.Vertical,
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Children = {
                        gridValorLayout,
                        gridBotaoLayout
                    }
                }
            };
            AbsoluteLayout.SetLayoutBounds(_rodapeFrame, new Rectangle(0.5, 1, 0.9, 0.25));
            AbsoluteLayout.SetLayoutFlags(_rodapeFrame, AbsoluteLayoutFlags.All);
            //_mainLayout.Children.Add(_rodapeFrame);
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

            _origemSearchBar = new SearchBar()
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                BackgroundColor = Color.White,
                Placeholder = "Local de Partida",
                FontSize = 16,
                HeightRequest = 40
            };
            _origemSearchBar.SearchButtonPressed += async (sender, e) => {
                UserDialogs.Instance.ShowLoading("Pesquisando...");
                try
                {
                    var posicao = await CrossGeolocator.Current.GetLastKnownLocationAsync();
                    var local = new Mapa.Model.LocalInfo(posicao.Latitude, posicao.Longitude);
                    var regraBusca = MapaBuscaFactory.create();
                    var palavras = await regraBusca.listarAutoCompletarPorPalavraChave(_origemSearchBar.Text, local);
                    _resultadoBuscaListView.ItemsSource = palavras;
                    if (_listaNaTela == MapaAutoCompleteEnum.Nenhum)
                    {
                        _listaNaTela = MapaAutoCompleteEnum.Origem;
                        _mapaLayout.Children.Remove(_destinoSearchBar);
                        _mapaLayout.Children.Remove(_mapa);
                        _mainLayout.Children.Remove(_barraLateralLayout);
                        //_mainLayout.Children.Remove(_confirmarButton);
                        //_mainLayout.Children.Remove(_rodapeFrame);
                        _mapaLayout.Children.Add((_resultadoBuscaListView));
                    }
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
            };
            _origemSearchBar.TextChanged += (sender, e) => {
                if (e.NewTextValue == string.Empty) {
                    if (_mainLayout.Children.Contains(_rodapeFrame)) {
                        _mainLayout.Children.Remove(_rodapeFrame);
                    }
                    if (_origem != null) {
                        if (Frete.TemOrigem()) {
                            var origem = Frete.LocalOrigem;
                            Frete.Locais.Remove(origem);
                        }
                        _mapa.Pins.Clear();
                        //_mapa.Pins.Remove(_origem);
                        _origem = null;
                        _mapa.resetarPolyline();
                        if (_destino != null) {
                            _mapa.Pins.Add(_destino);
                            _mapa.MoveToRegion(MapSpan.FromCenterAndRadius(_destino.Position, Distance.FromMiles(0.5)));
                        }
                    }
                }
            };
            _mapaLayout.Children.Add(_origemSearchBar);

            _destinoSearchBar = new SearchBar()
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                BackgroundColor = Color.White,
                Placeholder = "Para onde?",
                FontSize = 16,
                HeightRequest = 40
            };
            _destinoSearchBar.SearchButtonPressed += async (sender, e) => {
                UserDialogs.Instance.ShowLoading("Pesquisando...");
                try
                {
                    var posicao = await CrossGeolocator.Current.GetLastKnownLocationAsync();
                    var local = new Mapa.Model.LocalInfo(posicao.Latitude, posicao.Longitude);
                    var regraBusca = MapaBuscaFactory.create();
                    var palavras = await regraBusca.listarAutoCompletarPorPalavraChave(_destinoSearchBar.Text, local);
                    _resultadoBuscaListView.ItemsSource = palavras;
                    if (_listaNaTela == MapaAutoCompleteEnum.Nenhum)
                    {
                        _listaNaTela = MapaAutoCompleteEnum.Destino;
                        _mapaLayout.Children.Remove(_mapa);
                        _mainLayout.Children.Remove(_barraLateralLayout);
                        //_mainLayout.Children.Remove(_confirmarButton);
                        //_mainLayout.Children.Remove(_rodapeFrame);
                        _mapaLayout.Children.Add((_resultadoBuscaListView));
                    }
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
            };
            _destinoSearchBar.TextChanged += (sender, e) => {
                if (e.NewTextValue == string.Empty) {
                    if (_mainLayout.Children.Contains(_rodapeFrame)) {
                        _mainLayout.Children.Remove(_rodapeFrame);
                    }
                    if (_destino != null) {
                        if (Frete.TemDestino()) {
                            var destino = Frete.LocalDestino;
                            Frete.Locais.Remove(destino);
                        }
                        //_mapa.Pins.Remove(_destino);
                        _mapa.Pins.Clear();
                        _destino = null;
                        _mapa.resetarPolyline();
                        if (_origem != null) {
                            _mapa.Pins.Add(_origem);
                            _mapa.MoveToRegion(MapSpan.FromCenterAndRadius(_origem.Position, Distance.FromMiles(0.5)));
                        }
                    }
                }
            };
            _mapaLayout.Children.Add(_destinoSearchBar);


            _mapaLayout.Children.Add(new BoxView
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                BackgroundColor = Estilo.Current.PrimaryColor,
                HeightRequest = 1
            });

            //var itemTemplate = new DataTemplate(typeof(TextCell));
            //itemTemplate.SetBinding(TextCell.TextProperty, new Binding("."));

            _resultadoBuscaListView = new ListView {
                HasUnevenRows = true,
                RowHeight = -1,
                //SeparatorVisibility = SeparatorVisibility.None,
                SeparatorVisibility = SeparatorVisibility.Default,
                SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(ResultadoBuscaCell))
                //ItemTemplate = new DataTemplate(typeof(TextCell))
                //ItemTemplate = itemTemplate
            };
            _resultadoBuscaListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _resultadoBuscaListView.ItemTapped += resultadoBuscaItemTapped;

            _mapa = new CustomMap()
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                IsShowingUser = false
            };
            _mapa.aoClicar += async (sender, e) => {
                UserDialogs.Instance.ShowLoading("Atualizando...");
                try
                {
                    var p = new Plugin.Geolocator.Abstractions.Position(e.Position.Latitude, e.Position.Longitude);
                    var endereco = await GPSUtils.Current.pegarEnderecoPorPosicao(p);
                    var enderecoStr = converterEnderecoParaTexto(endereco);
                    var local = new FreteLocalInfo
                    {
                        Latitude = p.Latitude,
                        Longitude = p.Longitude
                    };
                    if (Frete.TemOrigem()) {
                        local.Tipo = FreteLocalTipoEnum.Destino;
                        definirDestino(local, enderecoStr);
                    }
                    else {
                        local.Tipo = FreteLocalTipoEnum.Origem;
                        definirOrigem(local, enderecoStr);
                    }
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
            };
            _mapaLayout.Children.Add(_mapa);

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
                        _mapa.Pins.Clear();
                        _mapa.Polyline = Polyline;
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
                    //_mapa.zoomPolyline(true);
                    _mapa.zoomPolyline(false);
                }
                catch (Exception erro) {
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
                }
            };
            _barraLateralLayout.Children.Add(_visualizarButton);
        }

        private async void resultadoBuscaItemTapped(object sender, ItemTappedEventArgs e)
        {
            if (e == null)
                return;
            var palavraChave = (string)((ListView)sender).SelectedItem;
            if (_listaNaTela != MapaAutoCompleteEnum.Nenhum)
            {
                _mapaLayout.Children.Remove(_resultadoBuscaListView);
                if (_listaNaTela == MapaAutoCompleteEnum.Origem) {
                    _mapaLayout.Children.Add(_destinoSearchBar);
                }
                _mapaLayout.Children.Add(_mapa);
                _mainLayout.Children.Add(_barraLateralLayout);
                //_mainLayout.Children.Add(_confirmarButton);
                //_mainLayout.Children.Add(_rodapeFrame);
                _listaNaTela = MapaAutoCompleteEnum.Nenhum;
            }
            UserDialogs.Instance.ShowLoading("Carregando...");
            try
            {
                //var p = await CrossGeolocator.Current.GetLastKnownLocationAsync();
                var p = await GPSUtils.Current.pegarUltimaPosicao();
                var local = new Mapa.Model.LocalInfo(p.Latitude, p.Longitude);
                var regraBusca = MapaBuscaFactory.create();
                var posicao = await regraBusca.pegarPosicaoPorPalavraChave(palavraChave, local);
                if (posicao != null) {
                    if (Frete.TemOrigem())
                    {
                        var destino = new FreteLocalInfo
                        {
                            Tipo = FreteLocalTipoEnum.Destino,
                            Latitude = posicao.Latitude,
                            Longitude = posicao.Longitude
                        };
                        definirDestino(destino, palavraChave);
                    }
                    else {
                        var origem = new FreteLocalInfo
                        {
                            Tipo = FreteLocalTipoEnum.Origem,
                            Latitude = posicao.Latitude,
                            Longitude = posicao.Longitude
                        };
                        definirOrigem(origem, palavraChave);
                    }
                    /*
                    var mapaPosicao = new Position(posicao.Latitude, posicao.Longitude);
                    _mapa.MoveToRegion(MapSpan.FromCenterAndRadius(mapaPosicao, Distance.FromMiles(0.5)));
                    _mapa.Pins.Add(new Pin
                    {
                        Label = "Origem",
                        Position = mapaPosicao,
                        Address = palavraChave
                    });
                    */
                }
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro) {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
            }
        }
    }
}

