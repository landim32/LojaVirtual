using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Login.Pages
{
    public class EmailSenhaPage : ContentPage
    {
        private Entry _Email;
        private Button _RecuperarSenha;
        private Button _ResetarSenha;

        public EmailSenhaPage()
        {
            Title = "Recuperar senha";
            inicializarComponente();
            Content = new StackLayout
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Orientation = StackOrientation.Vertical,
                Children = {
                    _Email,
                    _RecuperarSenha,
                    _ResetarSenha
                }
            };
        }

        private bool validaProximo()
        {
            if (String.IsNullOrEmpty(_Email.Text))
            {
                DisplayAlert("Atenção", "Preencha o email.", "Entendi");
                return false;
            }
            return true;
        }

        private void inicializarComponente()
        {
            _Email = new Entry
            {
                Placeholder = "Preencha o seu email.",
                Keyboard = Keyboard.Email,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };
            _RecuperarSenha = new Button()
            {
                Margin = new Thickness(0, 10, 0, 0),
                Text = "ENVIAR SENHA PARA O EMAIL",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _RecuperarSenha.Clicked += async (sender, e) =>
            {
                if (validaProximo())
                {
                    UserDialogs.Instance.ShowLoading("Aguarde...");
                    if (await UsuarioFactory.create().recuperarSenha(new UsuarioNovaSenhaInfo { Email = _Email.Text }))
                    {
                        UserDialogs.Instance.HideLoading();
                        await UserDialogs.Instance.AlertAsync("A senha foi enviada para o seu email", "Sucesso !", "Ok");
                        Navigation.PopToRootAsync();
                    }
                    else
                    {
                        UserDialogs.Instance.HideLoading();
                        UserDialogs.Instance.Alert("Ocorreu uma falha ao enviar a senha. Verifique se o email informado está correto.", "Falha", "Ok");
                    }
                }
            };
            _ResetarSenha = new Button()
            {
                Margin = new Thickness(0, 10, 0, 0),
                Text = "ALTERAR A SENHA",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PADRAO]
            };
            _ResetarSenha.Clicked += (sender, e) => {
                if (validaProximo())
                {
                    Navigation.PushAsync(new ResetaSenhaPage(_Email.Text));
                }
            };
        }

    }
}