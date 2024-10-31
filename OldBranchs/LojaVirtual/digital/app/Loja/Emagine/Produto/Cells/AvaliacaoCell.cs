using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Base.Pages;
using Emagine.Pedido.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Cells
{
    public class AvaliacaoCell: ViewCell
    {
        private Label _comentarioLabel;
        private NotaControl _notaControl;

        public AvaliacaoCell() {
            inicilizarComponente();
            View = new Frame
            {
                Style = Estilo.Current[Estilo.LISTA_FRAME_PADRAO],
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    HorizontalOptions = LayoutOptions.FillAndExpand,
                    VerticalOptions = LayoutOptions.Start,
                    Margin = new Thickness(0, 3),
                    Children = {
                        _comentarioLabel,
                        _notaControl
                    }
                }
            };
        }

        private void inicilizarComponente() {
            _comentarioLabel = new Label {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                TextColor = Estilo.Current.BarBackgroundColor,
                FontSize = 12,
            };
            _comentarioLabel.SetBinding(Label.TextProperty, new Binding("Comentario"));

            _notaControl = new NotaControl {
                HorizontalOptions = LayoutOptions.End,
                VerticalOptions = LayoutOptions.Start,
                WidthRequest = 100,
                IconSize = 16
            };
            _notaControl.SetBinding(NotaControl.NotaProperty, new Binding("Nota"));
            _notaControl.AoClicar += (sender, nota) => {
                return;
            };
        }
    }
}
