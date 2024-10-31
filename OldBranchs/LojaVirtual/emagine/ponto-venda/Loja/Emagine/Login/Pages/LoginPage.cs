using Acr.UserDialogs;
using Emagine.Base.BLL;
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

namespace Emagine.Login.Pages
{
    public class LoginPage : ContentPage
    {
        private Entry _UsuarioTextbox;
        private Entry _SenhaTextbox;
        private Button _LogarButton;
        private Button _SenhaButton;

        public event EventHandler<UsuarioInfo> AoLogar;

        public string Email {
            get {
                return _UsuarioTextbox.Text;
            }
            set {
                _UsuarioTextbox.Text = value;
            }
        }

        public string Senha {
            get {
                return _SenhaTextbox.Text;
            }
            set {
                _SenhaTextbox.Text = value;
            }
        }

        public LoginPage()
        {
            Title = "Login";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            inicializarComponente();

            //_UsuarioTextbox.Text = "rodrigo@emagine.com.br";
            //_SenhaTextbox.Text = "pikpro6";

            Content = new StackLayout()
            {
                Padding = new Thickness(10, 20),
                Spacing = 5,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Orientation = StackOrientation.Vertical,
                //Style = Estilo.Current[Estilo.BG_PADRAO],
                Children = {
                    new Label {
                        Text = "Usuário:",
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new IconImage {
                                Icon = "fa-user",
                                //IconSize = 20,
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO],
                            },
                            _UsuarioTextbox
                        }
                    },
                    new Label() {
                        Text = "Senha:",
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Start,
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
                            _SenhaTextbox
                        }
                    },
                    _LogarButton,
                    _SenhaButton
                }
            };
        }

        private void inicializarComponente()
        {
            _UsuarioTextbox = new Entry
            {
                Placeholder = "Preencha o seu email.",
                Keyboard = Keyboard.Email,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO]
            };
            _SenhaTextbox = new Entry()
            {
                Placeholder = "Preencha a sua senha.",
                IsPassword = true,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO]
            };
            _LogarButton = new Button()
            {
                Margin = new Thickness(0, 10, 0, 0),
                Text = "ENTRAR",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _LogarButton.Clicked += logarButtonClicked;

            _SenhaButton = new Button()
            {
                Margin = new Thickness(0, 10, 0, 0),
                Text = "ESQUECI/ALTERAR MINHA SENHA",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PADRAO]
            };
            _SenhaButton.Clicked += (sender, e) => {
                Navigation.PushAsync(new EmailSenhaPage());
            };
        }

        protected virtual async Task executarLogin() {
            var regraUsuario = UsuarioFactory.create();
            var id_usuario = await regraUsuario.logar(Email, Senha);
            var usuario = await regraUsuario.pegar(id_usuario);
            if (usuario != null) {
                regraUsuario.gravarAtual(usuario);
                executarEventoLogar(usuario);
            }
            else {
                string mensagem = string.Format("Nenhum usuário encontrado com o ID {0}.", id_usuario);
                await DisplayAlert("Aviso", mensagem, "Fechar");
            }
        }

        protected void executarEventoLogar(UsuarioInfo usuario) {
            AoLogar?.Invoke(this, usuario);
        }

        private async void logarButtonClicked(object sender, EventArgs e)
        {
            UserDialogs.Instance.ShowLoading("Entrando...");
            try
            {
                await executarLogin();
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                //UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                await DisplayAlert("Erro", erro.Message, "Fechar");
            }
        }
    }
}