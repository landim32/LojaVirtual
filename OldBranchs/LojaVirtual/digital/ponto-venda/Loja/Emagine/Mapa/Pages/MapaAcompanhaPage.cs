using System;
using System.Collections.Generic;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Mapa.Controls;
using Emagine.Mapa.Model;
using Plugin.Geolocator;
using Xamarin.Forms;
using Xamarin.Forms.Maps;
using System.Linq;

namespace Emagine.Frete.Pages
{
    public class MapaAcompanhaPage : ContentPage
    {
        private bool _executandoDuracao = false;
        private bool _duracaoVisivel = false;

        private StackLayout _mainLayout;
        private MapaRotaInfo _rotaAtual;
        private CustomMap _CustomMap;
        private Button _abrirRotaButton;
        private Label _distanciaLabel;
        private Label _tempoLabel;
        private Label _duracaoLabel;

        public int Duracao { get; set; }

        public bool DuracaoVisivel {
            get {
                return _duracaoVisivel;
            }
            set {
                _duracaoVisivel = value;
                atualizarTela();
            }
        }

        public MapaAcompanhaPage()
        {
            inicializarComponente();
            Title = "Acompanhar entrega";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            _mainLayout = new StackLayout()
            {
                Orientation = StackOrientation.Vertical,
                Margin = 0,
                Padding = 0,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill
            };
            atualizarTela();

            Content = _mainLayout;
        }

        private Frame gerarPainel() {
            var bodyLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand
            };
            var painel = new Frame
            {
                Style = Estilo.Current[Estilo.ENDERECO_FRAME],
                Margin = new Thickness(4, 1, 4, 0),
                Padding = new Thickness(6, 4),
                Content = bodyLayout
            };
            bodyLayout.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    new Label {
                        VerticalOptions = LayoutOptions.Fill,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                        HorizontalTextAlignment = TextAlignment.End,
                        VerticalTextAlignment = TextAlignment.End,
                        Text = "Distância até o destino:"
                    },
                    _distanciaLabel
                }
            });
            bodyLayout.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    new Label {
                        VerticalOptions = LayoutOptions.Fill,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                        HorizontalTextAlignment = TextAlignment.End,
                        VerticalTextAlignment = TextAlignment.End,
                        Text = "Tempo até o destino:"
                    },
                    _tempoLabel
                }
            });
            if (_duracaoVisivel)
            {
                bodyLayout.Children.Add(new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.FillAndExpand,
                    Children = {
                        new Label {
                            VerticalOptions = LayoutOptions.Fill,
                            HorizontalOptions = LayoutOptions.FillAndExpand,
                            Style = Estilo.Current[Estilo.LABEL_CONTROL],
                            HorizontalTextAlignment = TextAlignment.End,
                            VerticalTextAlignment = TextAlignment.End,
                            Text = "Duração:"
                        },
                        _duracaoLabel
                    }
                });
            }
            return painel;
        }

        private void atualizarTela() {
            _mainLayout.Children.Clear();
            _mainLayout.Children.Add(_CustomMap);
            _mainLayout.Children.Add(gerarPainel());
            _mainLayout.Children.Add(_abrirRotaButton);
        }

        public void atualizarMapa(MapaRotaInfo rota)
        {
            _rotaAtual = rota;
            _CustomMap.Polyline = rota.Polyline;
            var posicaoAtual = new Position(rota.PosicaoAtual.Latitude, rota.PosicaoAtual.Longitude);
            _CustomMap.MoveToRegion(MapSpan.FromCenterAndRadius(posicaoAtual, Distance.FromMiles(0.1)));
            _distanciaLabel.Text = rota.DistanciaStr;
            if (_duracaoVisivel)
            {
                int duracao = rota.Tempo - Duracao;
                if (duracao > 0)
                {
                    _tempoLabel.Text = TimeSpan.FromSeconds(duracao).ToString();
                }
                else {
                    _tempoLabel.Text = rota.TempoStr;
                }
            }
            else
            {
                _tempoLabel.Text = rota.TempoStr;
            }
        }

        private void inicializarComponente()
        {
            _abrirRotaButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(4, 1, 4, 2),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Ver rota externamente"
            };
            _abrirRotaButton.Clicked += async (sender, e) => {
                try
                {
                    if (_rotaAtual == null)
                    {
                        await DisplayAlert("Erro", "Destino não encontrado.", "Entendi");
                        return;
                    }
                    var opcao = await DisplayActionSheet("Enviar rota para", "Cancelar", "Fechar", "Waze", "Google Maps");
                    if (!string.IsNullOrEmpty(opcao)) {

                        var pos = (from p in _rotaAtual.Polyline select p).LastOrDefault();

                        string posicao = pos.Latitude.ToString() + "," + pos.Longitude.ToString();
                        switch (opcao)
                        {
                            case "Maps":
                                Device.OpenUri(new Uri("http://maps.google.com/maps?daddr=" + posicao));
                                break;
                            case "Waze":
                                Device.OpenUri(new Uri("waze://?q=" + posicao));
                                break;
                        }
                    }
                }
                catch (Exception erro) {
                    await DisplayAlert("Erro", erro.Message, "Entendi");
                }
            };

            _distanciaLabel = new Label()
            {
                VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Start,
                WidthRequest = 100,
                Style = Estilo.Current[Estilo.LABEL_CAMPO],
                HorizontalTextAlignment = TextAlignment.End,
                VerticalTextAlignment = TextAlignment.End,
            };
            _tempoLabel = new Label()
            {
                VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Start,
                WidthRequest = 100,
                Style = Estilo.Current[Estilo.LABEL_CAMPO],
                HorizontalTextAlignment = TextAlignment.End,
                VerticalTextAlignment = TextAlignment.End,
            };
            _duracaoLabel = new Label()
            {
                VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Start,
                WidthRequest = 100,
                Style = Estilo.Current[Estilo.LABEL_CAMPO],
                HorizontalTextAlignment = TextAlignment.End,
                VerticalTextAlignment = TextAlignment.End,
            };

            _CustomMap = new CustomMap
            {
                MapType = MapType.Street,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill,
                IsShowingUser = true
            };

        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            _executandoDuracao = true;
            Device.StartTimer(new TimeSpan(0, 0, 1), () => {
                if (_duracaoVisivel) {
                    _duracaoLabel.Text = TimeSpan.FromSeconds(Duracao).ToString();
                }
                Duracao++;
                return _executandoDuracao;
            });
        }

        protected override void OnDisappearing()
        {
            base.OnDisappearing();
            _executandoDuracao = false;
        }
    }
}

