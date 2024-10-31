using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Endereco.Cells
{
    public class LogradouroCell: ViewCell
    {
        private Label _NomeLabel;

        public LogradouroCell() {
            inicilizarComponente();
            View = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 7),
                Children = {
                    _NomeLabel
                }
            };
        }

        private void inicilizarComponente() {
            _NomeLabel = new Label {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Style = Estilo.Current[Estilo.ENDERECO_ITEM]
            };
            _NomeLabel.SetBinding(Label.TextProperty, new Binding("Logradouro"));
        }
    }
}
