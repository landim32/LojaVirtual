using System;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Frete.Cells
{
    public class TipoVeiculoCell : ViewCell
    {
        private Label _Nome;
        private Image _Foto;

        public TipoVeiculoCell()
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
                        Spacing = 5,
                        Children = {
                            _Foto,
                            _Nome
                        }
                    }
                }
            };
        }


        private void inicializarComponente()
        {
            _Nome = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                FontSize = 20,
                Margin = 5
            };
            _Nome.SetBinding(Label.TextProperty, new Binding("Nome"));
            _Foto = new Image
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                WidthRequest = 100,
                HeightRequest = 25,
                Aspect = Aspect.AspectFit,
                Margin = 5
            };
            _Foto.SetBinding(Image.SourceProperty, new Binding("Foto"));
            //_Foto.SetBinding(Image.SourceProperty, new Binding("FotoUrl"));
        }
    }
}
