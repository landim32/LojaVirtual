using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class UsuarioSelecionePage : ContentPage
    {
        protected Button _clienteButton;
        protected Button _motoristaButton;

        public event EventHandler AoSelecionaCliente;
        public event EventHandler AoSelecionaMotorista;

        public UsuarioSelecionePage()
        {
            Title = "CRIAR UMA NOVA CONTA";
            inicializarComponente();
            Content = new StackLayout {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Spacing = 15,
                Padding = 5,
                Children = {
                    new Image {
                        Source = "logo.png",
                        Margin = new Thickness(20, 20, 20, 10),
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Center
                    },
                    _clienteButton,
                    _motoristaButton
                }
            };
        }

        private void inicializarComponente() {
            _clienteButton = new Button()
            {
                Text = "PASSAGEIROS",
                Style = Estilo.Current[Estilo.BTN_AVISO],
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Italic,
                FontSize = 20,
            };
            _clienteButton.Clicked += (sender, e) => {
                AoSelecionaCliente?.Invoke(this, e);
            };

            _motoristaButton = new Button()
            {
                Text = "MARINHEIROS",
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Italic,
                FontSize = 20,
            };
            _motoristaButton.Clicked += (sender, e) => {
                AoSelecionaMotorista?.Invoke(this, e);
            };
        }
    }
}