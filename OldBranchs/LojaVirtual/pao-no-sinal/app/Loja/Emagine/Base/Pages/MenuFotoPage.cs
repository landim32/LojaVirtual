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
using FormsPlugin.Iconize;
using Emagine.Login.Model;
using ImageCircle.Forms.Plugin.Abstractions;

namespace Emagine.Base.Pages
{
    public class MenuFotoPage
    {
        //private Image _logoImage;
        private CircleImage _fotoImage;
        private StackLayout _mainLayout;
        private StackLayout _usuarioLayout;
        private Label _nomeLabel;
        //private Label _tipoLabel;
        private ListView _listView;
        //private Label _telefoneLabel;
        private Label _versaoLabel;

        private IList<MenuItemInfo> _menus = new List<MenuItemInfo>();

        public string NomeApp
        {
            get
            {
                return Title;
            }
            set
            {
                Title = value;
            }
        }

        private UsuarioInfo _usuario;

        public UsuarioInfo Usuario
        {
            get
            {
                return _usuario;
            }
            set
            {
                _usuario = value;
                if (_usuario != null)
                {
                    if (!_mainLayout.Children.Contains(_usuarioLayout))
                    {
                        _mainLayout.Children.Insert(0, _usuarioLayout);
                    }
                    _nomeLabel.Text = _usuario.Nome;
                    if (!string.IsNullOrEmpty(_usuario.FotoUrl))
                    {
                        _fotoImage.Source = new UriImageSource
                        {
                            Uri = new Uri(_usuario.FotoUrl),
                            CachingEnabled = true,
                            CacheValidity = new TimeSpan(5, 0, 0, 0)
                        };
                    }
                }
                else
                {
                    if (_mainLayout.Children.Contains(_usuarioLayout))
                    {
                        _mainLayout.Children.Remove(_usuarioLayout);
                    }
                }
            }
        }

        public IList<MenuItemInfo> Menus
        {
            get
            {
                return _menus;
            }
            set
            {
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
            //_mainLayout.Children.Add(_logoImage);
            _mainLayout.Children.Add(_listView);
            /*
            _mainLayout.Children.Add(new StackLayout {
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Orientation = StackOrientation.Horizontal,
                Children = {
                    new IconImage {
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Icon = "fa-phone",
                        IconColor = Estilo.Estilo.Current.MenuTexto.TextColor,
                        IconSize = 18,
                    },
                    new Label {
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Style = Estilo.Estilo.Current[Estilo.Estilo.MENU_TEXTO],
                        FontAttributes = FontAttributes.Bold,
                        FontSize = 16,
                        Text = "0800 111 2233"
                    }
                }
            });
            */
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
            /*
            _logoImage = new Image
            {
                Source = "logo_menu.png",
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                VerticalOptions = LayoutOptions.Center,
                WidthRequest = 150,
                HeightRequest = 150
            };
            */

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
                if (item.aoClicar != null)
                {
                    item.aoClicar(sender, new EventArgs());
                    _listView.SelectedItem = null;
                    if (App.Current.MainPage is MasterDetailPage)
                    {
                        ((MasterDetailPage)App.Current.MainPage).IsPresented = false;
                    }
                }
            };

            _fotoImage = new CircleImage
            {
                Margin = new Thickness(0, 5, 0, 0),
                VerticalOptions = LayoutOptions.CenterAndExpand,
                HorizontalOptions = LayoutOptions.Start,
                Source = "anonimo.png",
                BorderColor = Color.White,
                BorderThickness = 5,
                WidthRequest = 140,
                HeightRequest = 140,
                Aspect = Aspect.AspectFit
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

            /*
            _tipoLabel = new Label
            {
                TextColor = Color.FromHex("#ffffff"),
                FontSize = 15,
                FontAttributes = FontAttributes.Italic,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start
            };
            */

            _usuarioLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                Margin = new Thickness(10, 5),
                Spacing = 10,
                Children = {
                    _fotoImage,
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
