using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using FormsPlugin.Iconize;
using Xamarin.Forms;
using Emagine.Base.Estilo;
using Emagine.Frete.Model;
using Emagine.Login.Factory;
using Emagine.Frete.Factory;

namespace Emagine.Frete.Cells
{
    public class FreteMotoristaCell : ViewCell
    {
        private StackLayout _mainLayout;
        private Label _OrigemLabel;
        //private Label _UsuarioLabel;
        //private Label _DestinoLabel;
        private Label _PesoLabel;
        private Label _DimensaoLabel;
        private Label _ValorLabel;
        private Label _DuracaoLabel;
        private Label _DistanciaLabel;
        private Label _SituacaoLabel;
        //private MenuItem _excluirButton;

        public FreteMotoristaCell()
        {
            inicializarComponente();

            View = new Frame
            {
                Style = Estilo.Current[Estilo.LISTA_FRAME_PADRAO],
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Content = _mainLayout
            };
        }

        private Grid gerarAtributo(FreteInfo frete) {
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
            if (frete != null && frete.Peso > 0) {
                grid.Children.Add(new StackLayout {
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
            }
            return grid;
        }

        protected override void OnBindingContextChanged()
        {
            base.OnBindingContextChanged();
            var frete = (FreteInfo)BindingContext;

            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();

            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

            var regraFrete = FreteFactory.create();
            var freteAtual = regraFrete.pegarAtual();

            _mainLayout.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5,
                Children = {
                    new IconImage{
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        //Icon = "fa-truck",
                        Icon = "fa-ship",
                        IconSize = 20,
                        WidthRequest = 20,
                        IconColor = Estilo.Current.PrimaryColor
                    },
                    _SituacaoLabel
                }
            });
            if (frete != null && !string.IsNullOrEmpty(frete.EnderecoDestino))
            {
                _mainLayout.Children.Add(new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Spacing = 5,
                    Children = {
                        new IconImage{
                            HorizontalOptions = LayoutOptions.Start,
                            VerticalOptions = LayoutOptions.Start,
                            Icon = "fa-map-marker",
                            IconSize = 16,
                            WidthRequest = 20,
                            IconColor = Estilo.Current.PrimaryColor
                        },
                        _OrigemLabel
                    }
                });
            }
            _mainLayout.Children.Add(gerarAtributo(frete));
            if (frete != null && !string.IsNullOrEmpty(frete.Dimensao))
            {
                _mainLayout.Children.Add(new StackLayout
                {
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
                });
            }
            if (motorista != null)
            {
                _mainLayout.Children.Add(new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Spacing = 5,
                    Children = {
                        new IconImage{
                            HorizontalOptions = LayoutOptions.Start,
                            VerticalOptions = LayoutOptions.Center,
                            Icon = "fa-user",
                            IconSize = 16,
                            WidthRequest = 20,
                            IconColor = Estilo.Current.PrimaryColor
                        },
                        new Label {
                            HorizontalOptions = LayoutOptions.Start,
                            VerticalOptions = LayoutOptions.Start,
                            FontAttributes = FontAttributes.Bold,
                            TextColor = Estilo.Current.PrimaryColor,
                            Text = frete.Usuario.Nome
                        }
                    }
                });
                if (freteAtual != null && freteAtual.Id == frete.Id && frete.IdMotorista == motorista.Id)
                {
                    _mainLayout.Children.Add(new StackLayout
                    {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new IconImage{
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Icon = "fa-truck",
                                IconSize = 16,
                                WidthRequest = 20,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            new Label {
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Start,
                                FontAttributes = FontAttributes.Bold,
                                TextColor = Estilo.Current.PrimaryColor,
                                Text = "Em Andamento"
                            }
                        }
                    });
                }
            }
            else if (frete.IdMotorista > 0) {
                _mainLayout.Children.Add(new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Spacing = 5,
                    Children = {
                        new IconImage{
                            HorizontalOptions = LayoutOptions.Start,
                            VerticalOptions = LayoutOptions.Center,
                            Icon = "fa-user",
                            IconSize = 16,
                            WidthRequest = 20,
                            IconColor = Estilo.Current.PrimaryColor
                        },
                        new Label {
                            HorizontalOptions = LayoutOptions.Start,
                            VerticalOptions = LayoutOptions.Start,
                            FontAttributes = FontAttributes.Bold,
                            TextColor = Estilo.Current.PrimaryColor,
                            Text = frete.Motorista.Usuario.Nome
                        }
                    }
                });
            }
        }

        private void inicializarComponente()
        {
            _mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
            };
            _SituacaoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                TextColor = Estilo.Current.PrimaryColor,
                FontSize = 18
            };
            _SituacaoLabel.SetBinding(Label.TextProperty, new Binding("SituacaoStr"));
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
            _DuracaoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _DuracaoLabel.SetBinding(Label.TextProperty, new Binding("DuracaoStr"));
            _DistanciaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
            };
            _DistanciaLabel.SetBinding(Label.TextProperty, new Binding("DistanciaStr"));
        }
    }
}
