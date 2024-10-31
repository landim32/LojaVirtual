using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Cells
{
    public class LojaCell: ViewCell
    {
        private Label _NomeLabel;
        private Label _DistanciaLabel;

        public LojaCell() {
            inicilizarComponente();
            View = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 7),
                Children = {
                    _NomeLabel,
                    new Frame {
                        Style = Estilo.Current[Estilo.LISTA_BADGE_FUNDO],
                        Content = _DistanciaLabel
                    }
                }
            };
        }

        private void inicilizarComponente() {
            _NomeLabel = new Label {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Style = Estilo.Current[Estilo.LISTA_ITEM]
            };
            _NomeLabel.SetBinding(Label.TextProperty, new Binding("Nome"));
            _DistanciaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                HorizontalTextAlignment = TextAlignment.End,
                Style = Estilo.Current[Estilo.LISTA_BADGE_TEXTO]
            };
            _DistanciaLabel.SetBinding(Label.TextProperty, new Binding("DistanciaStr"));
        }
    }
}
