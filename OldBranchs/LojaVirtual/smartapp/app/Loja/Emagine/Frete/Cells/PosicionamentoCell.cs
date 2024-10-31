using System;
using Emagine;
using Emagine.Base.Estilo;
using Xamarin.Forms;

namespace Emagine.Frete.Cells
{
    public class PosicionamentoCell : ViewCell
    {
        private Label _Titulo;

        public PosicionamentoCell()
        {
            inicializarComponente();
            View = new StackLayout
            {
                Margin = 10,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _Titulo,
                    new Label {
                        Text = "(Toque para ver no mapa)",
                        TextColor = Color.LightGray,
                        HorizontalOptions = LayoutOptions.Fill,
                        HorizontalTextAlignment = TextAlignment.Center,
                        Margin = new Thickness(0, 0, 0, 5)
                    }
                }
            };
        }

        private void inicializarComponente()
        {
            _Titulo = new Label
            {
                Style = Estilo.Current[Estilo.TITULO3]
            };
            _Titulo.SetBinding(Label.TextProperty, new Binding("TextoCell"));


        }
    }
}
