using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Xfx;

namespace Emagine.Login.Pages
{
    public class UsuarioFormPage : ContentPage
    {
        private UsuarioInfo _usuario;

        protected StackLayout _mainStack;
        protected XfxEntry _NomeEntry;
        protected XfxEntry _EmailEntry;
        protected XfxEntry _TelefoneEntry;
        protected XfxEntry _CPFEntry;
        protected XfxEntry _SenhaEntry;
        protected XfxEntry _ConfirmaEntry;
        protected Button _CadastroButton;

        public event EventHandler<UsuarioInfo> AoCadastrar;

        public UsuarioFormPage()
        {
            Title = "Cadastro";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            inicializarComponente();

            _mainStack = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Padding = 5,
            };
            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Content = _mainStack
            };
            atualizarTela();
        }

        public bool Gravar { get; set; } = true;

        public virtual UsuarioInfo Usuario {
            get {
                if (_usuario == null) {
                    _usuario = new UsuarioInfo();
                    _usuario.Situacao = SituacaoEnum.Ativo;
                }
                _usuario.Nome = _NomeEntry.Text;
                _usuario.Email = _EmailEntry.Text;
                _usuario.Telefone = _TelefoneEntry.Text;
                _usuario.CpfCnpj = _CPFEntry.Text;
                _usuario.Senha = _SenhaEntry.Text;
                return _usuario;
            }
            set {
                _usuario = value;
                if (_usuario != null) {
                    _NomeEntry.Text = _usuario.Nome;
                    _EmailEntry.Text = _usuario.Email;
                    _TelefoneEntry.Text = _usuario.Telefone;
                    _CPFEntry.Text = _usuario.CpfCnpj;
                }
                atualizarTela();
            }
        }

        protected virtual void atualizarTela() {
            var usuario = this.Usuario;
            _mainStack.Children.Clear();
            _mainStack.Children.Add(_NomeEntry);
            _mainStack.Children.Add(_EmailEntry);
            _mainStack.Children.Add(_TelefoneEntry);
            _mainStack.Children.Add(_CPFEntry);
            if (!(usuario != null && usuario.Id > 0))
            {
                _mainStack.Children.Add(_SenhaEntry);
                _mainStack.Children.Add(_ConfirmaEntry);
            }
            _mainStack.Children.Add(_CadastroButton);
        }

        protected virtual void inicializarComponente()
        {
            _NomeEntry = new XfxEntry
            {
                Placeholder = "Nome",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                ErrorDisplay = ErrorDisplay.None,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL]
            };
            _EmailEntry = new XfxEntry
            {
                Placeholder = "Email",
                Keyboard = Keyboard.Email,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                ErrorDisplay = ErrorDisplay.None,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL]
            };
            _TelefoneEntry = new XfxEntry
            {
                Placeholder = "Celular",
                Keyboard = Keyboard.Telephone,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                ErrorDisplay = ErrorDisplay.None,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL]
            };
            _CPFEntry = new XfxEntry
            {
                Placeholder = "CPF / CNPJ",
                Keyboard = Keyboard.Numeric,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                ErrorDisplay = ErrorDisplay.None,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL]
            };
            _SenhaEntry = new XfxEntry
            {
                Placeholder = "Preencha sua senha",
                IsPassword = true,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                ErrorDisplay = ErrorDisplay.None,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL]
            };
            _ConfirmaEntry = new XfxEntry
            {
                Placeholder = "Confirme sua senha",
                IsPassword = true,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                ErrorDisplay = ErrorDisplay.None,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL]
            };

            _CadastroButton = new Button()
            {
                Text = "CADASTRAR",
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _CadastroButton.Clicked += CadastroClicked;
        }

        protected virtual void efetuarCadastro(UsuarioInfo usuario) {
            AoCadastrar?.Invoke(this, usuario);
        }

        protected virtual bool validar() {
            var usuario = this.Usuario;
            if (string.IsNullOrEmpty(_NomeEntry.Text))
            {
                DisplayAlert("Aviso", "Preencha o seu nome.", "Fechar");
                _NomeEntry.Focus();
                return false;
            }
            if (string.IsNullOrEmpty(_EmailEntry.Text))
            {
                DisplayAlert("Aviso", "Preencha o seu email.", "Fechar");
                _EmailEntry.Focus();
                return false;
            }
            if (string.IsNullOrEmpty(_TelefoneEntry.Text))
            {
                DisplayAlert("Aviso", "Preencha o seu celular.", "Fechar");
                _TelefoneEntry.Focus();
                return false;
            }
            if (!(usuario != null && usuario.Id > 0))
            {
                if (string.IsNullOrEmpty(_SenhaEntry.Text))
                {
                    DisplayAlert("Aviso", "Preencha a senha.", "Fechar");
                    _SenhaEntry.Focus();
                    return false;
                }
                if (string.IsNullOrEmpty(_ConfirmaEntry.Text))
                {
                    DisplayAlert("Aviso", "Preencha a confirmação de senha.", "Fechar");
                    _ConfirmaEntry.Focus();
                    return false;
                }
                if (string.Compare(_SenhaEntry.Text, _ConfirmaEntry.Text) != 0)
                {
                    DisplayAlert("Aviso", "A senha não está batendo com a confirmação.", "Fechar");
                    _SenhaEntry.Focus();
                    return false;
                }
            }
            return true;
        }

        protected virtual async void CadastroClicked(object sender, EventArgs e)
        {
            if (!validar()) {
                return;
            }
            var usuario = this.Usuario;
            if (Gravar) {
                usuario = await enviarUsuario(this.Usuario);
                if (usuario != null) {
                    efetuarCadastro(usuario);
                }
            }
            else {
                efetuarCadastro(usuario);
            }
        }

        private async Task<UsuarioInfo> enviarUsuario(UsuarioInfo usuario) {
            var regraUsuario = UsuarioFactory.create();
            UserDialogs.Instance.ShowLoading("Enviando...");
            try
            {
                var id_usuario = 0;
                if (usuario.Id > 0)
                {
                    await regraUsuario.alterar(usuario);
                    id_usuario = usuario.Id;
                }
                else {
                    id_usuario = await regraUsuario.inserir(usuario);
                }
                var usuarioCadastrado = await regraUsuario.pegar(id_usuario);

                UserDialogs.Instance.HideLoading();
                if (usuarioCadastrado != null)
                {
                    regraUsuario.gravarAtual(usuarioCadastrado);
                    return usuarioCadastrado;
                }
                else
                {
                    string mensagem = string.Format("Nenhum usuário encontrado com o ID {0}.", id_usuario);
                    await DisplayAlert("Aviso", mensagem, "Fechar");
                }
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                //UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                await DisplayAlert("Erro", erro.Message, "Entendi");
            }
            return null;
        }
    }
}