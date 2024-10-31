using System;
using FormsPlugin.Iconize;
using Xamarin.Forms;
using Emagine.Base.Estilo;

namespace Emagine.Frete.Cells
{
    public class PointTransporteCell : ViewCell
    {
        private Label _Text;
        private IconImage _Icon;

        public PointTransporteCell()
        {
            inicializarComponente();
            View = new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                BackgroundColor = Estilo.Current.PrimaryColor,
                Margin = new Thickness(10, 2, 10, 2),
                Padding = 5,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            _Icon,
                            _Text
                        }
                    }
                }
            };
        }


        private void inicializarComponente()
        {
            _Icon = new IconImage
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Center,
                IconSize = 16,
                WidthRequest = 20,
                IconColor = Color.White
            };
            _Icon.SetBinding(IconImage.IconProperty, new Binding("Icone"));
            _Text = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                TextColor = Color.White,
                FontSize = 16
            };
            _Text.SetBinding(Label.TextProperty, new Binding("TextLabel"));
        }
    }
}
