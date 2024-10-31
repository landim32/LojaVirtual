using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using FormsPlugin.Iconize;
using Xamarin.Forms;
using Emagine.Base.Estilo;

namespace Emagine.Frete.Cells
{
    public class FreteMotoristaOldCell : ViewCell
    {
        private Label _OrigemDestinoLabel;
        private Label _TituloLabel;
        private Image _ImgAux;


        public FreteMotoristaOldCell()
        {
            inicializarComponente();


            View = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Margin = 10,
                        Children = {
                            new StackLayout
                            {
                                Orientation = StackOrientation.Vertical,
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Children = {
                                    _TituloLabel,
                                    _OrigemDestinoLabel
                                }
                            },
                            _ImgAux
                        }
                    },
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
            _TituloLabel = new Label
            {
                FontSize = 18,
                HorizontalOptions = LayoutOptions.Start
            };
            _OrigemDestinoLabel = new Label
            {
                FontSize = 15,
                HorizontalOptions = LayoutOptions.Start
            };
            _ImgAux = new Image
            {
                Source = "P.png",
                HeightRequest = 20,
                HorizontalOptions = LayoutOptions.End,
                VerticalOptions = LayoutOptions.Center
            };
            _TituloLabel.SetBinding(Label.TextProperty, new Binding("TituloFreteMotoristaLbl"));
            _OrigemDestinoLabel.SetBinding(Label.TextProperty, new Binding("OrigemDestinoStr"));
            _ImgAux.SetBinding(Image.IsVisibleProperty, new Binding("MostraP"));
        }
    }
}
