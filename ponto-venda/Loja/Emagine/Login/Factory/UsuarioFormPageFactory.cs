using Emagine.Login.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Login.Factory
{
    public static class UsuarioFormPageFactory
    {
        public static Type Tipo { get; set; } = typeof(UsuarioFormPage);

        public static UsuarioFormPage create()
        {
            return (UsuarioFormPage)Activator.CreateInstance(Tipo);
        }
    }
}
