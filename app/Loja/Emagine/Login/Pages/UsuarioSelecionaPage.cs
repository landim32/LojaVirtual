using Emagine.Base.Estilo;
using Emagine.Login.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Login.Pages
{
	public class UsuarioSelecionaPage : ContentPage
	{
        private Button _CadastroButton;
        private Button _LoginButton;
        private Button _SenhaButton;

        public event EventHandler<UsuarioInfo> AoSelecionar;

        public UsuarioInfo Usuario {
            get
            {
                return (UsuarioInfo)BindingContext;
            }
            set {
                BindingContext = value;
            }
        }

        public UsuarioSelecionaPage ()
		{
            Title = "Criar / Entrar em sua conta";
            inicializarComponente();
            Content = new StackLayout()
            {
                Padding = new Thickness(10, 20),
                Spacing = 5,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Orientation = StackOrientation.Vertical,
                Style = Estilo.Current[Estilo.BG_PADRAO],
                Children = {
                    _CadastroButton,
                    _LoginButton,
                    _SenhaButton
                }
            };
        }

        private void inicializarComponente() {
            _CadastroButton = new Button()
            {
                Margin = new Thickness(0, 10, 0, 0),
                Text = "CRIAR CONTA",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _CadastroButton.Clicked += (sender, e) => {
                var cadastroPage = new UsuarioFormPage
                {
                    Title = "Cria sua conta",
                    Usuario = Usuario,
                    Gravar = true
                };
                cadastroPage.AoCadastrar += (s2, usuario) =>
                {
                    AoSelecionar?.Invoke(this, usuario);
                };
                Navigation.PushAsync(cadastroPage);
            };
            _LoginButton = new Button()
            {
                Margin = new Thickness(0, 10, 0, 0),
                Text = "JÁ POSSUO UM CONTA",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };
            _LoginButton.Clicked += (sender, e) => {
                var loginPage = new LoginPage {
                    Title = "Entre com sua conta"
                };
                loginPage.AoLogar += (s2, usuario) => {
                    AoSelecionar?.Invoke(this, usuario);
                };
                Navigation.PushAsync(loginPage);
            };
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
	}
}