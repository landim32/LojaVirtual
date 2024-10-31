using Radar.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Pages
{
    public class PercursoResumoCell: ViewCell
    {
        /*
			resumoRadar.Add(new ResumoItemInfo() { Descricao = "Latitude", Valor = "-10.897765" });
			resumoRadar.Add(new ResumoItemInfo() { Descricao = "Longitude", Valor = "-15.447853" });
			resumoRadar.Add(new ResumoItemInfo() { Descricao = "Data", Valor = "10 / DEZ" });
			resumoRadar.Add(new ResumoItemInfo() { Descricao = "Velocidade", Valor = "40 Km/h" });
			resumoRadar.Add(new ResumoItemInfo() { Descricao = "Minha Velocidade", Valor = "60 Km/h" });
			
			ObservableCollection<ResumoItemInfo> resumoDespesas = new ObservableCollection<ResumoItemInfo>();

			resumoDespesas.Add(new ResumoItemInfo() { Descricao = "Latitude", Valor = "-10.897765" });
			resumoDespesas.Add(new ResumoItemInfo() { Descricao = "Longitude", Valor = "-15.447853" });
			resumoDespesas.Add(new ResumoItemInfo() { Descricao = "Data", Valor = "10 / DEZ" });
			resumoDespesas.Add(new ResumoItemInfo() { Descricao = "Valor", Valor = "120.00 R$" });
        */

        private View _painelImagem;
        private View _painelDados;

        private Image _ImagemImage;
        private Label _TituloLabel;
        private Label _LatitudeLabel;
        private Label _LongitudeLabel;
        private Label _DataLabel;
        private Label _ValorLabel;
        private Label _VelocidadeLabel;
        private Label _MinhaVelocidadeLabel;
        private Label _TempoLabel;
        private Label _DistanciaLabel;

        public PercursoResumoCell() {
            inicializarComponente();

            var mainLayout = new StackLayout
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Orientation = StackOrientation.Vertical,
                Children = {
                    _TituloLabel,
                    new BoxView {
                        BackgroundColor = Color.FromHex(TemaInfo.PrimaryColor),
                        HeightRequest = 1
                    },
                    _DataLabel,
                    _LatitudeLabel,
                    _LongitudeLabel,
                    _TempoLabel,
                    _DistanciaLabel,
                    _VelocidadeLabel,
                    _MinhaVelocidadeLabel
                }
            };

            /*
            if (resumo is PercursoRadarInfo) {
                //var radar = (PercursoRadarInfo)resumo;
                mainLayout.Children.Add(_VelocidadeLabel);
                mainLayout.Children.Add(_MinhaVelocidadeLabel);
            }
            */
            
            View = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(5, 5, 5, 5),
                Children = {
                    new Frame {
                        HorizontalOptions = LayoutOptions.Center,
                        VerticalOptions = LayoutOptions.Start,
                        WidthRequest = 80,
                        HeightRequest = 80,
                        Padding = new Thickness(10, 5, 10, 10),
                        Content = _ImagemImage
                    },
                    new Frame {
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.StartAndExpand,
                        Padding = new Thickness(10),
                        Content = new StackLayout {
                            Orientation = StackOrientation.Vertical,
                            Children = {
                                _TituloLabel,
                                new BoxView {
                                    BackgroundColor = Color.FromHex(TemaInfo.PrimaryColor),
                                    HeightRequest = 1
                                },
                                _DataLabel,
                                _LatitudeLabel,
                                _LongitudeLabel,
                                _TempoLabel,
                                _DistanciaLabel,
                                _VelocidadeLabel,
                                _MinhaVelocidadeLabel
                            }
                        }
                    },
                }
            };
        }

        private void inicializarComponente() {
            _ImagemImage = new Image()
            {
                HeightRequest = 60,
                WidthRequest = 60,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Center,
                Source = "ic_add_a_photo_48pt.png"
            };
            _ImagemImage.SetBinding(Image.SourceProperty, new Binding("Icone"));
            _TituloLabel = new Label
            {
                TextColor = Color.FromHex(TemaInfo.PrimaryColor),
                FontFamily = "Roboto-Condensed",
                FontSize = 20,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.StartAndExpand,
            };
            _TituloLabel.SetBinding(Label.TextProperty, new Binding("Descricao"));

            _LatitudeLabel = new Label {
                TextColor = Color.FromHex(TemaInfo.PrimaryText),
                FontFamily = "Roboto-Condensed",
                FontSize = 14,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.StartAndExpand
            };
            _LatitudeLabel.SetBinding(Label.TextProperty, new Binding("Latitude", stringFormat: "Latitude: {0}"));

            _LongitudeLabel = new Label {
                TextColor = Color.FromHex(TemaInfo.PrimaryText),
                FontFamily = "Roboto-Condensed",
                FontSize = 14,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.StartAndExpand
            };
            _LongitudeLabel.SetBinding(Label.TextProperty, new Binding("Longitude", stringFormat: "Longitude: {0}"));

            _DataLabel = new Label
            {
                TextColor = Color.FromHex(TemaInfo.PrimaryText),
                FontFamily = "Roboto-Condensed",
                FontSize = 14,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.StartAndExpand
            };
            _DataLabel.SetBinding(Label.TextProperty, new Binding("DataStr", stringFormat: "Data: {0}"));

            _ValorLabel = new Label
            {
                TextColor = Color.FromHex(TemaInfo.PrimaryText),
                FontFamily = "Roboto-Condensed",
                FontSize = 14,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.StartAndExpand
            };
            _ValorLabel.SetBinding(Label.TextProperty, new Binding("ValorStr"));

            _VelocidadeLabel = new Label {
                TextColor = Color.FromHex(TemaInfo.PrimaryText),
                FontFamily = "Roboto-Condensed",
                FontSize = 14,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.StartAndExpand
            };
            _VelocidadeLabel.SetBinding(Label.TextProperty, new Binding("VelocidadeStr", stringFormat: "Velocidade: {0}"));

            _MinhaVelocidadeLabel = new Label
            {
                TextColor = Color.FromHex(TemaInfo.PrimaryText),
                FontFamily = "Roboto-Condensed",
                FontSize = 14,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.StartAndExpand
            };
            _MinhaVelocidadeLabel.SetBinding(Label.TextProperty, new Binding("MinhaVelocidadeStr", stringFormat: "Minha Vel.: {0}"));

            _TempoLabel = new Label
            {
                TextColor = Color.FromHex(TemaInfo.PrimaryText),
                FontFamily = "Roboto-Condensed",
                FontSize = 14,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.StartAndExpand
            };
            _TempoLabel.SetBinding(Label.TextProperty, new Binding("TempoStr", stringFormat: "Tempo: {0}"));

            _DistanciaLabel = new Label
            {
                TextColor = Color.FromHex(TemaInfo.PrimaryText),
                FontFamily = "Roboto-Condensed",
                FontSize = 14,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.StartAndExpand
            };
            _DistanciaLabel.SetBinding(Label.TextProperty, new Binding("DistanciaStr", stringFormat: "Distância: {0}"));
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            var resumo = (PercursoResumoInfo)BindingContext;
            if (resumo is PercursoRadarInfo)
            {
                var radar = (PercursoRadarInfo)resumo;
                _VelocidadeLabel.IsVisible = true;
                _MinhaVelocidadeLabel.IsVisible = true;
                if (radar.MinhaVelocidade > radar.Velocidade)
                {
                    _MinhaVelocidadeLabel.TextColor = Color.Red;
                }
            }
            else {
                _VelocidadeLabel.IsVisible = false;
                _MinhaVelocidadeLabel.IsVisible = false;
            }
        }
    }
}
