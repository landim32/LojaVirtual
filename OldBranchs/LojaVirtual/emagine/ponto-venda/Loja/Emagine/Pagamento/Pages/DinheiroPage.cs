using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Pagamento.Factory;
using Emagine.Pagamento.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pagamento.Pages
{
    public class DinheiroPage : ContentPage
    {
        private Entry _TrocoParaEntry;
        private Entry _ObservacaoEntry;
        private Label _ValorTotalLabel;
        private Button _ConcluirButton;

        public PagamentoInfo Pagamento {
            get {
                return (PagamentoInfo)BindingContext;
            }
            set {
                BindingContext = value;
            }
        }
        public event EventHandler<PagamentoInfo> AoEfetuarPagamento;

        public DinheiroPage()
        {
            Title = "Em dinheiro";
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];

            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(5, 20),
                Spacing = 5,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Text = "Valor da Compra",
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                        HorizontalTextAlignment = TextAlignment.Center
                    },
                    _ValorTotalLabel,
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Text = "Troco para",
                        Style = Estilo.Current[Estilo.LABEL_CONTROL]
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new IconImage {
                                Icon = "fa-dollar",
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                            },
                            _TrocoParaEntry
                        }
                    },
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Text = "Observação",
                        Style = Estilo.Current[Estilo.LABEL_CONTROL]
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new IconImage {
                                Icon = "fa-comment",
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                            },
                            _ObservacaoEntry
                        }
                    },
                    _ConcluirButton
                }
            };
        }

        private void inicializarComponente() {
            _ValorTotalLabel = new Label {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CAMPO],
                HorizontalTextAlignment = TextAlignment.Center,
                FontSize = 24
            };
            _ValorTotalLabel.SetBinding(Label.TextProperty, new Binding("ValorTotalStr", stringFormat: "R${0}"));
            _TrocoParaEntry = new Entry {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                Keyboard = Keyboard.Numeric
            };
            _ObservacaoEntry = new Entry
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO]
            };
            _ConcluirButton = new Button {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "FINALIZAR PEDIDO"
            };
            _ConcluirButton.Clicked += async (sender, e) => {
                if (Pagamento == null) {
                    await DisplayAlert("Erro", "Nenhum pagamento informando.", "Fechar");
                    return;
                }

                UserDialogs.Instance.ShowLoading("Enviando...");
                try {
                    var regraPagamento = PagamentoFactory.create();
                    Pagamento.Tipo = TipoPagamentoEnum.Dinheiro;
                    Pagamento.Observacao = _ObservacaoEntry.Text;
                    double trocoPara = 0;
                    if (double.TryParse(_TrocoParaEntry.Text, out trocoPara)) {
                        Pagamento.TrocoPara = trocoPara;
                    }
                    var retorno = await regraPagamento.pagar(Pagamento);
                    Pagamento = await regraPagamento.pegar(retorno.IdPagamento);
                    UserDialogs.Instance.HideLoading();
                    AoEfetuarPagamento?.Invoke(this, Pagamento);
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    //UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                    await DisplayAlert("Erro", erro.Message, "Entendi");
                }
            };
        }
    }
}