using Acr.UserDialogs;
using Emagine.Base.Controls;
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
    [Obsolete("Use UsuarioFormPage")]
    public class CadastroPage : ContentPage
    {
        private Entry _NomeEntry;
        private Entry _SobrenomeEntry;
        private Entry _CelularEntry;
        private Entry _EmailEntry;
        private Entry _SenhaEntry;
        private Entry _ConfirmaEntry;
        private FotoImageButton _FotoCNHButton;
        private Button _CadastroButton;

        public CadastroPage()
        {
            Title = "Cadastro";

            inicializarComponente();

            var mainStack = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Padding = 5,
                Children = {
                    new Image{
                        Source = "logo.png",
                        HeightRequest = 80,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Center
                    }/*,
                    _NomeEntry,
                    _SobrenomeEntry,
                    _CelularEntry,
                    _EmailEntry,
                    */
                }
            };
            if (_NomeEntry != null)
            {
                mainStack.Children.Add(_NomeEntry);
            }
            if (_SobrenomeEntry != null)
            {
                mainStack.Children.Add(_SobrenomeEntry);
            }
            if (_CelularEntry != null)
            {
                mainStack.Children.Add(_CelularEntry);
            }
            if (_EmailEntry != null)
            {
                mainStack.Children.Add(_EmailEntry);
            }


            var gridLayout = new Grid
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
            };
            gridLayout.Children.Add(_FotoCNHButton, 0, 0);

            gridLayout.RowDefinitions.Add(new RowDefinition
            {
                Height = new GridLength(140)
            });
            gridLayout.RowDefinitions.Add(new RowDefinition
            {
                Height = new GridLength(140)
            });
            mainStack.Children.Add(gridLayout);

            if (_SenhaEntry != null)
            {
                mainStack.Children.Add(_SenhaEntry);
            }

            if (_ConfirmaEntry != null)
            {
                mainStack.Children.Add(_ConfirmaEntry);
            }
            mainStack.Children.Add(_CadastroButton);


            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Content = mainStack
            };
        }

        private void inicializarComponente()
        {
                _NomeEntry = new Entry
                {
                    Placeholder = "Nome",
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill
                };
                _SobrenomeEntry = new Entry
                {
                    Placeholder = "Sobrenome",
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill
                };
                _EmailEntry = new Entry
                {
                    Placeholder = "Email",
                    Keyboard = Keyboard.Email,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill
                };
                _CelularEntry = new Entry
                {
                    Placeholder = "Celular",
                    Keyboard = Keyboard.Telephone,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill
                };
            

                _FotoCNHButton = new FotoImageButton()
                {
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Source = "FotoCNH.png"
                };
        

                _SenhaEntry = new Entry
                {
                    Placeholder = "Preencha sua senha",
                    IsPassword = true,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill
                };
                _ConfirmaEntry = new Entry
                {
                    Placeholder = "Confirme sua senha",
                    IsPassword = true,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill
                };

            _CadastroButton = new Button()
            {
                Text = "CADASTRAR",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _CadastroButton.Clicked += async (sender, e) =>
            {

                if (_NomeEntry != null && string.IsNullOrEmpty(_NomeEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha o seu nome.", "Fechar");
                    return;
                }
                if (_SobrenomeEntry != null && string.IsNullOrEmpty(_SobrenomeEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha o seu sobrenome.", "Fechar");
                    return;
                }
                if (_CelularEntry != null && string.IsNullOrEmpty(_CelularEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha o seu celular.", "Fechar");
                    return;
                }
                if (_EmailEntry != null && string.IsNullOrEmpty(_EmailEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha o seu email.", "Fechar");
                    return;
                }
                if (_SenhaEntry != null && string.IsNullOrEmpty(_SenhaEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha a senha.", "Fechar");
                    return;
                }
                if (_ConfirmaEntry != null && string.IsNullOrEmpty(_ConfirmaEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha a confirmação de senha.", "Fechar");
                    return;
                }
                if (_SenhaEntry != null && _ConfirmaEntry != null && string.Compare(_SenhaEntry.Text, _ConfirmaEntry.Text) != 0)
                {
                    await DisplayAlert("Aviso", "A senha não está batendo com a confirmação.", "Fechar");
                    return;
                }


                var regraUsuario = UsuarioFactory.create();
                var usuario = new UsuarioInfo
                {
                    Nome = _NomeEntry.Text + " " + _SobrenomeEntry.Text,
                    Email = _EmailEntry.Text,
                    Telefone = _CelularEntry.Text,
                    Senha = _SenhaEntry.Text,
                    Situacao = SituacaoEnum.Ativo
                };
                UserDialogs.Instance.ShowLoading("Enviando...");
                try
                {
                    var id_usuario = await regraUsuario.inserir(usuario);
                    var usuarioCadastrado = await regraUsuario.pegar(id_usuario);

                    regraUsuario.gravarAtual(usuarioCadastrado);
                    UserDialogs.Instance.HideLoading();
                    if (usuarioCadastrado != null)
                    {
                        regraUsuario.gravarAtual(usuarioCadastrado);
                        //App.Current.MainPage = new NavigationPage(new PrincipalPage());
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
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                }

            };

        }
    }
}