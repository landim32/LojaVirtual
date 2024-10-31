using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Cells
{
    public class HorarioEntregaCell: ViewCell
    {
        private Label _NomeLabel;
        private Label _QuantidadeLabel;

        public HorarioEntregaCell() {
            inicilizarComponente();
            View = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(10, 7),
                Children = {
                    _NomeLabel,
                    new Frame {
                        Style = Estilo.Current[Estilo.LISTA_BADGE_FUNDO],
                        WidthRequest = 20,
                        Content = _QuantidadeLabel
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
            _QuantidadeLabel = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                HorizontalTextAlignment = TextAlignment.Center,
                Style = Estilo.Current[Estilo.LISTA_BADGE_TEXTO]
            };
            _QuantidadeLabel.SetBinding(Label.TextProperty, new Binding("Quantidade"));
        }
    }
}
