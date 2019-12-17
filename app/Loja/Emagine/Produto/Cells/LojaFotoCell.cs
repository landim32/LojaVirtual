using Emagine.Base.Estilo;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Cells
{
    public class LojaFotoCell: ViewCell
    {
        private Image _FotoImage;
        private Label _NomeLabel;
        private Label _EnderecoLabel;
        private Label _DistanciaLabel;

        public LojaFotoCell() {
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
                    Margin = new Thickness(0, 7),
                    Children = {
                        _FotoImage,
                        new StackLayout {
                            Orientation = StackOrientation.Vertical,
                            HorizontalOptions = LayoutOptions.FillAndExpand,
                            VerticalOptions = LayoutOptions.Start,
                            Margin = new Thickness(0, 1),
                            Spacing = 0,
                            Children = {
                                _NomeLabel,
                                _EnderecoLabel,
                                _DistanciaLabel
                            }
                        },
                    }
                }
            };
        }

        private void inicilizarComponente() {
            _FotoImage = new Image {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Aspect = Aspect.AspectFit,
                WidthRequest = 120,
                HeightRequest = 120
                //Style = Estilo.Current[Estilo.LISTA_ITEM]
            };
            _FotoImage.SetBinding(Image.SourceProperty, new Binding("FotoUrl"));
            _NomeLabel = new Label {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                FontAttributes = FontAttributes.Bold,
                TextColor = Estilo.Current.BarBackgroundColor,
                FontSize = 18,
            };
            _NomeLabel.SetBinding(Label.TextProperty, new Binding("Nome"));
            _EnderecoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Italic,
                TextColor = Color.FromHex("#7c7c7c"),
                FontSize = 13,
            };
            _EnderecoLabel.SetBinding(Label.TextProperty, new Binding("EnderecoCompleto"));
            _DistanciaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                TextColor = Color.FromHex("#7c7c7c"),
                FontSize = 14,
            };
            _DistanciaLabel.SetBinding(Label.TextProperty, new Binding("DistanciaStr"));
        }
    }
}
