using Rg.Plugins.Popup.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Rg.Plugins.Popup.Extensions;

namespace Emagine.Utils
{
    public class NavigationX: INavigation
    {
        private static NavigationX _current;
        public static NavigationX create(Page page) {
            _current = new NavigationX(page);
            return _current;
        }

        Page _paginaPai;
        Page _paginaAtual;
        PopupPage _popupAtual;
        bool carregandoPagina = false;

        public NavigationX(Page page = null) {
            if (page != null)
                _paginaPai = page;
            else
                _paginaPai = Application.Current.MainPage;
        }

        public IReadOnlyList<Page> ModalStack
        {
            get {
                return _paginaPai.Navigation.ModalStack;
            }
        }

        public IReadOnlyList<Page> NavigationStack
        {
            get {
                return _paginaPai.Navigation.NavigationStack;
            }
        }

        public void InsertPageBefore(Page page, Page before)
        {
            _paginaPai.Navigation.InsertPageBefore(page, before);
        }

        public Task<Page> PopAsync()
        {
            return _paginaPai.Navigation.PopAsync();
        }

        public Task<Page> PopAsync(bool animated)
        {
            return _paginaPai.Navigation.PopAsync(animated);
        }

        public Task<Page> PopModalAsync()
        {
            return _paginaPai.Navigation.PopModalAsync();
        }

        public Task<Page> PopModalAsync(bool animated)
        {
            return _paginaPai.Navigation.PopModalAsync(animated);
        }

        public Task PopToRootAsync()
        {
            return _paginaPai.Navigation.PopToRootAsync();
        }

        public Task PopToRootAsync(bool animated)
        {
            return _paginaPai.Navigation.PopToRootAsync(animated);
        }

        public Task PushAsync(Page page)
        {
            if (!carregandoPagina && (_paginaAtual == null || _paginaAtual.GetType() != page.GetType())) { 
                carregandoPagina = true;
                _paginaAtual = page;
                _paginaAtual.Appearing += (sender, e) => {
                    carregandoPagina = false;
                };
                return _paginaPai.Navigation.PushAsync(page);
            }
            return null;
        }

        public Task PushAsync(Page page, bool animated)
        {
            if (!carregandoPagina && (_paginaAtual == null || _paginaAtual.GetType() != page.GetType()))
            {
                carregandoPagina = true;
                _paginaAtual = page;
                _paginaAtual.Appearing += (sender, e) => {
                    carregandoPagina = false;
                };
                return _paginaPai.Navigation.PushAsync(page, animated);
            }
            return null;
        }

        public Task PushModalAsync(Page page)
        {
            if (!carregandoPagina && (_paginaAtual == null || _paginaAtual.GetType() != page.GetType()))
            {
                carregandoPagina = true;
                _paginaAtual = page;
                _paginaAtual.Appearing += (sender, e) => {
                    carregandoPagina = false;
                };
                return _paginaPai.Navigation.PushModalAsync(page);
            }
            return null;
        }

        public Task PushModalAsync(Page page, bool animated)
        {
            if (!carregandoPagina && (_paginaAtual == null || _paginaAtual.GetType() != page.GetType()))
            {
                carregandoPagina = true;
                _paginaAtual = page;
                _paginaAtual.Appearing += (sender, e) => {
                    carregandoPagina = false;
                };
                return _paginaPai.Navigation.PushModalAsync(page, animated);
            }
            return null;
        }

        public Task PushPopupAsyncX(PopupPage page, bool animated = false) {
            if (!carregandoPagina && (_popupAtual == null || _popupAtual.GetType() != page.GetType()))
            {
                carregandoPagina = true;
                _popupAtual = page;
                _popupAtual.Appearing += (sender, e) =>
                {
                    carregandoPagina = false;
                };
              return _paginaPai.Navigation.PushPopupAsync(page, animated);
            }
            return null;
        }

        public void RemovePage(Page page)
        {
            _paginaPai.Navigation.RemovePage(page);
        }
    }
}
