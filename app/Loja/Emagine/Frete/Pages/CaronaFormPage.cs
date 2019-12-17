using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Frete.Pages;
using Emagine.Login.Factory;
using Emagine.Mapa.Controls;
using Emagine.Mapa.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Xfx;

namespace Frete.Pages
{
    public class CaronaFormPage: ContentPage
    {
        private bool _precoVisivel = true;
        private bool _agendamentoObrigatorio = false;

        private StackLayout _mainLayout;
        private XfxEntry _PassageiroEntry;
        private XfxEntry _ObservacaoEntry;
        private StackLayout _TipoVeiculoLayout;

        private Switch _UsaCargaSwitch;
        private StackLayout _cargaStackLayout;
        private XfxEntry _PesoEntry;
        private XfxEntry _LarguraEntry;
        private XfxEntry _AlturaEntry;
        private XfxEntry _ProfundidadeEntry;

        private Switch _AgendandoSwitch;
        private StackLayout _agendadoStackLayout;
        private DatePicker _DataRetiradaPicker;
        private DatePicker _DataEntregaPicker;
        private TimePicker _HoraRetiradaPicker;
        private TimePicker _HoraEntregaPicker;

        private XfxEntry _PrecoEntry;
        private Button _EnviarButton;

        public bool TipoVeiculoExtra { get; set; } = true;
        public bool PrecoVisivel {
            get {
                return _precoVisivel;
            }
            set {
                _precoVisivel = value;
                atualizarTela();
            }
        }
        public bool AgendamentoObrigatorio {
            get {
                return _agendamentoObrigatorio;
            }
            set {
                _agendamentoObrigatorio = value;
                atualizarTela();
            }
        }
        public event EventHandler<FreteInfo> AoCadastrar;

        private FreteInfo _frete;

        public FreteInfo Frete {
            get {
                if (_frete == null) {
                    _frete = new FreteInfo();
                }
                var regraUsuario = UsuarioFactory.create();
                var usuario = regraUsuario.pegarAtual();

                _frete.IdUsuario = usuario.Id;
                _frete.Situacao = FreteSituacaoEnum.ProcurandoMotorista;
                _frete.Observacao = _ObservacaoEntry.Text;

                _frete.Veiculos.Clear();
                foreach (var obj in _TipoVeiculoLayout.Children) {
                    if (obj is DropDownList && ((DropDownList)obj).Value is TipoVeiculoInfo) {
                        var tipo = (TipoVeiculoInfo)((DropDownList)obj).Value;
                        _frete.Veiculos.Add(tipo);
                    }
                }
                
                if (_UsaCargaSwitch.IsToggled) {
                    double peso = 0;
                    if (double.TryParse(_PesoEntry.Text, out peso)) {
                        _frete.Peso = peso;
                    }
                    double altura = 0;
                    if (double.TryParse(_AlturaEntry.Text, out altura)) {
                        _frete.Altura = altura;
                    }
                    double largura = 0;
                    if (double.TryParse(_LarguraEntry.Text, out largura)) {
                        _frete.Largura = largura;
                    }
                    double profundidade = 0;
                    if (double.TryParse(_ProfundidadeEntry.Text, out profundidade)) {
                        _frete.Profundidade = profundidade;
                    }
                }
                if (_agendamentoObrigatorio || _AgendandoSwitch.IsToggled) {
                    var dataRetirada = new DateTime(_DataRetiradaPicker.Date.Year, _DataRetiradaPicker.Date.Month, _DataRetiradaPicker.Date.Day, 0, 0, 0, DateTimeKind.Unspecified);
                    dataRetirada = dataRetirada.AddHours(_HoraRetiradaPicker.Time.Hours);
                    dataRetirada = dataRetirada.AddMinutes(_HoraRetiradaPicker.Time.Minutes);
                    _frete.DataRetirada = dataRetirada;

                    var dataEntrega = new DateTime(_DataEntregaPicker.Date.Year, _DataEntregaPicker.Date.Month, _DataEntregaPicker.Date.Day, 0, 0, 0, DateTimeKind.Unspecified);
                    dataEntrega = dataEntrega.AddHours(_HoraEntregaPicker.Time.Hours);
                    dataEntrega = dataEntrega.AddMinutes(_HoraEntregaPicker.Time.Minutes);
                    _frete.DataEntrega = dataEntrega;
                }
                double preco = 0;
                if (double.TryParse(_PrecoEntry.Text, out preco)) {
                    _frete.Preco = preco;
                }
                return _frete;
            }
            set {
                _frete = value;
            }
        }

