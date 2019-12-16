using System;
using System.Threading.Tasks;
using Emagine.Base.Estilo;
using Emagine.Login.Pages;
using Rg.Plugins.Popup.Pages;
using Xamarin.Forms;

namespace Emagine.Frete.Popups
{
    public class CadastroTipoPopup : PopupPage
    {
        private Button _UsuarioButton;
        private Button _FornecedorButton;
        private bool _Modal;

        public CadastroTipoPopup(bool modal)
        {
            _Modal = modal;
            Title = "Tipo de Cadastro";
            BackgroundColor = Color.White;
            WidthRequest = 0.8;
            HeightRequest = 0.6;
            inicializarComponente();
            Content = new StackLayout
            {
                Padding = new Thickness(5, 10),
                Orientation = StackOrientation.Vertical,
                Spacing = 10,
                Children = {
                    new Label { 
                        HorizontalTextAlignment = TextAlignment.Center,
                        Text = "Selecione o tipo de cadastro que deseja efetuar",
                        FontSize = 18
                    },
                    _UsuarioButton,
                    _FornecedorButton
                }
            };
        }

        protected override Task OnAppearingAnimationEnd()
        {
            return Content.FadeTo(0.5);
        }

        protected override Task OnDisappearingAnimationBegin()
        {
            return Content.FadeTo(1);
        }

        protected override bool OnBackButtonPressed()
        {
            // Prevent hide popup
            //return base.OnBackButtonPressed();
            return true;
        }

        private void inicializarComponente() {
            _UsuarioButton = new Button()
            {
                Text = "USUÁRIO",
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(10, 0)
            };
            _UsuarioButton.Clicked += (sender, e) => {
                if(_Modal){
                    Navigation.PopModalAsync();
                    var navPage = (NavigationPage)Application.Current.MainPage;
                    // Rodrigo Landim - 16/03
                    //navPage.PushAsync(new CadastroPage(CadastroTipoEnum.Usuario));
                }
                else {
                    // Rodrigo Landim - 16/03
                    //Navigation.PushAsync(new CadastroPage(CadastroTipoEnum.Usuario));
                }
            };
            _FornecedorButton = new Button()
            {
                Text = "FORNECEDOR",
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(10, 0)
            };
            _FornecedorButton.Clicked += (sender, e) => {
                if (_Modal)
                {
                    Navigation.PopModalAsync();
                    var navPage = (NavigationPage)Application.Current.MainPage;
                    // Rodrigo Landim - 16/03
                    //navPage.PushAsync(new CadastroPage(CadastroTipoEnum.Fornecedor));
                }
                else
                {
                    // Rodrigo Landim - 16/03
                    //Navigation.PushAsync(new CadastroPage(CadastroTipoEnum.Fornecedor));
                }
            };
        }
    }
}

