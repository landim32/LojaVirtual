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
using Emagine.Pagamento.Model;

namespace Emagine.Frete.Cells
{
    public class FreteCell : ViewCell
    {
        private int x = 0, y = 0;

        private StackLayout _mainLayout;
        private Grid _gridLayout;
        private Label _OrigemLabel;
        private Label _DimensaoLabel;
        private Label _ValorLabel;
        private Label _DistanciaLabel;
        private Label _PesoLabel;
        private Label _SituacaoLabel;

        public FreteCell()
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

        protected void adicionarAtributo(string icone, Label label) {
            _gridLayout.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5,
                Children = {
                    new IconImage{
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Icon = icone,
                        IconSize = 16,
                        WidthRequest = 20,
                        IconColor = Estilo.Current.PrimaryColor
                    },
                    label
                }
            }, x, y);
            x++;
            if (x > 1) {
                y++;
                x = 0;
            }
        }

        protected override void OnBindingContextChanged()
        {
            base.OnBindingContextChanged();
            var frete = (FreteInfo)BindingContext;

            //var regraUsuario = UsuarioFactory.create();
            //var usuario = regraUsuario.pegarAtual();

            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();

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
            }
            else if (frete.IdMotorista > 0)
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
                            Text = frete.Motorista.Usuario.Nome
                        }
                    }
                });
            }
            if (frete != null)
            {
                if (frete.Preco > 0)
                {
                    adicionarAtributo("fa-dollar", new Label
                    {
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Start,
                        FontAttributes = FontAttributes.Bold,
                        Text = "R$ " + frete.Preco.ToString("N2")
                    });
                }
                if (frete.Distancia > 0) {
                    adicionarAtributo("fa-map", new Label
                    {
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Start,
                        FontAttributes = FontAttributes.Bold,
                        Text = frete.DistanciaStr
                    });
                }
                if (frete.Peso > 0)
                {
                    adicionarAtributo("fa-balance-scale", new Label {
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Start,
                        FontAttributes = FontAttributes.Bold,
                        Text = frete.Peso.ToString("N1") + "Kg"
                    });
                }
                if (!string.IsNullOrEmpty(frete.Dimensao))
                {
                    adicionarAtributo("fa-arrows-alt", new Label {
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Start,
                        FontAttributes = FontAttributes.Bold,
                        Text = frete.Dimensao
                    });
                }
                if (frete.Duracao > 0)
                {
                    adicionarAtributo("fa-clock-o", new Label
                    {
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Start,
                        FontAttributes = FontAttributes.Bold,
                        Text = frete.DuracaoStr
                    });
                }
                else if (frete.PrevisaoTempo > 0) {
                    adicionarAtributo("fa-clock-o", new Label
                    {
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Start,
                        FontAttributes = FontAttributes.Bold,
                        Text = frete.PrevisaoStr
                    });
                }
                adicionarAtributo("fa-calendar", new Label
                {
                    HorizontalOptions = LayoutOptions.Start,
                    VerticalOptions = LayoutOptions.Start,
                    FontAttributes = FontAttributes.Bold,
                    Text = frete.DataInclusaoStr
                });
            }
            _mainLayout.Children.Add(_gridLayout);
            if (motorista != null)
            {
                var regraFrete = FreteFactory.create();
                var freteAtual = regraFrete.pegarAtual();

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

            if (frete != null && frete.IdPagamento > 0 && frete.Pagamento != null)
            {
                var texto = string.Empty;
                var cor = Estilo.Current.PrimaryColor;
                switch (frete.Pagamento.Tipo) {
                    case TipoPagamentoEnum.Boleto:
                        texto = (motorista != null) ? "Receber por Boleto Bancário" : "Pagar com Boleto Bancário";
                        cor = Estilo.Current.DangerColor;
                        break;
                    case TipoPagamentoEnum.CartaoOffline:
                        texto = (motorista != null) ? "Receber por máquina de cartão" : "Pagar com máquina de cartão";
                        cor = Estilo.Current.DangerColor;
                        break;
                    case TipoPagamentoEnum.CreditoOnline:
                        if (frete.Pagamento.Situacao == SituacaoPagamentoEnum.Pago)
                        {
                            texto = "Pago com cartão de crédito";
                            cor = Estilo.Current.SuccessColor;
                        }
                        else {
                            texto = (motorista != null) ? "Receber por cartão de crédito" : "Pagar com cartão de crédito";
                            cor = Estilo.Current.DangerColor;
                        }
                        break;
                    case TipoPagamentoEnum.DebitoOnline:
                        if (frete.Pagamento.Situacao == SituacaoPagamentoEnum.Pago)
                        {
                            texto = "Pago com cartão de débito";
                            cor = Estilo.Current.SuccessColor;
                        }
                        else
                        {
                            texto = (motorista != null) ? "Receber por cartão de débito" : "Pagar com cartão de débito";
                            cor = Estilo.Current.DangerColor;
                        }
                        break;
                    case TipoPagamentoEnum.Dinheiro:
                        texto = (motorista != null) ? "Receber por dinheiro" : "Pagar com dinheiro";
                        cor = Estilo.Current.DangerColor;
                        break;
                }

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
                            Icon = "fa-dollar",
                            IconSize = 16,
                            WidthRequest = 20,
                            IconColor = Estilo.Current.PrimaryColor
                        },
                        new Label {
                            HorizontalOptions = LayoutOptions.Start,
                            VerticalOptions = LayoutOptions.Start,
                            FontAttributes = FontAttributes.Bold,
                            TextColor = cor,
                            Text = texto
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
            _gridLayout = new Grid
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
            };
            _SituacaoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                TextColor = Estilo.Current.PrimaryColor,
                FontSize = 20
            };
            _SituacaoLabel.SetBinding(Label.TextProperty, new Binding("SituacaoStr"));
            _OrigemLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
            };
            _OrigemLabel.SetBinding(Label.TextProperty, new Binding("EnderecoDestino"));
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
            _DistanciaLabel.SetBinding(Label.TextProperty, new Binding("DistanciaStr"));
        }
    }
}
