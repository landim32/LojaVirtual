using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Pedido.Cells
{
    public class PedidoHorarioCell: ViewCell
    {
        private Label _NomeLabel;

        public PedidoHorarioCell() {
            inicilizarComponente();
            View = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(5, 7),
                Children = {
                    _NomeLabel,
                }
            };
        }

        private void inicilizarComponente() {
            _NomeLabel = new Label {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Style = Estilo.Current[Estilo.LISTA_ITEM],
                FontSize = 22
            };
            _NomeLabel.SetBinding(Label.TextProperty, new Binding("Horario"));
        }
    }
}
