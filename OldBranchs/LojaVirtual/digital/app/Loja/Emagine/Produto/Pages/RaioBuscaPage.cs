using Emagine.Base.Estilo;
using Emagine.Produto.Factory;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class RaioBuscaPage : ContentPage
    {
        private Slider _barraSlider;
        private Label _raioLabel;
        private Button _avancarButton;

        public string BotaoTexto {
            get {
                return _avancarButton.Text;
            }
            set {
                _avancarButton.Text = value;
            }
        }

        public event EventHandler AoAvancar;

        public RaioBuscaPage()
        {
            Title = "Raio de Busca";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                Padding = 5,
                Spacing = 5,
                Children = {
                    _barraSlider,
                    _raioLabel,
                    _avancarButton
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            var regraLoja = LojaFactory.create();
            _barraSlider.Value = regraLoja.RaioBusca;
        }

        private void inicializarComponente() {
            _barraSlider = new Slider {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(5, 15),
                Maximum = 310,
                Minimum = 10,
                Value = 100
            };
            _barraSlider.ValueChanged += (sender, e) => {
                _raioLabel.Text = e.NewValue.ToString("N0") + " km";
            };
            _raioLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.Center,
                FontSize = 24,
                FontAttributes = FontAttributes.Bold,
                Text = "100 km"
            };

            _avancarButton = new Button {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Avançar"
            };
            _avancarButton.Clicked += (sender, e) => {
                var regraLoja = LojaFactory.create();
                regraLoja.RaioBusca = Convert.ToInt32(_barraSlider.Value);
                AoAvancar?.Invoke(sender, e);
            };
        }
    }
}