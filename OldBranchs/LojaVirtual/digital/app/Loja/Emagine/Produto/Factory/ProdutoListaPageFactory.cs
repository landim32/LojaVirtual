using Emagine.Produto.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Factory
{
    public class ProdutoListaPageFactory
    {
        public static Type Tipo { get; set; } = typeof(ProdutoListaPage);

        public static ProdutoBasePage create()
        {
            return (ProdutoBasePage)Activator.CreateInstance(Tipo);
        }
    }
}