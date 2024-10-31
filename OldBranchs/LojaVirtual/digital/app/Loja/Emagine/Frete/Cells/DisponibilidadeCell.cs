using System;

using Xamarin.Forms;

namespace Emagine.Frete.Cells
{
    public class DisponibilidadeCell : ViewCell
    {
        private Label _Titulo;
        private Label _Local;

        public DisponibilidadeCell()
        {
            inicializarComponente();
            View = new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = 10,
                Children = {
                    _Titulo,
                    _Local,
                    new Label {
                        Text = "(Toque para ver mais)",
                        TextColor = Color.LightGray,
                        HorizontalOptions = LayoutOptions.Fill,
                        HorizontalTextAlignment = TextAlignment.Center
                    }
                }
            };
        }

        private void inicializarComponente()
        {
            _Titulo = new Label
            {
                FontSize = 18,
                HorizontalOptions = LayoutOptions.Start
            };
            _Local = new Label
            {
                FontSize = 15,
                HorizontalOptions = LayoutOptions.Start
            };
            _Titulo.SetBinding(Label.TextProperty, new Binding("Titulo"));
            _Local.SetBinding(Label.TextProperty, new Binding("LocalidadeLbl"));
        }
    }
}

