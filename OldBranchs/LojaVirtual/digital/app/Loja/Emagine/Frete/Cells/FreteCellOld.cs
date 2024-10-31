using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using FormsPlugin.Iconize;
using Xamarin.Forms;
using Emagine.Base.Estilo;

namespace Emagine.Frete.Cells
{
    public class FreteCellOld : ViewCell
    {
        private Label _OrigemLabel;
        private Label _DestinoLabel;
        private Label _PesoLabel;
        private Label _DimensaoLabel;
        private Label _ValorLabel;
        private Label _DistanciaLabel;
        private Label _SituacaoLabel;
        private MenuItem _excluirButton;

        public FreteCellOld()
        {
            inicializarComponente();

            View = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new IconImage{
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Icon = "fa-map-marker",
                                IconSize = 16,
                                WidthRequest = 20,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            _OrigemLabel
                        }
                    },
                    gerarAtributo(),
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new IconImage{
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Icon = "fa-arrows-alt",
                                IconSize = 16,
                                WidthRequest = 20,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            _DimensaoLabel
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new IconImage{
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Icon = "fa-shopping-bag",
                                IconSize = 16,
                                WidthRequest = 20,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            _SituacaoLabel
                        }
                    },
                    new BoxView{
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Color = Estilo.Current.SuccessColor,
                        HeightRequest = 1
                    }
                }
            };
        }

        private Grid gerarAtributo() {
            var grid = new Grid
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
            };
            grid.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5,
                Children = {
                    new IconImage{
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Icon = "fa-dollar",
                        IconSize = 16,
                        WidthRequest = 20,
                        IconColor = Estilo.Current.PrimaryColor
                    },
                    _ValorLabel
                }
            }, 0, 0);
            grid.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5,
                Children = {
                    new IconImage{
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Icon = "fa-map",
                        IconSize = 16,
                        WidthRequest = 20,
                        IconColor = Estilo.Current.PrimaryColor
                    },
                    _DistanciaLabel
                }
            }, 1, 0);
            grid.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5,
                Children = {
                    new IconImage{
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Icon = "fa-balance-scale",
                        IconSize = 16,
                        WidthRequest = 20,
                        IconColor = Estilo.Current.PrimaryColor
                    },
                    _PesoLabel
                }
            }, 2, 0);
            return grid;
        }

        private void inicializarComponente()
        {
            _OrigemLabel = new Label {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
            };
            _OrigemLabel.SetBinding(Label.TextProperty, new Binding("EnderecoChegada"));
            _PesoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _PesoLabel.SetBinding(Label.TextProperty, new Binding("Peso", stringFormat: "{0:N1}Kg"));
            _DimensaoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _DimensaoLabel.SetBinding(Label.TextProperty, new Binding("Dimensao"));
            _ValorLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _ValorLabel.SetBinding(Label.TextProperty, new Binding("Preco", stringFormat: "R$ {0:N2}"));
            _DistanciaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _DistanciaLabel.SetBinding(Label.TextProperty, new Binding("DistanciaStr", stringFormat: "{0:N2} km"));
            _SituacaoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _SituacaoLabel.SetBinding(Label.TextProperty, new Binding("SituacaoLbl"));
        }
    }
}
