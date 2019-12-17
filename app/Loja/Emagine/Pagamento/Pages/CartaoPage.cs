using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Base.Pages;
using Emagine.Base.Utils;
using Emagine.Login.Factory;
using Emagine.Pagamento.Controls;
using Emagine.Pagamento.Factory;
using Emagine.Pagamento.Model;
using FormsPlugin.Iconize;
using Plugin.LocalNotifications;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pagamento.Pages
{
    public class CartaoPage : ContentPage
    {
        private const int NUMERO_CARTAO_SIZE = 30;
        private bool _totalVisivel = false;
        private PagamentoInfo _pagamento = null;

        private StackLayout _MainLayout;
        private Grid _TipoDebitoGrid;
        private Label _observacaoLabel;
        private Label _totalLabel;
        private Button _CreditoButton;
        private Button _DebitoButton;
        private Entry _NomeEntry;
        private NumeroCartaoEntry _NumeroCartaoEntry;
        private Picker _ValidadeCartaoPicker;
        private Entry _CVCartaoEntry;
        private Button _PagarButton;
        private TipoPagamentoEnum _TipoSelecionado = TipoPagamentoEnum.CreditoOnline;

        public event EventHandler<PagamentoInfo> AoEfetuarPagamento;

        public PagamentoInfo Pagamento {
            get {
                return _pagamento;
            }
            set {
                _pagamento = value;
                if (_pagamento != null) {
                    if (!string.IsNullOrEmpty(_pagamento.Observacao)) {
                        _observacaoLabel.Text = _pagamento.Observacao;
                    }
                    _totalLabel.Text = "R$ " + _pagamento.ValorTotal.ToString("N2");
                }
            }
        }

        public bool UsaCredito {
            get {
                return (bool)GetValue(UsaCreditoProperty);
            }
            set {
                SetValue(UsaCreditoProperty, value);
                atualizarTipoPagamento();
            }
        }

        public bool UsaDebito {
            get {
                return (bool)GetValue(UsaDebitoProperty);
            }
            set {
                SetValue(UsaDebitoProperty, value);
                atualizarTipoPagamento();
            }
        }

        public bool TotalVisivel {
            get {
                return _totalVisivel;
            }
            set {
                _totalVisivel = value;
                if (_totalVisivel)
                {
                    if (!(_MainLayout.Children[0] is Frame)) {
                        _MainLayout.Children.Insert(0, gerarPainelValor());
                    }
                }
                else {
                    if (_MainLayout.Children[0] is Frame)
                    {
                        _MainLayout.Children.RemoveAt(0);
                    }
                }
            }
        }

        public CartaoPage()
        {
            Title = "Forma de Pagamento";
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];
            inicializarComponente();

            _TipoDebitoGrid = new Grid
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
            };
            _TipoDebitoGrid.Children.Add(_CreditoButton, 0, 0);
            _TipoDebitoGrid.Children.Add(_DebitoButton, 1, 0);

            _MainLayout = new StackLayout()
            {
                Padding = new Thickness(10, 20),
                Spacing = 5,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Orientation = StackOrientation.Vertical,
                Children = {
                    _TipoDebitoGrid,
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Text = "Número do Cartão",
                        Style = Estilo.Current[Estilo.LABEL_CONTROL]
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new IconImage {
                                Icon = "fa-credit-card",
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO],
                                IconSize = NUMERO_CARTAO_SIZE
                            },
                            _NumeroCartaoEntry
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new StackLayout {
                                Orientation = StackOrientation.Vertical,
                                HorizontalOptions = LayoutOptions.FillAndExpand,
                                VerticalOptions = LayoutOptions.Start,
                                Children = {
                                    new Label {
                                        HorizontalOptions = LayoutOptions.Fill,
                                        VerticalOptions = LayoutOptions.Start,
                                        Text = "Validade do Cartão",
                                        Style = Estilo.Current[Estilo.LABEL_CONTROL]
                                    },
                                    new StackLayout {
                                        Orientation = StackOrientation.Horizontal,
                                        HorizontalOptions = LayoutOptions.FillAndExpand,
                                        VerticalOptions = LayoutOptions.Start,
                                        Children = {
                                            new IconImage {
                                                Icon = "fa-calendar",
                                                HorizontalOptions = LayoutOptions.Start,
                                                VerticalOptions = LayoutOptions.Center,
                                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                                            },
                                            _ValidadeCartaoPicker
                                        }
                                    }
                                }
                            },
                            new StackLayout {
                                Orientation = StackOrientation.Vertical,
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Start,
                                WidthRequest = 120,
                                Children = {
                                    new Label {
                                        HorizontalOptions = LayoutOptions.Fill,
                                        VerticalOptions = LayoutOptions.Start,
                                        Text = "CVV",
                                        Style = Estilo.Current[Estilo.LABEL_CONTROL]
                                    },
                                    new StackLayout {
                                        Orientation = StackOrientation.Horizontal,
                                        HorizontalOptions = LayoutOptions.FillAndExpand,
                                        VerticalOptions = LayoutOptions.Start,
                                        Children = {
                                            new IconImage {
                                                Icon = "fa-lock",
                                                HorizontalOptions = LayoutOptions.Start,
                                                VerticalOptions = LayoutOptions.Center,
                                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                                            },
                                            _CVCartaoEntry
                                        }
                                    }
                                }
                            }
                        }
                    },
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Text = "Nome no Cartão",
                        Style = Estilo.Current[Estilo.LABEL_CONTROL]
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new IconImage {
                                Icon = "fa-user",
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                            },
                            _NomeEntry
                        }
                    },
                    //_CPFEntry,
                    _PagarButton
                }
            };

            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Fill,
                Content = _MainLayout
            };
        }

        private Frame gerarPainelValor() {
            return new Frame
            {
                Style = Estilo.Current[Estilo.TOTAL_FRAME],
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.CenterAndExpand,
                    Spacing = 2,
                    Children = {
                        _observacaoLabel,
                        new Label {
                            VerticalOptions = LayoutOptions.Center,
                            HorizontalOptions = LayoutOptions.Start,
                            Style = Estilo.Current[Estilo.TOTAL_LABEL],
                            Text = ": "
                        },
                        _totalLabel
                    }
                }
            };
        }

        private bool validarCampo()
        {
            if (String.IsNullOrEmpty(_NomeEntry.Text))
            {
                DisplayAlert("Aviso", "Preencha o nome no cartão.", "Entendi");
                return false;
            }
            if (String.IsNullOrEmpty(_NumeroCartaoEntry.Text))
            {
                DisplayAlert("Aviso", "Preencha o número do cartão.", "Entendi");
                return false;
            }
            var regraPagamento = PagamentoFactory.create();
            /*
            var bandeira = regraPagamento.pegarBandeiraPorNumeroCartao(_NumeroCartaoEntry.Text);
            if (!bandeira.HasValue) {
                 
            }
            */
            if (_ValidadeCartaoPicker.SelectedItem == null)
            {
                DisplayAlert("Aviso", "Selecione a validade do cartão.", "Entendi");
                return false;
            }
            if (String.IsNullOrEmpty(_CVCartaoEntry.Text))
            {
                DisplayAlert("Aviso", "Preencha o CVV.", "Entendi");
                return false;
            }
            return true;
        }

        private async void enviarPagamento()
        {
            if (!validarCampo()) {
                return;
            }
            UserDialogs.Instance.ShowLoading("Enviando...");
            try
            {
                var regraPagamento = PagamentoFactory.create();
                var regraCartao = CartaoFactory.create();
                var regraUsuario = UsuarioFactory.create();

                var usuario = regraUsuario.pegarAtual();

                Pagamento.Bandeira = regraPagamento.pegarBandeiraPorNumeroCartao(_NumeroCartaoEntry.Text);
                Pagamento.DataExpiracao = regraCartao.pegarDataExpiracao((string)_ValidadeCartaoPicker.SelectedItem);
                Pagamento.IdUsuario = usuario.Id;
                Pagamento.NomeCartao = _NomeEntry.Text;
                Pagamento.NumeroCartao = _NumeroCartaoEntry.TextOnlyNumber;
                Pagamento.Tipo = _TipoSelecionado;
                Pagamento.CVV = _CVCartaoEntry.Text;

                var retorno = await regraPagamento.pagar(Pagamento);
                if (retorno.Situacao == SituacaoPagamentoEnum.Pago || retorno.Situacao == SituacaoPagamentoEnum.AguardandoPagamento) {
                    var pagamento = await regraPagamento.pegar(retorno.IdPagamento);
                    if (retorno.Situacao == SituacaoPagamentoEnum.Pago) {
                        var mensagem = "Foram debitados R$ {0} do seu cartão de crédito.";
                        var rootPage = (RootPage) App.Current.MainPage;
                        CrossLocalNotifications.Current.Show(rootPage.NomeApp, string.Format(mensagem, pagamento.ValorTotalStr));
                    }
                    UserDialogs.Instance.HideLoading();
                    AoEfetuarPagamento?.Invoke(this, pagamento);
                }
                else
                {
                    UserDialogs.Instance.HideLoading();
                    await DisplayAlert("Erro", retorno.Mensagem, "Entendi");
                }
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
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
            _DebitoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PADRAO],
                Text = "Débito"
            };
            _DebitoButton.Clicked += (sender, e) => {
                _TipoSelecionado = TipoPagamentoEnum.DebitoOnline;
                _DebitoButton.Style = Estilo.Current[Estilo.BTN_PRINCIPAL];
                _CreditoButton.BackgroundColor = Color.Silver;
                _CreditoButton.TextColor = Color.White;
            };
            _CreditoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PADRAO],
                Text = "Crédito"
            };
            _CreditoButton.Clicked += (sender, e) => {
                _TipoSelecionado = TipoPagamentoEnum.CreditoOnline;
                _CreditoButton.Style = Estilo.Current[Estilo.BTN_PRINCIPAL];
                _DebitoButton.BackgroundColor = Color.Silver;
                _DebitoButton.TextColor = Color.White;
            };
            _NomeEntry = new Entry
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Placeholder = "Nome que está no cartão",
                Style = Estilo.Current[Estilo.ENTRY_PADRAO]
            };
            /*_CPFEntry = new Entry {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Placeholder = "CPF do títular do cartão",
                Keyboard = Keyboard.Numeric
            };*/
            _NumeroCartaoEntry = new NumeroCartaoEntry
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Placeholder = "Número do Cartão",
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                FontSize = NUMERO_CARTAO_SIZE
            };
            _ValidadeCartaoPicker = new Picker
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Title = "Selecione a data"
            };
            var regraCartao = CartaoFactory.create();
            _ValidadeCartaoPicker.ItemsSource = regraCartao.listarValidadeCartao();

            _CVCartaoEntry = new Entry
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Placeholder = "CV",
                WidthRequest = 100,
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO]
            };
            _PagarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "PAGAR"
            };
            _PagarButton.Clicked += (sender, e) => {
                enviarPagamento();
            };
        }

        private void atualizarTipoPagamento() {
            if (UsaCredito && UsaDebito)
            {
                if (!_MainLayout.Children.Contains(_TipoDebitoGrid))
                {
                    _MainLayout.Children.Insert(0, _TipoDebitoGrid);
                }
            }
            else if (UsaCredito)
            {
                _MainLayout.Children.Remove(_TipoDebitoGrid);
            }
            else if (UsaDebito)
            {
                _MainLayout.Children.Remove(_TipoDebitoGrid);
            }
            else {
                _MainLayout.Children.Remove(_TipoDebitoGrid);
            }
        }

        public static readonly BindableProperty UsaCreditoProperty = BindableProperty.Create(
            nameof(UsaCredito), typeof(bool), typeof(CartaoPage), default(bool),
            propertyChanged: UsaCreditoPropertyChanged
        );

        private static void UsaCreditoPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (CartaoPage)bindable;
            control.UsaCredito = (bool)newValue;
        }

        public static readonly BindableProperty UsaDebitoProperty = BindableProperty.Create(
            nameof(UsaDebito), typeof(bool), typeof(CartaoPage), default(bool),
            propertyChanged: UsaDebitoPropertyChanged
        );      

        private static void UsaDebitoPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (CartaoPage)bindable;
            control.UsaDebito = (bool)newValue;
        }
    }
}