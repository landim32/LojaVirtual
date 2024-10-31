using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Treino.Cells
{
    public class TreinoCell : ViewCell
    {
        private Label _nomeLabel;

        public TreinoCell()
        {
            inicilizarComponente();
            View = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(5, 7),
                Children = {
                    _nomeLabel,
                }
            };
        }

        private void inicilizarComponente()
        {
            _nomeLabel = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Style = Estilo.Current[Estilo.LISTA_ITEM],
                FontSize = 22
            };
            _nomeLabel.SetBinding(Label.TextProperty, new Binding("Nome"));
        }
    }
}