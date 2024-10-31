using Emagine.Frete.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Frete.Factory
{
    public static class CadastroMotoristaPageFactory
    {
        public static Type Tipo { get; set; } = typeof(CadastroMotoristaPage);

        public static CadastroMotoristaPage create()
        {
            return (CadastroMotoristaPage)Activator.CreateInstance(Tipo);
        }
    }
}
