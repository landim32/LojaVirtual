using Emagine.Banner.BLL;
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
        private static ProdutoBLL _produto;

        public static ProdutoBLL create()
        {
            if (_produto == null) {
                _produto = new ProdutoBLL();
            }
            return _produto;
        }

    }
}
