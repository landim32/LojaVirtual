using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Pagamento.Factory;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Plugin.LocalNotifications;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class AceitePage : ContentPage
    {
        private FreteInfo _frete;

        private Label _observacaoLabel;
        private Label _totalLabel;
        private Button _aceitaButton;
        private Button _cancelarButton;

        public FreteInfo Frete
        {
            get
            {
                return _frete;
            }
            set
            {
                _frete = value;
                if (_frete != null)
                {
                    var valorCobranca = _frete.Preco * 0.15;
                    _totalLabel.Text = "R$ " + valorCobranca.ToString("N2");
                }
            }
        }

        public event EventHandler<FreteInfo> AoAceitar;

        public AceitePage()
        {
            Title = "Quero esse frete";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Padding = 5,
                Spacing = 15,
                Children = {
                    new Frame {
                        Style = Estilo.Current[Estilo.TOTAL_FRAME],
                        Content = new StackLayout
                        {
                            Orientation = StackOrientation.Horizontal,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.CenterAndExpand,
                            Spacing = 2,
                            Children = {
                                new Label {
                                    VerticalOptions = LayoutOptions.Center,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Style = Estilo.Current[Estilo.TOTAL_LABEL],
                                    Text = "15% da primeira hora: "
                                },
                                _totalLabel
                            }
                        }
                    },
                    new Label
                    {
                        VerticalOptions = LayoutOptions.FillAndExpand,
                        HorizontalOptions = LayoutOptions.Start,
                        Text = "Para aceitar esse atendimento será descontado o valor de " +
                            "15% da primeira hora no seu cartão de crédito."
                    },
                    new Label {
                        VerticalOptions = LayoutOptions.Center,
                        HorizontalOptions = LayoutOptions.Start,
                        HorizontalTextAlignment = TextAlignment.Center,
                        Style = Estilo.Current[Estilo.TITULO1],
                        Text = "Você deseja fazer esse atendimento?"
                    },
                    _aceitaButton,
                    _cancelarButton
                }
            };
        }

        private void inicializarComponente()
        {
            _observacaoLabel = new Label
            {
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.TOTAL_LABEL],
                Text = "Valor Cobrado: "
            };
            _totalLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.TOTAL_TEXTO],
                Text = "R$ 0,00",
            };
            _aceitaButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO],
                Text = "Quero essa viagem"
            };
            _aceitaButton.Clicked += aceitarClicked;
            _cancelarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_DANGER],
                Text = "Desisti"
            };
            _cancelarButton.Clicked += (sender, e) =>
            {
                Navigation.PopAsync();
            };
        }

        private PagamentoInfo gerarPagamento(MotoristaInfo motorista, FreteInfo frete, PagamentoCartaoInfo cartao = null)
        {
            var pagamento = new PagamentoInfo
            {
                IdUsuario = motorista.Id,
                Situacao = SituacaoPagamentoEnum.Aberto,
                Tipo = TipoPagamentoEnum.Credito,
                Observacao = "15% da primeira hora"
            };
            pagamento.Itens.Add(new PagamentoItemInfo
            {
                Descricao = "15% da primeira hora",
                Quantidade = 1,
                Valor = frete.Preco * 0.15
            });
            if (cartao != null)
            {
                pagamento.Bandeira = cartao.Bandeira;
                pagamento.Token = cartao.Token;
                pagamento.NomeCartao = cartao.Nome;
                pagamento.CVV = cartao.CVV;
            }
            return pagamento;
        }

        protected async void aceitarClicked(object sender, EventArgs e)
        {
            UserDialogs.Instance.ShowLoading("Efetuando pagamento...");
            try
            {
                var regraMotorista = MotoristaFactory.create();
                var motorista = regraMotorista.pegarAtual();
                if (motorista == null)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert("Você não é um motorista.", "Erro", "Entendi");
                    return;
                }
                var regraCartao = PagamentoCartaoFactory.create();
                var cartoes = await regraCartao.listar(motorista.Id);
                if (cartoes != null && cartoes.Count > 0)
                {
                    var pagamento = gerarPagamento(motorista, _frete, cartoes[0]);
                    var regraPagamento = PagamentoFactory.create();
                    var retorno = await regraPagamento.pagarComToken(pagamento);
                    if (retorno.Situacao == SituacaoPagamentoEnum.Pago)
                    {
                        pagamento = await regraPagamento.pegar(retorno.IdPagamento);

                        var mensagem = "Foram debitados R$ {0} do seu cartão de crédito.";
                        CrossLocalNotifications.Current.Show("Easy Barcos", string.Format(mensagem, pagamento.ValorTotalStr));

                        var regraFrete = FreteFactory.create();
                        _frete = await regraFrete.pegar(_frete.Id);
                        _frete.IdPagamento = pagamento.IdPagamento;
                        await regraFrete.alterar(_frete);
                        UserDialogs.Instance.HideLoading();
                        AoAceitar?.Invoke(this, _frete);
                    }
                    else
                    {
                        UserDialogs.Instance.HideLoading();
                        await DisplayAlert("Erro", retorno.Mensagem, "Entendi");
                    }
                }
                else
                {
                    UserDialogs.Instance.HideLoading();
                    var cartaoPage = new CartaoPage
                    {
                        UsaCredito = true,
                        UsaDebito = false,
                        TotalVisivel = true,
                        Pagamento = gerarPagamento(motorista, _frete)
                    };
                    cartaoPage.AoEfetuarPagamento += async (s2, pagamento) =>
                    {
                        UserDialogs.Instance.ShowLoading("Atualizando frete...");
                        try
                        {
                            var regraFrete = FreteFactory.create();
                            _frete = await regraFrete.pegar(_frete.Id);
                            _frete.IdPagamento = pagamento.IdPagamento;
                            await regraFrete.alterar(_frete);
                            UserDialogs.Instance.HideLoading();
                            AoAceitar?.Invoke(this, _frete);
                        }
                        catch (Exception erro)
                        {
                            UserDialogs.Instance.HideLoading();
                            await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                        }
                    };
                    await Navigation.PushAsync(cartaoPage);
                }
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
            }
        }
    }
}