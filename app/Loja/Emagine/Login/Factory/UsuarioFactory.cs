using Emagine.Login.BLL;
using Emagine.Login.IBLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Login.Factory
{
    public static class UsuarioFactory
    {
        private const string TYPE_NAME = "Emagine.Login.BLL.{0}.UsuarioBLL";
        private static IUsuarioBLL _Usuario;

        public static string Tipo { get; set; } = "Base";

        public static IUsuarioBLL create()
        {
            if (_Usuario == null)
            {
                /*
                var assembly = Assembly.GetCallingAssembly();
                var typeName = string.Format(TYPE_NAME, Tipo);
                _Usuario = (IUsuarioBLL)assembly.CreateInstance(typeName);
                */
                _Usuario = new BLL.Mobile.UsuarioBLL();
            }
            return _Usuario;
        }

    }
}
