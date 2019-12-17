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
using Xfx;

namespace Emagine.Login.Pages
{
    public class AlteraSenhaPage : ContentPage
    {
        public UsuarioInfo Usuario { get; set; }

        private XfxEntry _SenhaAntiga;
        private XfxEntry _NovaSenha;
        private XfxEntry _ConfirmaNovaSenha;
        private Button _ResetaSenha;

        public AlteraSenhaPage()
        {
            Title = "Alterar Senha";
            Style = Estilo.Current[Estilo.TELA_PADRAO];
            inicializarComponente();
            Content = new StackLayout
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Orientation = StackOrientation.Vertical,
                Padding = 5,
                Children = {
                    _SenhaAntiga,
                    _NovaSenha,
                    _ConfirmaNovaSenha,
                    _ResetaSenha
                }
            };
        }

        private bool validarAlteracaoSenha()
        {
            if (Usuario == null) {
                DisplayAlert("Erro", "Usuário não informado!", "Entendi");
                return false;
            }
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
            _SenhaAntiga = new XfxEntry
            {
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "Senha antiga",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                ErrorDisplay = ErrorDisplay.None,
                IsPassword = true
            };
            _NovaSenha = new XfxEntry
            {
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "Nova senha",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                ErrorDisplay = ErrorDisplay.None,
                IsPassword = true
            };
            _ConfirmaNovaSenha = new XfxEntry
            {
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "Repita a nova senha",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                ErrorDisplay = ErrorDisplay.None,
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
                if (validarAlteracaoSenha())
                {
                    UserDialogs.Instance.ShowLoading("Alterando senha...");
                    var regraUsuario = UsuarioFactory.create();
                    var alteraSenha = new UsuarioTrocaSenhaInfo {
                        IdUsuario = Usuario.Id,
                        Senha = _NovaSenha.Text,
                        SenhaAntiga = _SenhaAntiga.Text
                    };
                    try
                    {
                        await regraUsuario.trocarSenha(alteraSenha);
                        await DisplayAlert("Sucesso", "Sua senha foi alterada!", "Entendi");
                        await Navigation.PopToRootAsync();
                    }
                    catch (Exception erro) {
                        UserDialogs.Instance.HideLoading();
                        UserDialogs.Instance.Alert(erro.Message, "Erro", "Ok");
                    }
                }
            };
        }

    }
}