using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Xamarin.Forms;
using Xfx;

namespace Emagine.Frete.Pages
{
    public class CadastroMotoristaPage : ContentPage
    {
        protected StackLayout _mainLayout;
        protected DropDownList _TipoVeiculoEntry;
        protected DropDownList _CarroceriaEntry;
        protected XfxEntry _VeiculoEntry;
        protected XfxEntry _CNHEntry;
        protected XfxEntry _PlacaEntry;
        protected XfxEntry _ANTTEntry;
        protected XfxEntry _ValorHoraEntry;
        protected Button _CadastroButton;

        public bool Gravar { get; set; } = true;

        public event EventHandler<MotoristaInfo> AoCompletar;

        private UsuarioInfo _usuario;
        private MotoristaInfo _motorista;


        public UsuarioInfo Usuario {
            get {
                return _usuario;
            }
            set {
                _usuario = value;
            }
        }

        public virtual MotoristaInfo Motorista {
            get {
                if (_motorista == null) {
                    _motorista = new MotoristaInfo();
                }
                TipoVeiculoInfo tipo = (TipoVeiculoInfo)_TipoVeiculoEntry.Value;
                TipoCarroceriaInfo carroceria = (TipoCarroceriaInfo)_CarroceriaEntry.Value;

                double valorHora = 0;
                double.TryParse(_ValorHoraEntry.Text, out valorHora);

                if (_usuario != null) {
                    _motorista.Id = _usuario.Id;
                }
                _motorista.IdTipo = tipo.Id;
                _motorista.Placa = _PlacaEntry.Text;
                _motorista.Veiculo = _VeiculoEntry.Text;
                _motorista.ANTT = _ANTTEntry.Text;
                _motorista.ValorHora = valorHora;
                _motorista.Situacao = MotoristaSituacaoEnum.Ativo;
                if (carroceria != null)
                {
                    _motorista.IdCarroceria = carroceria.Id;
                }
                return _motorista;
            }
            set {
                _motorista = value;
                if (_motorista != null) {
                    _TipoVeiculoEntry.Value = _motorista.Tipo;
                    _CarroceriaEntry.Value = _motorista.Carroceria;
                    _PlacaEntry.Text = _motorista.Placa;
                    _VeiculoEntry.Text = _motorista.Veiculo;
                    _ANTTEntry.Text = _motorista.ANTT;
                    _ValorHoraEntry.Text = _motorista.ValorHora.ToString();
                }
            }
        }

