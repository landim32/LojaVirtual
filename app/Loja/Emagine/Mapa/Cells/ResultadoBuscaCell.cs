using System;
using FormsPlugin.Iconize;
using Xamarin.Forms;
using Emagine.Base.Estilo;

namespace Emagine.Frete.Cells
{
    public class ResultadoBuscaCell : ViewCell
    {
        private Label _Text;

        public ResultadoBuscaCell()
        {
            inicializarComponente();
            View = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 5),
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new IconImage{
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Start,
                                Icon = "fa-map-marker",
                                IconSize = 14,
                                WidthRequest = 16,
                                IconColor = Estilo.Current.PrimaryColor
                            },
                            _Text
                        }
                    }
                }
            };
        }


        private void inicializarComponente()
        {
            _Text = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                LineBreakMode = LineBreakMode.TailTruncation,
                FontSize = 14
            };
            _Text.SetBinding(Label.TextProperty, new Binding("."));
        }
    }
}
