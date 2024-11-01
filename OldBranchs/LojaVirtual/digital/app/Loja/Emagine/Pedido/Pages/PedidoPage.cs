﻿using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Base.Pages;
using Emagine.Endereco.Controls;
using Emagine.GPS.Utils;
using Emagine.Pagamento.Factory;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Emagine.Pedido.Controls;
using Emagine.Pedido.Factory;
using Emagine.Pedido.Model;
using Emagine.Pedido.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Pedido.Pages
{
	public class PedidoPage : ContentPage
	{
        private Grid _gridLayout;
        private StackLayout _mainLayout;
        private Label _TituloLabel;
        private EnderecoView _enderecoView;
        private Label _MetodoEntregaLabel;
        private Label _FormaPagamentoLabel;
        private Label _TrocoParaLabel;
        private Label _ValorFreteLabel;
        private Label _ValorTotalLabel;
        private Label _SituacaoLabel;
        private Label _ObservacaoLabel;
        private PedidoView _PedidoView;
        private Button _ImprimirButton;
        private Button _AcompanhaButton;
        private Button _AvaliarButton;
        private Button _CancelarButton;

        public PedidoInfo Pedido {
            get {
                return (PedidoInfo)BindingContext;
            }
            set {
                BindingContext = value;
                if (value != null) {
                    Title = "Pedido nº " + value.Id.ToString();
                    atualizarTitulo(value);
                    atualizarTela(value);
                }
            }
        }

		public PedidoPage ()
		{
            Style = Estilo.Current[Estilo.TELA_PADRAO];
            inicializarComponente();
            _mainLayout = new StackLayout
            {
                Padding = new Thickness(3, 5),
                Spacing = 5,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
            };
            _gridLayout = new Grid {
                Padding = new Thickness(3, 5),
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
			};
            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Content = _mainLayout
            };
            //atualizarTela();
		}

        private void atualizarTitulo(PedidoInfo pedido) {
            switch (pedido.Situacao)
            {
                case SituacaoEnum.Finalizado:
                    if (pedido.Entrega == EntregaEnum.RetirarNaLoja)
                    {
                        _TituloLabel.Text = "Seu pedido foi retirado na loja.";
                    }
                    else if (pedido.Entrega == EntregaEnum.RetiradaMapeada)
                    {
                        _TituloLabel.Text = "Seu pedido foi retirado.";
                    }
                    else {
                        _TituloLabel.Text = "Seu pedido foi entregue.";
                    }
                    break;
                case SituacaoEnum.Preparando:
                    _TituloLabel.Text = "Seu pedido está sendo preparado.";
                    break;
                case SituacaoEnum.Pendente:
                    if (pedido.Entrega == EntregaEnum.RetirarNaLoja)
                    {
                        _TituloLabel.Text = "Seu pedido está aguardando ser retirado no Loja.";
                    }
                    else if (pedido.Entrega == EntregaEnum.RetiradaMapeada)
                    {
                        _TituloLabel.Text = "Seu pedido está aguardando ser retirado.";
                    }
                    else {
                        _TituloLabel.Text = "Seu pedido está pedente para entrega.";
                    }
                    break;
                case SituacaoEnum.AguardandoPagamento:
                    _TituloLabel.Text = "Seu pedido ainda não foi finalizando.";
                    break;
                case SituacaoEnum.Entregando:
                    _TituloLabel.Text = "Seu pedido saiu para entrega.";
                    break;
                case SituacaoEnum.Entregue:
                    _TituloLabel.Text = "Seu pedido foi entregue.";
                    break;
                case SituacaoEnum.Cancelado:
                    _TituloLabel.Text = "Seu pedido foi cancelado.";
                    break;
                default:
                    _TituloLabel.Text = "O status do seu pedido é '" + pedido.SituacaoStr + "'.";
                    break;
            }
        }

        private StackLayout gerarNota(int nota) {
            return new StackLayout {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    new IconImage {
                        Icon = (nota >= 1) ? "fa-star" : "fa-star-o",
                        IconSize = 22,
                        IconColor = Color.Gold
                    },
                    new IconImage {
                        Icon = (nota >= 2) ? "fa-star" : "fa-star-o",
                        IconSize = 22,
                        IconColor = Color.Gold
                    },
                    new IconImage {
                        Icon = (nota >= 3) ? "fa-star" : "fa-star-o",
                        IconSize = 22,
                        IconColor = Color.Gold
                    },
                    new IconImage {
                        Icon = (nota >= 4) ? "fa-star" : "fa-star-o",
                        IconSize = 22,
                        IconColor = Color.Gold
                    },
                    new IconImage {
                        Icon = (nota >= 5) ? "fa-star" : "fa-star-o",
                        IconSize = 22,
                        IconColor = Color.Gold
                    }
                }
            };
        }

        private void atualizarTela(PedidoInfo pedido) {
            _mainLayout.Children.Clear();
            _mainLayout.Children.Add(_TituloLabel);
            if (pedido.Nota > 0)
            {
                _mainLayout.Children.Add(gerarNota(pedido.Nota.HasValue ? pedido.Nota.Value : 0));
            }

            if (pedido.FormaPagamento == FormaPagamentoEnum.Boleto) {
                if (pedido.Situacao == SituacaoEnum.Pendente || pedido.Situacao == SituacaoEnum.AguardandoPagamento) {
                    _mainLayout.Children.Add(new Label
                    {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                        Text = "Clique no botão abaixo para imprimir o boleto. Pode demorar de um a dois dias para baixa no pagamento."
                    });
                    _mainLayout.Children.Add(_ImprimirButton);
                }
            }
            if (!(pedido.Nota > 0))
            {
                var situacaoFinalizado = new List<SituacaoEnum>() {
                    SituacaoEnum.Entregue,
                    SituacaoEnum.Finalizado
                };
                if (situacaoFinalizado.Contains(pedido.Situacao))
                {
                    _mainLayout.Children.Add(_AvaliarButton);
                }
            }

            var formaPgto = new List<FormaPagamentoEnum>()
            {
                FormaPagamentoEnum.Boleto,
                FormaPagamentoEnum.Dinheiro
            };
            var situacaoPedido = new List<SituacaoEnum>() {
                SituacaoEnum.AguardandoPagamento,
                SituacaoEnum.Pendente,
                SituacaoEnum.Preparando
            };
            if (formaPgto.Contains(pedido.FormaPagamento) && situacaoPedido.Contains(pedido.Situacao)) {
                _mainLayout.Children.Add(_CancelarButton);
            }

            if (pedido.Entrega == EntregaEnum.RetiradaMapeada)
            {
                var situacoes = new List<SituacaoEnum>() {
                    SituacaoEnum.Entregando,
                    SituacaoEnum.Pendente,
                    SituacaoEnum.Preparando
                };
                if (GPSUtils.UsaLocalizacao && situacoes.Contains(pedido.Situacao))
                {
                    _mainLayout.Children.Add(_AcompanhaButton);
                }
            }
            else if (pedido.Entrega == EntregaEnum.RetirarNaLoja) {
                _mainLayout.Children.Add(new Label
                {
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Style = Estilo.Current[Estilo.TITULO3],
                    Text = "Retire seu pedido no endereço abaixo:"
                });
                _enderecoView.SetBinding(EnderecoView.BindingContextProperty, new Binding("Loja"));
                _mainLayout.Children.Add(_enderecoView);
            }
            else {
                _mainLayout.Children.Add(new Label
                {
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Style = Estilo.Current[Estilo.TITULO3],
                    Text = "Seu pedido será entregue no endereço abaixo:"
                });
                _enderecoView.SetBinding(EnderecoView.BindingContextProperty, new Binding("."));
                _mainLayout.Children.Add(_enderecoView);
                if (pedido.FormaPagamento == FormaPagamentoEnum.Dinheiro) {
                    _mainLayout.Children.Add(new Frame
                    {
                        CornerRadius = 5,
                        Padding = new Thickness(4, 3),
                        Margin = new Thickness(2, 2),
                        BackgroundColor = Color.White,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Fill,
                        Content = new StackLayout
                        {
                            HorizontalOptions = LayoutOptions.CenterAndExpand,
                            VerticalOptions = LayoutOptions.Start,
                            Orientation = StackOrientation.Horizontal,
                            Spacing = 3,
                            Children = {
                                new Label {
                                    HorizontalOptions = LayoutOptions.Fill,
                                    VerticalOptions = LayoutOptions.Center,
                                    Style = Estilo.Current[Estilo.LABEL_CONTROL],
                                    VerticalTextAlignment = TextAlignment.Center,
                                    Text = "Troco para:"
                                },
                                _TrocoParaLabel
                            }
                        }
                    });
                }
            }

            _mainLayout.Children.Add(new Frame {
                CornerRadius = 5,
                Padding = new Thickness(4, 3),
                Margin = new Thickness(2, 2),
                BackgroundColor = Color.White,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Content = _gridLayout
            });
            _gridLayout.Children.Add(new StackLayout {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Orientation = StackOrientation.Vertical,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                        Text = "Método de Entrega:"
                    },
                    _MetodoEntregaLabel
                }
            }, 0, 0);
            _gridLayout.Children.Add(new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Orientation = StackOrientation.Vertical,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                        Text = "Forma de Pagamento:"
                    },
                    _FormaPagamentoLabel
                }
            }, 0, 1);
            _gridLayout.Children.Add(new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Orientation = StackOrientation.Vertical,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                        Text = "Situação:"
                    },
                    _SituacaoLabel
                }
            }, 1, 0);

            _gridLayout.Children.Add(new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Orientation = StackOrientation.Vertical,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                        Text = "Valor Total:"
                    },
                    _ValorTotalLabel
                }
            }, 1, 1);

            if (!string.IsNullOrEmpty(pedido.Observacao))
            {
                _mainLayout.Children.Add(new Frame
                {
                    CornerRadius = 5,
                    Padding = new Thickness(4, 3),
                    Margin = new Thickness(2, 2),
                    BackgroundColor = Color.White,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    Content = new StackLayout
                    {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Orientation = StackOrientation.Vertical,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.Fill,
                                VerticalOptions = LayoutOptions.Start,
                                Style = Estilo.Current[Estilo.LABEL_CONTROL],
                                Text = "Observação:"
                            },
                            _ObservacaoLabel
                        }
                    }
                });
            }

            _mainLayout.Children.Add(new Frame
            {
                CornerRadius = 5,
                Padding = new Thickness(4, 3),
                Margin = new Thickness(2, 2),
                BackgroundColor = Color.White,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Content = _PedidoView
            });
        }

        public void inicializarComponente() {
            _TituloLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.TITULO1],
                HorizontalTextAlignment = TextAlignment.Center
            };
            _enderecoView = new EnderecoView
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
            };
            _enderecoView.SetBinding(EnderecoView.BindingContextProperty, new Binding("."));
            _MetodoEntregaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CAMPO],
                LineBreakMode = LineBreakMode.TailTruncation
            };
            _MetodoEntregaLabel.SetBinding(Label.TextProperty, new Binding("EntregaStr"));
            _FormaPagamentoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CAMPO],
                LineBreakMode = LineBreakMode.TailTruncation
            };
            _FormaPagamentoLabel.SetBinding(Label.TextProperty, new Binding("PagamentoStr"));
            _TrocoParaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CAMPO]
            };
            _TrocoParaLabel.SetBinding(Label.TextProperty, new Binding("TrocoParaStr", stringFormat: "R${0}"));
            _ValorFreteLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CAMPO]
            };
            _ValorFreteLabel.SetBinding(Label.TextProperty, new Binding("ValorFreteStr", stringFormat: "R${0}"));
            _ValorTotalLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CAMPO]
            };
            _ValorTotalLabel.SetBinding(Label.TextProperty, new Binding("TotalStr", stringFormat: "R${0}"));
            _SituacaoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                LineBreakMode = LineBreakMode.TailTruncation,
                Style = Estilo.Current[Estilo.LABEL_CAMPO]
            };
            _SituacaoLabel.SetBinding(Label.TextProperty, new Binding("SituacaoStr"));
            _ObservacaoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CAMPO]
            };
            _ObservacaoLabel.SetBinding(Label.TextProperty, new Binding("Observacao"));

            _PedidoView = new PedidoView
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
            };
            _PedidoView.SetBinding(PedidoView.BindingContextProperty, new Binding("."));

            _ImprimirButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Imprimir Boleto"
            };
            _ImprimirButton.Clicked += async (sender, e) => {
                if (Pedido.Pagamento == null && !Pedido.IdPagamento.HasValue) {
                    UserDialogs.Instance.Alert("Pedido não possui pagamento vinculado.", "Erro", "Fechar");
                    return;
                }
                if (Pedido.Pagamento == null) {
                    UserDialogs.Instance.ShowLoading("Carregando...");
                    try
                    {
                        var regraPagamento = PagamentoFactory.create();
                        var pagamento = await regraPagamento.pegar(Pedido.IdPagamento.Value);
                        UserDialogs.Instance.HideLoading();
                        Pedido.Pagamento = pagamento;
                    }
                    catch (Exception erro)
                    {
                        UserDialogs.Instance.HideLoading();
                        UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                    }
                }
                var boletoImprimePage = new BoletoImprimePage
                {
                    Pagamento = Pedido.Pagamento
                };
                await Navigation.PushAsync(boletoImprimePage);
            };
            _AcompanhaButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO],
                Text = "Acompanhar no Mapa"
            };
            _AcompanhaButton.Clicked += acompanharClicked;

            _CancelarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_DANGER],
                Text = "Cancelar"
            };
            _CancelarButton.Clicked += cancelarClicked;

            _AvaliarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO],
                Text = "Avaliar"
            };
            _AvaliarButton.Clicked += avaliarClicked;
        }

        private async void cancelarClicked(object sender, EventArgs e)
        {
            UserDialogs.Instance.ShowLoading("Carregando...");
            try
            {
                var regraPedido = PedidoFactory.create();
                await regraPedido.alterarSituacao(Pedido.Id, SituacaoEnum.Cancelado);
                var pedido = await regraPedido.pegar(Pedido.Id);
                Pedido = pedido;
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }

        private async void acompanharClicked(object sender, EventArgs e)
        {
            var acompanhaPage = await AcompanhamentoUtils.gerarMapaAcompanhamento(Pedido);
            await Navigation.PushAsync(acompanhaPage);
        }

        private void avaliarClicked(object sender, EventArgs e) {
            var avaliePage = new AvaliePage
            {
                Descricao = "Que nota você daria para o seu pedido?"
            };
            avaliePage.AoAvaliar += async (s2, nota) => {
                Pedido.Nota = nota;
                UserDialogs.Instance.ShowLoading("Carregando...");
                try
                {
                    var regraPedido = PedidoFactory.create();
                    await regraPedido.alterar(Pedido);
                    var pedido = await regraPedido.pegar(Pedido.Id);
                    Pedido = pedido;
                    atualizarTela(Pedido);
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                }
            };
            Navigation.PushAsync(avaliePage);
        }
    }
}