        public CadastroMotoristaPage()
        {
            Title = "Criando nova conta";

            inicializarComponente();

            _mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Padding = 5,
                Children = {
                    _TipoVeiculoEntry,
                    _CarroceriaEntry,
                    _VeiculoEntry,
                    _PlacaEntry,
                    _ANTTEntry,
                    _ValorHoraEntry,
                    _CadastroButton
                }
            };

            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Content = _mainLayout
            };
        }

        protected virtual void inicializarComponente()
        {
            _TipoVeiculoEntry = new DropDownList
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "Tipo de Veículo",
                TextColor = Color.Black,
                PlaceholderColor = Color.Silver
            };
            _TipoVeiculoEntry.Clicked += (sender, e) =>
            {
                var tipoVeiculoPage = new TipoVeiculoSelecionaPage();
                tipoVeiculoPage.AoSelecionar += (object s2, Model.TipoVeiculoInfo e2) =>
                {
                    _TipoVeiculoEntry.Value = e2;
                    tipoVeiculoPage.Navigation.PopAsync();
                };
                Navigation.PushAsync(tipoVeiculoPage);
            };

            _CarroceriaEntry = new DropDownList
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "Tipo de Carroceria",
                TextColor = Color.Black,
                PlaceholderColor = Color.Silver
            };
            _CarroceriaEntry.Clicked += (sender, e) =>
            {
                var carroceriaPage = new CarroceriaSelecionaPage();
                carroceriaPage.AoSelecionar += (object s2, Model.TipoCarroceriaInfo e2) =>
                {
                    _CarroceriaEntry.Value = e2;
                    carroceriaPage.Navigation.PopAsync();
                };
                Navigation.PushAsync(carroceriaPage);
            };
            _CNHEntry = new XfxEntry
            {
                Placeholder = "CNH",
                Keyboard = Keyboard.Numeric,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                ErrorDisplay = ErrorDisplay.None,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL]
            };

            _VeiculoEntry = new XfxEntry
            {
                Placeholder = "Veículo",
                Keyboard = Keyboard.Text,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                ErrorDisplay = ErrorDisplay.None
            };
            _PlacaEntry = new XfxEntry
            {
                Placeholder = "Placa",
                Keyboard = Keyboard.Text,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                ErrorDisplay = ErrorDisplay.None
            };
            _ANTTEntry = new XfxEntry
            {
                Placeholder = "ANTT",
                Keyboard = Keyboard.Numeric,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                ErrorDisplay = ErrorDisplay.None
            };
            _ValorHoraEntry = new XfxEntry
            {
                Placeholder = "Valor da Hora",
                Keyboard = Keyboard.Numeric,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                ErrorDisplay = ErrorDisplay.None
            };
            _CadastroButton = new Button()
            {
                Text = "CADASTRAR",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _CadastroButton.Clicked += CadastroClicked;

        }

        private async void CadastroClicked(object sender, EventArgs e)
        {
            var regraUsuario = UsuarioFactory.create();

            if (Gravar)
            {
                UserDialogs.Instance.ShowLoading("Enviando...");
            }
            try
            {
                if (String.IsNullOrEmpty(_CNHEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha a CNH", "Fechar");
                    return;
                }

                /*
                var regraUsuario = UsuarioFactory.create();
                _usuario.Preferencias.Add(new UsuarioPreferenciaInfo
                {
                    Chave = "ANTT",
                    Valor = _ANTTEntry.Text
                });
                */
                TipoVeiculoInfo tipo = (TipoVeiculoInfo)_TipoVeiculoEntry.Value;
                TipoCarroceriaInfo carroceria = (TipoCarroceriaInfo)_CarroceriaEntry.Value;

                bool incluir = false;
                var regraMotorista = MotoristaFactory.create();
                var motorista = await regraMotorista.pegar(_usuario.Id);
                if (motorista == null) {
                    motorista = new MotoristaInfo();
                    motorista.Id = _usuario.Id;
                    incluir = true;
                }
                motorista.IdTipo = tipo.Id;
                motorista.CNH = _CNHEntry.Text;
                motorista.Placa = _PlacaEntry.Text;
                motorista.Veiculo = _VeiculoEntry.Text;
                motorista.ANTT = _ANTTEntry.Text;
                motorista.Situacao = MotoristaSituacaoEnum.Ativo;
                if (carroceria != null) {
                    motorista.IdCarroceria = carroceria.Id;
                }

                if (Gravar)
                {
                    await regraUsuario.alterar(_usuario);

                    motorista.Usuario = _usuario;
                    /*
                    var motorista = new MotoristaInfo
                    {
                        Id = _usuario.Id,
                        Usuario = _usuario,
                        IdTipo = tipo.Id,
                        CNH = _CNHEntry.Text,
                        Placa = _PlacaEntry.Text,
                        ANTT = _ANTTEntry.Text,
                        Situacao = MotoristaSituacaoEnum.Ativo,
                    };
                    if (carroceria != null) {
                        motorista.IdCarroceria = carroceria.Id;
                    }
                    */
                    if (incluir)
                    {
                        await regraMotorista.inserir(motorista);
                    }
                    else {
                        await regraMotorista.alterar(motorista);
                    }
                    motorista = await regraMotorista.pegar(motorista.Id);
                    /*
                    var motoristaAtual = await regraMotorista.pegar(_usuario.Id);
                    if (motoristaAtual != null)
                    {
                        await regraMotorista.alterar(motorista);
                    }
                    else {
                        await regraMotorista.inserir(motorista);
                    }
                    */
                    //var usuarioCadastrado = await regraUsuario.pegar(_usuario.Id);
                    //motorista = await regraMotorista.pegar(_usuario.Id);
                    //var regraUsuario = UsuarioFactory.create();
                    regraUsuario.gravarAtual(motorista.Usuario);
                    regraMotorista.gravarAtual(motorista);
                    UserDialogs.Instance.HideLoading();
                    AoCompletar?.Invoke(this, motorista);
                }
                else {
                    //UserDialogs.Instance.HideLoading();
                    AoCompletar?.Invoke(this, Motorista);
                }
            }
            catch (Exception erro)
            {
                if (Gravar) {
                    UserDialogs.Instance.HideLoading();
                }
                await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
            }
        }
    }
}