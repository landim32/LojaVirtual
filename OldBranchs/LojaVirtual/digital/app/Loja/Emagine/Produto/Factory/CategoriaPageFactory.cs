using Emagine.Produto.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Factory
{
    public class CategoriaPageFactory
    {
        public static Type Tipo { get; set; } = typeof(CategoriaBasePage);

        public static CategoriaBasePage create()
        {
            return (CategoriaBasePage)Activator.CreateInstance(Tipo);
        }
    }
}