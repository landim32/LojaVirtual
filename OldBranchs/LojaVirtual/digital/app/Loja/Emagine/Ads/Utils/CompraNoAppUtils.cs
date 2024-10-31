using Emagine.IBLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Utils
{
    public static class CompraNoAppUtils
    {
        private static ICompraNoApp _compraNoApp;

        public static bool comprar(string idProduto) {
            if (_compraNoApp == null)
                _compraNoApp = DependencyService.Get<ICompraNoApp>();
            return _compraNoApp.comprar(idProduto);
        }

        public static bool possuiProduto(string idProduto) {
            if (_compraNoApp == null)
                _compraNoApp = DependencyService.Get<ICompraNoApp>();
            return _compraNoApp.possuiProduto(idProduto);
        }
    }
}
