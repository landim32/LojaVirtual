using Emagine.Produto.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Factory
{
    public static class CarrinhoFactory
    {
        private static CarrinhoBLL _Carrinho;

        public static CarrinhoBLL create()
        {
            if (_Carrinho == null)
            {
                _Carrinho = new CarrinhoBLL();
            }
            return _Carrinho;
        }

    }
}
