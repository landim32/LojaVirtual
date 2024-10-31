using Emagine.Produto.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Factory
{
    public static class ProdutoFactory
    {
        private static ProdutoBLL _Produto;

        public static ProdutoBLL create()
        {
            if (_Produto == null)
            {
                _Produto = new ProdutoBLL();
            }
            return _Produto;
        }

    }
}
