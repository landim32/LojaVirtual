using Emagine.Produto.BLL;
using Loja.Emagine.Produto.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Factory
{
    public static class CategoriaFactory
    {
        private static CategoriaBLL _Categoria;

        public static CategoriaBLL create()
        {
            if (_Categoria == null)
            {
                _Categoria = new CategoriaBLL();
            }
            return _Categoria;
        }

    }
}
