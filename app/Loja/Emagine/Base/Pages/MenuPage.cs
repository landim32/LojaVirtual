using Emagine.Base.Cells;
using Emagine.Base.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Emagine.Base.Estilo;
using Emagine;
using Emagine.Base.Utils;

namespace Emagine.Base.Pages
{
    public class MenuPage : ContentPage
    {
        private Image _logoImage;
        private Image _fotoImage;
        private StackLayout _mainLayout;
        private StackLayout _usuarioLayout;
        private Label _nomeLabel;
        private Label _tipoLabel;
        private ListView _listView;
        private Label _versaoLabel;

        private IList<MenuItemInfo> _menus = new List<MenuItemInfo>();

        public string NomeApp {
            get {
                return Title;
            }
            set {
                Title = value;
            }
        }

        public IList<MenuItemInfo> Menus {
            get {
                return _menus;
            }
            set {
                _menus = value;
                _listView.ItemsSource = _menus;
            }
        }

        public MenuPage()
        {
            Title = "...";
            Style = Estilo.Estilo.Current[Estilo.Estilo.MENU_PAGINA];

            _menus = new List<MenuItemInfo>();
            inicializarComponente();
            atualizarUsuario();
            Content = _mainLayout;
        }

        public void atualizarUsuario()
        {
            _mainLayout.Children.Clear();
            //_mainLayout.Children.Add(_logoImage);
            _mainLayout.Children.Add(_listView);
            _mainLayout.Children.Add(_versaoLabel);

            /*
            if (PreferenciaUtils.Perfil == PerfilEnum.Corretor)
            {
                _listView.Footer = new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.FillAndExpand,
                    Spacing = 3,
                    Margin = new Thickness(0, 10, 0, 0),
                    Children = {
                        _OnlineSwitch,
                        _OnlineLabel
                    }
                };
            }
            else
            {
                _listView.Footer = null;
            }
            */
            //_listView.ItemsSource = listarMenu();
        }

        private void inicializarComponente()
        {
            _logoImage = new Image
            {
                Source = "logo_menu.png",
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                VerticalOptions = LayoutOptions.Center,
                WidthRequest = 150,
                HeightRequest = 150
            };
            
            _listView = new ListView
            {
                HasUnevenRows = true,
                RowHeight = -1,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Estilo.Current[Estilo.Estilo.MENU_LISTA],
                ItemTemplate = new DataTemplate(typeof(MenuItemCell))
            };
            _listView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _listView.ItemsSource = _menus;

            _listView.ItemTapped += (sender, e) => {
                MenuItemInfo item = (MenuItemInfo)e.Item;
                if (item.aoClicar != null) {
                    item.aoClicar(sender, new EventArgs());
                    _listView.SelectedItem = null;
                    if (App.Current.MainPage is MasterDetailPage) {
                        ((MasterDetailPage)App.Current.MainPage).IsPresented = false;
                    }
                }
            };

            _fotoImage = new Image
            {
                Margin = new Thickness(0, 5, 0, 0),
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Source = "imovel_corretor.png",
                WidthRequest = 40,
                HeightRequest = 40,
                Aspect = Aspect.AspectFit
            };

            _nomeLabel = new Label
            {
                TextColor = Color.FromHex("#ffffff"),
                FontSize = 18,
                FontAttributes = FontAttributes.Bold,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start
            };

            _tipoLabel = new Label
            {
                TextColor = Color.FromHex("#ffffff"),
                FontSize = 15,
                FontAttributes = FontAttributes.Italic,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start
            };

            _usuarioLayout = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                Margin = new Thickness(0, 10),
                Spacing = 10,
                Children = {
                    _fotoImage,
                    new StackLayout {
                        Orientation = StackOrientation.Vertical,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Start,
                        Spacing = 0,
                        Children = {
                            _nomeLabel,
                            _tipoLabel
                        }
                    }
                }
            };

            _versaoLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Estilo.Current[Estilo.Estilo.MENU_TEXTO],
                FontSize = 11,
                HorizontalTextAlignment = TextAlignment.Center
            };
            _versaoLabel.Text = "Versão: " + VersionUtils.getVersion() + " (build " + VersionUtils.getBuild() + ")";

            _mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                Padding = new Thickness(5, 25, 5, 5),
                BackgroundColor = Color.Transparent
            };
        }
    }
}
