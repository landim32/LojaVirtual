using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Veiculo.Cells
{
    public class VeiculoCell: ViewCell
    {
        private const int FONTE_CARACTERISTICA = 11;

        private Image _FotoImage;
        private Label _TituloLabel;
        private Label _PromoLabel;
        private Label _ValorLabel;
        private Label _NomeLoja;
        private Grid _CaracteristicaGrid;
        private Label _MotorLabel;
        private Label _AssentoLabel;
        private Label _DirecaoLabel;
        private Label _ArCondicionadoLabel;
        private Label _CambioLabel;
        private Label _PortaLabel;
        private Label _AbsLabel;
        private Label _AirbagLabel;

        public VeiculoCell() {
            inicializarComponente();
            View = new Frame
            {
                //CornerRadius = 5,
                Padding = 2,
                Margin = new Thickness(2,2),
                BackgroundColor = Color.White,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Vertical,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    Children = {
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.Fill,
                            Children = {
                                _TituloLabel,
                                new StackLayout {
                                    Spacing = 3,
                                    Orientation = StackOrientation.Horizontal,
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.FillAndExpand,
                                    Children = {
                                        _TituloLabel,
                                        new Label {
                                            VerticalOptions = LayoutOptions.Start,
                                            HorizontalOptions = LayoutOptions.Start,
                                            Text = " ou similar",
                                            TextColor = Color.FromHex("#696969")
                                        }
                                    }
                                },
                                _PromoLabel
                            }
                        },
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.Fill,
                            Children = {
                                _FotoImage,
                                _CaracteristicaGrid
                            }
                        },
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.FillAndExpand,
                            Children = {
                                new StackLayout {
                                    Spacing = 0,
                                    Padding = new Thickness(0,0,10,0),
                                    Orientation = StackOrientation.Vertical,
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.FillAndExpand,
                                    Children = {
                                        new Label {
                                            Text = "Locadora",
                                            FontSize = 11,
                                            TextColor = Color.Gray,
                                            VerticalOptions = LayoutOptions.Start,
                                            HorizontalOptions = LayoutOptions.Start
                                        },
                                        _NomeLoja
                                    }
                                },
                                _ValorLabel
                            }
                        },
                    }
                }
            };
        }

        private StackLayout adicionarCaracteristica(string icone, View item) {
            return new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                Spacing = 3,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Children = {
                    new IconImage {
                        VerticalOptions = LayoutOptions.Center,
                        HorizontalOptions = LayoutOptions.Start,
                        Icon = icone,
                        IconSize = FONTE_CARACTERISTICA
                    },
                    item
                }
            };
        }

        private void inicilizarCaracteristica() {
            _MotorLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                FontSize = FONTE_CARACTERISTICA
            };
            _MotorLabel.SetBinding(Label.TextProperty, new Binding("Motor"));
            _AssentoLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                FontSize = FONTE_CARACTERISTICA
            };
            _AssentoLabel.SetBinding(Label.TextProperty, new Binding("Assento"));
            _DirecaoLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                FontSize = FONTE_CARACTERISTICA
            };
            _DirecaoLabel.SetBinding(Label.TextProperty, new Binding("Direcao"));
            _ArCondicionadoLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                FontSize = FONTE_CARACTERISTICA
            };
            _ArCondicionadoLabel.SetBinding(Label.TextProperty, new Binding("ArCondicionado"));
            _CambioLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                FontSize = FONTE_CARACTERISTICA
            };
            _CambioLabel.SetBinding(Label.TextProperty, new Binding("Cambio"));
            _PortaLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                FontSize = FONTE_CARACTERISTICA
            };
            _PortaLabel.SetBinding(Label.TextProperty, new Binding("Porta"));
            _AbsLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                FontSize = FONTE_CARACTERISTICA
            };
            _AbsLabel.SetBinding(Label.TextProperty, new Binding("AbsStr"));
            _AirbagLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                FontSize = FONTE_CARACTERISTICA
            };
            _AirbagLabel.SetBinding(Label.TextProperty, new Binding("AirbagStr"));

            _CaracteristicaGrid = new Grid
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                RowSpacing = 0,
                ColumnSpacing = 0
            };
            _CaracteristicaGrid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _CaracteristicaGrid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _CaracteristicaGrid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _CaracteristicaGrid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _CaracteristicaGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _CaracteristicaGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });

            _CaracteristicaGrid.Children.Add(adicionarCaracteristica("fa-check-circle", _MotorLabel), 0, 0);
            _CaracteristicaGrid.Children.Add(adicionarCaracteristica("fa-user", _AssentoLabel), 1, 0);
            _CaracteristicaGrid.Children.Add(adicionarCaracteristica("fa-check-circle", _DirecaoLabel), 0, 1);
            _CaracteristicaGrid.Children.Add(adicionarCaracteristica("fa-check-circle", _ArCondicionadoLabel), 1, 1);
            _CaracteristicaGrid.Children.Add(adicionarCaracteristica("fa-check-circle", _CambioLabel), 0, 2);
            _CaracteristicaGrid.Children.Add(adicionarCaracteristica("fa-check-circle", _PortaLabel), 1, 2);
            _CaracteristicaGrid.Children.Add(adicionarCaracteristica("fa-check-circle", _AbsLabel), 0, 3);
            _CaracteristicaGrid.Children.Add(adicionarCaracteristica("fa-check-circle", _AirbagLabel), 1, 3);
        }

        private void inicializarComponente() {

            inicilizarCaracteristica();

            _FotoImage = new Image
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                WidthRequest = 120,
                HeightRequest = 80,
                Aspect = Aspect.Fill
            };
            _FotoImage.SetBinding(Image.SourceProperty, new Binding("Foto"));
            _TituloLabel = new Label {
                FontAttributes = FontAttributes.Bold,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
            };
            _TituloLabel.SetBinding(Label.TextProperty, new Binding("Titulo"));
            _PromoLabel = new Label
            {
                WidthRequest = 150,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
            };
            _PromoLabel.SetBinding(Label.TextProperty, new Binding("Promocao"));
            _ValorLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.End,
                Margin = new Thickness(0, 0, 10, 0),
                FontSize = 24,
                FontAttributes = FontAttributes.Bold
            };
            _ValorLabel.SetBinding(Label.TextProperty, new Binding("ValorStr"));
            _NomeLoja = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold
            };
            _NomeLoja.SetBinding(Label.TextProperty, new Binding("NomeLoja"));
        }
    }
}
