using Emagine.Base.Model;
using Plugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Cells
{
    public class MenuItemCell : ViewCell
    {
        private Image _IconeImage;
        private IconImage _IconeFAImage;
        private Label _TituloLabel;
        private StackLayout _mainLayout;

        public MenuItemCell()
        {
            inicializarComponente();
            _mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                Margin = new Thickness(0, 5),
                BackgroundColor = Color.Transparent,
                VerticalOptions = LayoutOptions.Fill,

            };
            var menuItem = (MenuItemInfo) BindingContext;
            if (menuItem != null) {
                if (menuItem.ImagemExibe) {
                    _mainLayout.Children.Add(_IconeImage);
                }
                if (menuItem.IconeFAExibe) {
                    _mainLayout.Children.Add(_IconeFAImage);
                }
            }
            _mainLayout.Children.Add(_TituloLabel);
            View = _mainLayout;
        }

        protected override void OnBindingContextChanged()
        {
            base.OnBindingContextChanged();

            if (BindingContext == null)
                return;

            var menuItem = (MenuItemInfo)BindingContext;
            if (menuItem != null)
            {
                _mainLayout.Children.Clear();
                if (menuItem.ImagemExibe)
                {
                    _mainLayout.Children.Add(_IconeImage);
                }
                if (menuItem.IconeFAExibe)
                {
                    _mainLayout.Children.Add(_IconeFAImage);
                }
            }
            _mainLayout.Children.Add(_TituloLabel);
        }

        private void inicializarComponente() {
            _IconeImage = new Image
            {
                Margin = new Thickness(0, 3, 5, 3),
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                WidthRequest = 40,
                HeightRequest = 40,
                //Margin = new Thickness(0,0,0,0)
            };
            _IconeImage.SetBinding(Image.SourceProperty, new Binding("Icone"));
            _IconeImage.SetBinding(Image.IsVisibleProperty, new Binding("ImagemExibe"));

            _IconeFAImage = new IconImage
            {
                Margin = new Thickness(0, 3, 5, 3),
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                //IconSize = 22,
                Style = Estilo.Estilo.Current[Estilo.Estilo.MENU_ICONE]
                //IconColor = Color.FromHex("#ffffff")
                //Margin = new Thickness(0,0,0,0)
            };
            _IconeFAImage.SetBinding(IconImage.IconProperty, new Binding("IconeFA"));
            _IconeImage.SetBinding(Image.IsVisibleProperty, new Binding("IconeFAExibe"));

            _TituloLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Center,
                Style = Estilo.Estilo.Current[Estilo.Estilo.MENU_TEXTO]
                //FontSize = 18,
                //TextColor = Color.FromHex("#ffffff")
                //FontFamily = "Roboto-Condensed",
                //Margin = new Thickness(0,0,0,0)
            };
            _TituloLabel.SetBinding(Label.TextProperty, new Binding("Titulo"));
        }
    }
}
