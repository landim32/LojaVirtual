using Emagine.Utils;
using Radar.BLL;
using Radar.Controls;
using Radar.Estilo;
using Radar.Factory;
using Radar.Model;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Pages
{
    public class PercursoPage: ContentPage
    {
        StackLayout _RootLayout;
        ListView _PercursoListView;

        //WrapLayout _Descricao;

        Label _tempoCorrendo;
        Label _tempoParado;
        Label _paradas;
        Label _velocidadeMaxima;
        Label _velocidadeMedia;
        Label _radares;

        View _GravarButton;
        View _PararButton;

        public PercursoPage()
        {
            Title = "Percusos";
            inicializarComponente();

            _RootLayout = new StackLayout {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                Children = {
                    _PercursoListView,
                    (!PercursoUtils.Gravando) ? _GravarButton : _PararButton
                }
            };
            Content = _RootLayout;
        }

        public void atualizarGravacao(LocalizacaoInfo local, bool alterado) {
            var percurso = PercursoUtils.PercursoAtual;
            if (percurso != null)
            {
                TimeSpan tempo = TimeSpan.Zero;
                if (percurso.Pontos.Count > 0) {
                    tempo = local.Tempo.Subtract(percurso.Pontos[0].Data);
                }

                _tempoCorrendo.Text = "Tempo: " + tempo.ToString(@"hh\:mm\:ss");
                if (alterado)
                {
                    _tempoParado.Text = "Parado: " + percurso.TempoParadoStr;
                    _paradas.Text = "Paradas: " + percurso.QuantidadeParadaStr;
                    _velocidadeMedia.Text = "V Méd: " + percurso.VelocidadeMediaStr;
                    _velocidadeMaxima.Text = "V Max: " + percurso.VelocidadeMaximaStr;
                    _radares.Text = "Radares: " + percurso.QuantidadeRadarStr;
                }
            }
        }

        private View criarPararButton() {
            var gridLayout = new Grid {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.End
            };
            var imagemStop = new Image
            {
                Source = ImageSource.FromFile("Stop.png"),
                Style = EstiloUtils.Percurso.GravarImagem
            };
            var tituloLabel = new Label
            {
                Text = "Parar Percurso!",
                Style = EstiloUtils.Percurso.GravarTitulo
            };
            var descricaoLayout = new WrapLayout
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                //WidthRequest = TelaUtils.LarguraSemPixel * 0.7,
                Spacing = 1,
                Children = {
                    new Image
                    {
                        Source = ImageSource.FromFile("relogio_20x20_preto.png")
                    },
                    _tempoCorrendo,
                    new Image {
                        Source = ImageSource.FromFile("ampulheta_20x20_preto.png")
                    },
                    _tempoParado,
                    new Image {
                        Source = ImageSource.FromFile("mao_20x20_preto.png")
                    },
                    _paradas,
                    new Image {
                        Source = ImageSource.FromFile("velocimetro_20x20_preto.png")
                    },
                    _velocidadeMedia,
                    new Image {
                        Source = ImageSource.FromFile("velocimetro_20x20_preto.png")
                    },
                    _velocidadeMaxima,
                    new Image {
                        Source = ImageSource.FromFile("radar_20x20_preto.png")
                    },
                    _radares
                }
            };
            //gridLayout.Padding = new Thickness(30, 30, 30, 40);
            gridLayout.Padding = new Thickness(10, 10, 10, 20);
            gridLayout.HeightRequest = 150;
            gridLayout.ColumnDefinitions.Add(new ColumnDefinition() { Width = new GridLength(0.25, GridUnitType.Star) });
            gridLayout.ColumnDefinitions.Add(new ColumnDefinition() { Width = new GridLength(0.75, GridUnitType.Star) });
            gridLayout.Children.Add(imagemStop, 0, 0);
            gridLayout.Children.Add(tituloLabel, 1, 0);
            gridLayout.Children.Add(descricaoLayout, 1, 1);
            Grid.SetRowSpan(imagemStop, 2);
            gridLayout.GestureRecognizers.Add(new TapGestureRecognizer()
            {
                Command = new Command(() => {
                    pararPercurso();
                })
            });
            return gridLayout;
        }

        private View criarGravarButton() {
            var stackLayout = new StackLayout
            {
                Style = EstiloUtils.Percurso.GravarStackLayoutMain,
                Children = {
                    new Image {
                        Source = ImageSource.FromFile("Play.png"),
                        Style = EstiloUtils.Percurso.GravarImagem
                    },
                    new StackLayout {
                        Style = EstiloUtils.Percurso.GravarStackLayoutInterno,
                        Children = {
                            new Label {
                                Text = "Gravar Percurso!",
                                Style = EstiloUtils.Percurso.GravarTitulo
                            },
                            new Label {
                                Text="Toque aqui para gravar percurso",
                                Style = EstiloUtils.Percurso.GravarDescricao
                            }
                        }
                    }
                }
            };

            stackLayout.GestureRecognizers.Add(new TapGestureRecognizer()
            {
                Command = new Command(() => {
                    gravarPercurso();
                })
            });
            return stackLayout;
        }

        private void inicializarComponente()
        {
            _PercursoListView = new ListView {
                RowHeight = -1,
                HasUnevenRows = true,
                ItemTemplate = new DataTemplate(typeof(PercursoPageCell))
            };
            _PercursoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _PercursoListView.ItemTapped += (sender, e) =>
            {
                var percurso = (PercursoInfo)e.Item;
                NavigationX.create(this).PushAsync(new PercursoResumoPage(percurso));
            };

            _tempoCorrendo = new Label {
                HorizontalOptions = LayoutOptions.Start,
                FontSize = 14,
                Text = "Tempo: 00:00:00"
            };
            //_tempoCorrendo.SetBinding(Label.TextProperty, new Binding("TempoGravacaoStr"));

            _tempoParado = new Label{
                HorizontalOptions = LayoutOptions.Start,
                FontSize = 14,
                Text = "Parado: 00:00:00"
            };
            //_tempoParado.SetBinding(Label.TextProperty, new Binding("TempoParadoStr"));

            _paradas = new Label {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Center,
                Text = "Paradas: 0"
            };

            _velocidadeMaxima = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                Text = "V Méd: 0 Km/h"
            };

            _velocidadeMedia = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                Text = "V Max: 0 Km/h"
            };

            _radares = new Label {
                HorizontalOptions = LayoutOptions.Start,
                Text = "Radares: 0"
            };

            _GravarButton = criarGravarButton();
            _PararButton = criarPararButton();
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            PercursoBLL regraPercurso = PercursoFactory.create();
            var percursos = regraPercurso.listar();
            this.BindingContext = percursos;
            PercursoUtils.PaginaAtual = this;
        }

        protected override void OnDisappearing()
        {
            base.OnDisappearing();
            PercursoUtils.PaginaAtual = null;
        }

        private void gravarPercurso()
        {
            PercursoBLL regraPercurso = PercursoFactory.create();
            if (regraPercurso.iniciarGravacao()) {
                _RootLayout.Children.Remove(_GravarButton);
                _RootLayout.Children.Add(_PararButton);
            }
            else {
                MensagemUtils.avisar("Não foi possível iniciar a gravação!");
            }
        }

        private async void pararPercurso()
        {
            var retorno = await DisplayActionSheet("Tem certeza que deseja parar a gravação?", null, null, "Parar", "Continuar gravando");
            if (retorno == "Parar")
            {
                PercursoBLL regraPercurso = PercursoFactory.create();
                if (regraPercurso.pararGravacao())
                {
                    _RootLayout.Children.Remove(_PararButton);
                    _RootLayout.Children.Add(_GravarButton);

                    var percursos = regraPercurso.listar();
                    _PercursoListView.BindingContext = percursos;
                }
                else {
                    MensagemUtils.avisar("Não foi possível parar a gravação!");
                }
            }
        }
    }
}
