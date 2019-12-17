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
using Plugin.Iconize;
using Emagine.Login.Model;

namespace Emagine.Base.Pages
{
    public class MenuPage : ContentPage
    {
        private StackLayout _mainLayout;
        private StackLayout _usuarioLayout;
        private Label _nomeLabel;
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

        private UsuarioInfo _usuario;

        public UsuarioInfo Usuario {
            get {
                return _usuario;
            }
            set {
                _usuario = value;
                if (_usuario != null)
                {
                    if (!_mainLayout.Children.Contains(_usuarioLayout))
                    {
                        _mainLayout.Children.Insert(0, _usuarioLayout);
                    }
                    _nomeLabel.Text = _usuario.Nome;
                }
                else {
                    if (_mainLayout.Children.Contains(_usuarioLayout)) {
                        _mainLayout.Children.Remove(_usuarioLayout);
                    }
                }
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
            atualizarTela();
            Content = _mainLayout;
        }

        public void atualizarTela()
        {
            _mainLayout.Children.Clear();
            _mainLayout.Children.Add(_listView);
            _mainLayout.Children.Add(_versaoLabel);
        }

        private void inicializarComponente()
        {
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

            _nomeLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                HorizontalTextAlignment = TextAlignment.Center,
                Style = Estilo.Estilo.Current[Estilo.Estilo.MENU_TEXTO],
                FontAttributes = FontAttributes.Bold,
                FontSize = 18
            };

            _usuarioLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                Margin = new Thickness(10, 5),
                Spacing = 10,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Vertical,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Start,
                        Spacing = 0,
                        Children = {
                            _nomeLabel
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
