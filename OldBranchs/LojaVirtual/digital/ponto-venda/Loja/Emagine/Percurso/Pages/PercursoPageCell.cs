using Radar.BLL;
using Radar.Controls;
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
    public class PercursoPageCell : ViewCell
    {
        Label _tempoTotalLabel;
        Label _tempoParadoLabel;
        Label _paradaLabel;
        Label _velocidadeMaximaLabel;
        Label _velocidadeMediaLabel;
        Label _radarLabel;
        Label _tituloLabel;
        Label _enderecoLabel;
        Label _distanciaLabel;

        public PercursoPageCell()
        {
            inicializarComponente();
            inicializarMenu();

            View = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 0, 0, 10),
                Children = {
                    new Frame
                    {
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Start,
                        Margin = new Thickness(0, 0, 0, 0),
                        WidthRequest = 40,
                        Content = new StackLayout()
                        {
                            VerticalOptions = LayoutOptions.Center,
                            HorizontalOptions = LayoutOptions.Center,
                            Orientation = StackOrientation.Vertical,
                            Children = {
                                new Image {
                                    Source = ImageSource.FromFile("percursos.png"),
                                    HorizontalOptions = LayoutOptions.Center,
                                    VerticalOptions = LayoutOptions.Start
                                },
                                _distanciaLabel
                            }
                        }
                    },
                    new Frame
                    {
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.StartAndExpand,
                        Content = new StackLayout
                        {
                            Orientation = StackOrientation.Vertical,
                            HorizontalOptions = LayoutOptions.StartAndExpand,
                            VerticalOptions = LayoutOptions.Start,
                            Spacing = 1,
                            Children = {
                                _tituloLabel,
                                new BoxView {
                                    HeightRequest = 1,
                                    BackgroundColor = Color.FromHex(TemaInfo.DividerColor),
                                    HorizontalOptions = LayoutOptions.StartAndExpand,
                                    VerticalOptions = LayoutOptions.Center
                                },
                                new WrapLayout {
                                    HorizontalOptions = LayoutOptions.StartAndExpand,
                                    VerticalOptions = LayoutOptions.CenterAndExpand,
                                    WidthRequest = 220,
                                    Spacing = 1,
                                    Children = {
                                        new Image {
                                            Source = ImageSource.FromFile("relogio_20x20_preto.png")
                                        },
                                        _tempoTotalLabel,
                                        new Image {
                                            Source = ImageSource.FromFile("ampulheta_20x20_preto.png")
                                        },
                                        _tempoParadoLabel,
                                        new Image {
                                            Source = ImageSource.FromFile("mao_20x20_preto.png")
                                        },
                                        _paradaLabel,
                                        new Image {
                                            Source = ImageSource.FromFile("velocimetro_20x20_preto.png")
                                        },
                                        _velocidadeMediaLabel,
                                        new Image {
                                            Source = ImageSource.FromFile("velocimetro_20x20_preto.png")
                                        },
                                        _velocidadeMaximaLabel,
                                        new Image {
                                            Source = ImageSource.FromFile("radar_20x20_preto.png")
                                        },
                                        _radarLabel
                                    }
                                }
                            }
                        }
                    }
                }
            };
        }

        private void inicializarMenu() {
            var excluiPercurso = new MenuItem
            {
                Text = "Excluir"
            };
            excluiPercurso.SetBinding(MenuItem.CommandParameterProperty, new Binding("."));
            excluiPercurso.Clicked += (sender, e) =>
            {
                PercursoInfo percurso = (PercursoInfo)((MenuItem)sender).BindingContext;
                PercursoBLL regraPercurso = PercursoFactory.create();
                regraPercurso.excluir(percurso.Id);

                ListView percursoListView = this.Parent as ListView;

                percursoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));

                var percursos = regraPercurso.listar();
                percursoListView.BindingContext = percursos;
                percursoListView.ItemTemplate = new DataTemplate(typeof(PercursoPageCell));
            };

            var simulaPercurso = new MenuItem
            {
                Text = "Simular"
            };

            simulaPercurso.SetBinding(MenuItem.CommandParameterProperty, new Binding("."));
            simulaPercurso.Clicked += (sender, e) =>
            {
                PercursoInfo percurso = (PercursoInfo)((MenuItem)sender).BindingContext;
                if (percurso != null)
                    GPSUtils.simularPercurso(percurso.Id);
                OnAppearing();
            };

            ContextActions.Add(simulaPercurso);
            ContextActions.Add(excluiPercurso);
        }

        private void inicializarComponente() {

            _tituloLabel = new Label()
            {
                HorizontalOptions = LayoutOptions.StartAndExpand,
                FontSize = 26,
                FontFamily = "Roboto-Condensed",
                TextColor = Color.FromHex(TemaInfo.PrimaryColor)
            };
            _tituloLabel.SetBinding(Label.TextProperty, new Binding("Titulo"));

            _enderecoLabel = new Label()
            {
                HorizontalOptions = LayoutOptions.StartAndExpand,
                FontSize = 16,
                FontFamily = "Roboto-Condensed",
            };
            _enderecoLabel.SetBinding(Label.TextProperty, new Binding("Endereco"));

            _tempoTotalLabel = new Label {
                HorizontalOptions = LayoutOptions.Start,
                FontSize = 14
            };
            _tempoTotalLabel.SetBinding(Label.TextProperty, new Binding("TempoGravacaoStr", stringFormat: "Tempo: {0}"));

            _tempoParadoLabel = new Label {
                HorizontalOptions = LayoutOptions.Start,
                FontSize = 14
            };
            _tempoParadoLabel.SetBinding(Label.TextProperty, new Binding("TempoParadoStr", stringFormat: "Parado: {0}"));

            _paradaLabel = new Label {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Center,
                FontSize = 14
            };
            _paradaLabel.SetBinding(Label.TextProperty, new Binding("QuantidadeParadaStr", stringFormat: "Paradas: {0}"));

            _velocidadeMaximaLabel = new Label {
                HorizontalOptions = LayoutOptions.Start,
                FontSize = 14
            };
            _velocidadeMaximaLabel.SetBinding(Label.TextProperty, new Binding("VelocidadeMaximaStr", stringFormat: "V Max: {0}"));

            _velocidadeMediaLabel = new Label {
                HorizontalOptions = LayoutOptions.Start,
                FontSize = 14
            };
            _velocidadeMediaLabel.SetBinding(Label.TextProperty, new Binding("VelocidadeMediaStr", stringFormat: "V Méd: {0}"));

            _radarLabel = new Label {
                HorizontalOptions = LayoutOptions.Start,
                FontSize = 14
            };
            _radarLabel.SetBinding(Label.TextProperty, new Binding("QuantidadeRadarStr", stringFormat: "Radares: {0}"));

            _distanciaLabel = new Label()
            {
                FontSize = 14,
                TextColor = Color.FromHex(TemaInfo.PrimaryColor),
                FontFamily = "Roboto-Condensed",
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start
            };
            _distanciaLabel.SetBinding(Label.TextProperty, new Binding("DistanciaTotalStr"));
        }
    }
}
