using System;
using FormsPlugin.Iconize;
using Plugin.Iconize;
using Rg.Plugins.Popup.Extensions;
using Xamarin.Forms;
using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Login.Pages;
using Emagine.Frete.Popups;
using Emagine.Frete.Pages;
using Emagine;
using Emagine.Base.Pages;
using Emagine.Login.Model;
using Emagine.Frete.Factory;
using Emagine.Frete.Utils;

namespace Emagine.Frete.Pages
{
    public class InicialPage : ContentPage
    {
        protected StackLayout _mainLayout;
        protected Button _acessarContaButton;
        protected Button _criarContaButton;

        public InicialPage()
        {
            NavigationPage.SetHasNavigationBar(this, false);
            inicializarComponente();
            _mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Spacing = 5,
                Padding = 5,
                /*
                Children = {
                    new Image{
                        Source = "logo.png",
                        Margin = new Thickness(20),
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Center
                    },
                    new StackLayout{
                        Orientation = StackOrientation.Horizontal,
                        VerticalOptions = LayoutOptions.Fill,
                        HorizontalOptions = LayoutOptions.Start,
                        Margin = new Thickness(10, 0),
                        Spacing = 10,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.Fill,
                                TextColor = Color.Black,
                                //TextColor = Estilo.Current.PrimaryColor,
                                HorizontalTextAlignment = TextAlignment.Center,
                                Text = "Seja bem vindo",
                                FontSize = 18
                            }
                        }
                    },
                    _CriarContaButton,
                    _AcessarContaButton
                }
                */
            };
            atualizarTela();
            Content = _mainLayout;
        }

        protected virtual void atualizarTela() {
            _mainLayout.Children.Clear();
            _mainLayout.Children.Add(_acessarContaButton);
            _mainLayout.Children.Add(_criarContaButton);
        }

        public void inicializarComponente() {
            _criarContaButton = new Button()
            {
                Text = "Criar conta",
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
            };
            _criarContaButton.Clicked += criarContaClicked;
            _acessarContaButton = new Button()
            {
                Text = "Entrar",
                Style = Estilo.Current[Estilo.BTN_SUCESSO],
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
            };
            _acessarContaButton.Clicked += acessarContaClicked;
        }

        protected virtual void executarLogin() {
            ((App)App.Current).inicializarApp();
        }

        protected virtual async void criarContaClicked(object sender, EventArgs e)
        {
            await LoginUtils.criarUsuario(() => {
                executarLogin();
            });
        }

        protected virtual async void acessarContaClicked(object sender, EventArgs e)
        {
            var loginPage = LoginUtils.gerarLogin(() => {
                executarLogin();
            });
            await Navigation.PushAsync(loginPage);
        }
    }
}

