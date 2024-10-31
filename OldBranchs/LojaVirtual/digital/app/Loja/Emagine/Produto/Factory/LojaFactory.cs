using Emagine.Produto.BLL;
using Emagine.Produto.IBLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Factory
{
    public static class LojaFactory
    {
        private const string TYPE_NAME = "Emagine.Produto.BLL.{0}.LojaBLL";
        private static ILojaBLL _loja;

        public static string Tipo { get; set; } = "Base";

        public static ILojaBLL create()
        {
            if (_loja == null)
            {
                var assembly = Assembly.GetCallingAssembly();
                var typeName = string.Format(TYPE_NAME, Tipo);
                _loja = (ILojaBLL)assembly.CreateInstance(typeName);
            }
            return _loja;
        }

    }
}
