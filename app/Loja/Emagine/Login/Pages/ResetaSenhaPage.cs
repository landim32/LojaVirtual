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
    public class ResetaSenhaPage : ContentPage
    {
        private string _Email;

        private Entry _SenhaAntiga;
        private Entry _NovaSenha;
        private Entry _ConfirmaNovaSenha;
        private Button _ResetaSenha;

        public ResetaSenhaPage(string email)
        {
            _Email = email;
            Title = "Nova senha";
            inicializarComponente();
            Content = new StackLayout
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Orientation = StackOrientation.Vertical,
                Children = {
                    _SenhaAntiga,
                    _NovaSenha,
                    _ConfirmaNovaSenha,
                    _ResetaSenha
                }
            };
        }

        private bool validaResete()
        {
            if (String.IsNullOrEmpty(_SenhaAntiga.Text))
            {
                DisplayAlert("Atenção", "Preencha a senha antiga.", "Entendi");
                return false;
            }
            if (String.IsNullOrEmpty(_NovaSenha.Text))
            {
                DisplayAlert("Atenção", "Preencha a nova senha antiga.", "Entendi");
                return false;
            }
            if (String.IsNullOrEmpty(_ConfirmaNovaSenha.Text) && _NovaSenha.Text != _ConfirmaNovaSenha.Text)
            {
                DisplayAlert("Atenção", "Repita a nova senha.", "Entendi");
                return false;
            }
            return true;
        }

        private void inicializarComponente()
        {
            _SenhaAntiga = new Entry
            {
                Placeholder = "Senha antiga",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                IsPassword = true
            };
            _NovaSenha = new Entry
            {
                Placeholder = "Nova senha",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                IsPassword = true
            };
            _ConfirmaNovaSenha = new Entry
            {
                Placeholder = "Repita a nova senha",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                IsPassword = true
            };
            _ResetaSenha = new Button()
            {
                Margin = new Thickness(0, 10, 0, 0),
                Text = "RESETAR MINHA SENHA",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _ResetaSenha.Clicked += async (sender, e) =>
            {
                if (validaResete())
                {
                    UserDialogs.Instance.ShowLoading("Aguarde...");
                    if (await UsuarioFactory.create().resetarSenha(new UsuarioNovaSenhaInfo { Email = _Email, Senha = _NovaSenha.Text, SenhaAntiga = _SenhaAntiga.Text }))
                    {
                        UserDialogs.Instance.HideLoading();
                        await UserDialogs.Instance.AlertAsync("Sua senha foi resetada", "Sucesso !", "Ok");
                        Navigation.PopToRootAsync();
                    }
                    else
                    {
                        UserDialogs.Instance.HideLoading();
                        UserDialogs.Instance.Alert("Ocorreu uma falha ao resetar a senha. Tente novamente mais tarde.", "Falha", "Ok");
                    }
                }
            };
        }

    }
}