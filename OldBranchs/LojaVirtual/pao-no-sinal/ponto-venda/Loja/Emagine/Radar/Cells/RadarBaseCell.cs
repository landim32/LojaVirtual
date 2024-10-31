using Radar.BLL;
using Radar.Factory;
using Radar.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Pages
{
    public abstract class RadarBaseCell : ViewCell
    {
        private Image _radarIcone;
        private Label _tituloLabel;
        private Label _velocidadeLabel;
        private Label _latitudeLabel;
        private Label _longitudeLabel;
        private Label _anguloLabel;
        private Label _enderecoLabel;

        public RadarBaseCell()
        {
            inicializarComponente();

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
                        WidthRequest = 50,
                        Content = new StackLayout()
                        {
                            VerticalOptions = LayoutOptions.Center,
                            HorizontalOptions = LayoutOptions.Center,
                            Orientation = StackOrientation.Vertical,
                            Children = {
                                _radarIcone
                            }
                        }
                    },
                    new Frame
                    {
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        Content = new StackLayout
                        {
                            Orientation = StackOrientation.Vertical,
                            HorizontalOptions = LayoutOptions.FillAndExpand,
                            VerticalOptions = LayoutOptions.Start,
                            Spacing = 1,
                            Children = {
                                _tituloLabel,
                                new BoxView {
                                    HeightRequest = 1,
                                    BackgroundColor = Color.FromHex(TemaInfo.DividerColor),
                                    HorizontalOptions = LayoutOptions.FillAndExpand,
                                    VerticalOptions = LayoutOptions.Center
                                },
                                _velocidadeLabel,
                                _latitudeLabel,
                                _longitudeLabel,
                                _anguloLabel,
                                _enderecoLabel
                            }
                        }
                    }
                }
            };
        }

        protected virtual void inicializarComponente()
        {
            _radarIcone = new Image()
            {
                //Source = ImageSource.FromFile("meusradares.png"),
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start
            };
            _radarIcone.SetBinding(Image.SourceProperty, new Binding("Imagem"));

            _tituloLabel = new Label()
            {
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 26,
                FontFamily = "Roboto-Condensed",
                TextColor = Color.FromHex(TemaInfo.PrimaryColor)
            };
            _tituloLabel.SetBinding(Label.TextProperty, new Binding("Titulo"));

            _velocidadeLabel = new Label()
            {
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 14,
                FontFamily = "Roboto-Condensed",
                TextColor = Color.FromHex(TemaInfo.PrimaryColor)
            };
            _velocidadeLabel.SetBinding(Label.TextProperty, new Binding("VelocidadeStr"));

            _latitudeLabel = new Label()
            {
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 14,
                FontFamily = "Roboto-Condensed",
                TextColor = Color.FromHex(TemaInfo.PrimaryColor)
            };
            _latitudeLabel.SetBinding(Label.TextProperty, new Binding("LatitudeText"));

            _longitudeLabel = new Label()
            {
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 14,
                FontFamily = "Roboto-Condensed",
                TextColor = Color.FromHex(TemaInfo.PrimaryColor)
            };
            _longitudeLabel.SetBinding(Label.TextProperty, new Binding("LongitudeText"));

            _anguloLabel = new Label()
            {
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 14,
                FontFamily = "Roboto-Condensed",
                TextColor = Color.FromHex(TemaInfo.PrimaryColor)
            };
            _anguloLabel.SetBinding(Label.TextProperty, new Binding("DirecaoText"));

            _enderecoLabel = new Label()
            {
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 16,
                FontFamily = "Roboto-Condensed",
            };
            _enderecoLabel.SetBinding(Label.TextProperty, new Binding("Endereco"));
        }
    }
}
