using Emagine.Base.Model;
using Emagine.Base.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Base.Pages
{
    public class RootPage : MasterDetailPage
    {
        private string _NomeApp;

        public event EventHandler AoAtualizarMenu;

        public RootPage()
        {   
            Master = new MenuPage();
        }

        public string NomeApp {
            get {
                return _NomeApp;
            }
            set {
                _NomeApp = value;
                this.Menu.NomeApp = _NomeApp;
            }
        }

        protected MenuPage Menu {
            get {
                return (MenuPage)Master;
            }
        }

        public IList<MenuItemInfo> Menus {
            get
            {
                return ((MenuPage)Master).Menus;
            }
            set {
                ((MenuPage)Master).Menus = value;
            }
        }

        public Page PaginaAtual {
            get {
                return ((IconNavigationPage)Detail).CurrentPage;
            }
            set {
                Detail = new IconNavigationPage(value) {
                    BarTextColor = Estilo.Estilo.Current.BarTitleColor,
                    BarBackgroundColor = Estilo.Estilo.Current.BarBackgroundColor,
                    
                };
            }
        }

        public void PushAsync(Page page) {
            var navPage = ((IconNavigationPage)Detail);
            navPage.PushAsync(page);
        }

        public void atualizarMenu() {
            AoAtualizarMenu?.Invoke(this, new EventArgs());
        }
    }
}