        public CaronaFormPage() {
            inicializarComponente();

            atualizarTela();

            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Content = _mainLayout
            };
        }

        private void atualizarTela() {
            _mainLayout.Children.Clear();
            _mainLayout.Children.Add(_PassageiroEntry);
            _mainLayout.Children.Add(_TipoVeiculoLayout);
            _mainLayout.Children.Add(new StackLayout {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5,
                Children = {
                    _UsaCargaSwitch,
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Center,
                        Style = Estilo.Current[Estilo.LABEL_SWITCH],
                        Text = "Quero levar carga"
                    }
                }
            });
            _mainLayout.Children.Add(_cargaStackLayout);
            if (_agendamentoObrigatorio)
            {
                _mainLayout.Children.Add(_agendadoStackLayout);
                _agendadoStackLayout.Children.Clear();
                atualizarAgendamento();
            }
            else
            {
                _mainLayout.Children.Add(new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Spacing = 5,
                    Children = {
                        _AgendandoSwitch,
                        new Label {
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Center,
                            Style = Estilo.Current[Estilo.LABEL_SWITCH],
                            Text = "Agendar dia e hora"
                        }
                    }
                });
                _mainLayout.Children.Add(_agendadoStackLayout);
                _agendadoStackLayout.Children.Clear();
                if (_AgendandoSwitch.IsToggled) {
                    atualizarAgendamento();
                }
            }
            if (_precoVisivel)
            {
                _mainLayout.Children.Add(_PrecoEntry);
            }
            _mainLayout.Children.Add(_ObservacaoEntry);
            _mainLayout.Children.Add(_EnviarButton);
        }

        protected void atualizarAgendamento() {
            _agendadoStackLayout.Children.Add(new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Text = "Data hora da Retirada:",
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            });
            _agendadoStackLayout.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    new IconImage {
                        Icon = "fa-calendar",
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Style = Estilo.Current[Estilo.ICONE_PADRAO]
                    },
                    _DataRetiradaPicker,
                    new IconImage {
                        Icon = "fa-clock-o",
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Style = Estilo.Current[Estilo.ICONE_PADRAO]
                    },
                    _HoraRetiradaPicker
                }
            });
            _agendadoStackLayout.Children.Add(new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Text = "Data hora máxima para entrega:",
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            });
            _agendadoStackLayout.Children.Add(new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    new IconImage {
                        Icon = "fa-calendar",
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Style = Estilo.Current[Estilo.ICONE_PADRAO]
                    },
                    _DataEntregaPicker,
                    new IconImage {
                        Icon = "fa-clock-o",
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Style = Estilo.Current[Estilo.ICONE_PADRAO]
                    },
                    _HoraEntregaPicker
                }
            });
        }

        private void definirTipoPorOrdem(FreteInfo frete) {
            for (int i = 0; i < frete.Locais.Count(); i++) {
                if (i == 0)
                {
                    frete.Locais[i].Tipo = FreteLocalTipoEnum.Origem;
                }
                else if (i == (frete.Locais.Count() - 1))
                {
                    frete.Locais[i].Tipo = FreteLocalTipoEnum.Destino;
                }
                else {
                    frete.Locais[i].Tipo = FreteLocalTipoEnum.Parada;
                }
            }
        }

        private DropDownList criarTipoVeiculoEntry(TipoVeiculoInfo tipo = null)
        {
            var tipoVeiculoEntry = new DropDownList
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "Tipo de Embarcação",
                TextColor = Color.Black,
                PlaceholderColor = Color.Silver,
                Value = tipo
            };
            tipoVeiculoEntry.Clicked += (sender, e) => {
                var tipoVeiculoPage = new TipoVeiculoSelecionaPage();
                tipoVeiculoPage.AoSelecionar += (object s2, TipoVeiculoInfo e2) =>
                {
                    ((DropDownList)sender).Value = e2;
                    if (TipoVeiculoExtra) {
                        _TipoVeiculoLayout.Children.Add(criarTipoVeiculoEntry());
                    }
                };
                Navigation.PushAsync(tipoVeiculoPage);
            };
            return tipoVeiculoEntry;
        }

        private void inicializarComponente()
        {
            _mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Padding = new Thickness(3, 3),
            };

            _PassageiroEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "Quantidade de Passageiros",
                Keyboard = Keyboard.Numeric,
                ErrorDisplay = ErrorDisplay.None
            };

            _ObservacaoEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "Qual a carga?",
                ErrorDisplay = ErrorDisplay.None
            };
            _TipoVeiculoLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    criarTipoVeiculoEntry()
                }
            };

            _UsaCargaSwitch = new Switch
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                IsToggled = false
            };
            _UsaCargaSwitch.Toggled += (sender, e) =>
            {
                _cargaStackLayout.Children.Clear();
                if (e.Value)
                {
                    _cargaStackLayout.Children.Add(new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.TITULO2],
                        Text = "Informações de carga:"
                    });
                    _cargaStackLayout.Children.Add(_PesoEntry);
                    _cargaStackLayout.Children.Add(_AlturaEntry);
                    _cargaStackLayout.Children.Add(_ProfundidadeEntry);
                    _cargaStackLayout.Children.Add(_LarguraEntry);
                }
            };

            _cargaStackLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };

            _PesoEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                Placeholder = "Peso em Toneladas",
                ErrorDisplay = ErrorDisplay.None
            };

            _LarguraEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                Placeholder = "Largura total (m)",
                ErrorDisplay = ErrorDisplay.None
            };

            _AlturaEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                Placeholder = "Altura total (m)",
                ErrorDisplay = ErrorDisplay.None
            };

            _ProfundidadeEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                Placeholder = "Comprimento total (m)",
                ErrorDisplay = ErrorDisplay.None
            };

            _AgendandoSwitch = new Switch
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                IsToggled = false
            };
            _AgendandoSwitch.Toggled += (sender, e) => {
                _agendadoStackLayout.Children.Clear();
                if (e.Value) {
                    atualizarAgendamento();
                }
            };

            _agendadoStackLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };

            _DataRetiradaPicker = new DatePicker
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_DATA]
            };
            _HoraRetiradaPicker = new TimePicker
            {
                WidthRequest = 100,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_TEMPO]
            };

            _DataEntregaPicker = new DatePicker
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_DATA]
            };
            _HoraEntregaPicker = new TimePicker
            {
                WidthRequest = 100,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_TEMPO]
            };

            _PrecoEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                Placeholder = "Valor do Frete",
                ErrorDisplay = ErrorDisplay.None
            };

            _EnviarButton = new Button
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "SALVAR"
            };
            _EnviarButton.Clicked += async (sender, e) => {
                var regraFrete = FreteFactory.create();
                UserDialogs.Instance.ShowLoading("Enviando...");
                try
                {
                    var frete = this.Frete;
                    definirTipoPorOrdem(frete);
                    var id_frete = await regraFrete.inserir(frete);
                    frete = await regraFrete.pegar(id_frete);
                    UserDialogs.Instance.HideLoading();
                    AoCadastrar?.Invoke(this, frete);
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
            };
        }
    }
}
