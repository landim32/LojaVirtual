using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Frete.Utils;
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

        public bool EfetuarPagamento { get; set; } = false;

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
                        //Style = Estilo.Current[Estilo.TOTAL_FRAME],
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
                                    //Style = Estilo.Current[Estilo.TOTAL_LABEL],
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
                            "15% da primeira hora no seu cartão de crédito assim que iniciar o atendimento."
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
                //Style = Estilo.Current[Estilo.TOTAL_LABEL],
                Text = "Valor Cobrado: "
            };
            _totalLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                //Style = Estilo.Current[Estilo.TOTAL_TEXTO],
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

        protected void aceitarClicked(object sender, EventArgs e)
        {
            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();
            if (motorista == null)
            {
                UserDialogs.Instance.Alert("Você não é um motorista.", "Erro", "Entendi");
                return;
            }
            if (EfetuarPagamento)
            {
                PagamentoUtils.efetuarPagamento(motorista, _frete, (frete) => {
                    AoAceitar?.Invoke(this, frete);
                });
            }
            else {
                AoAceitar?.Invoke(this, _frete);
            }
        }
    }
}