using Emagine.Login.BLL;
using Emagine.Pedido.BLL;
using Emagine.Produto.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pedido.Factory
{
    public static class PedidoFactory
    {
        private static PedidoBLL _Pedido;

        public static PedidoBLL create()
        {
            if (_Pedido == null)
            {
                _Pedido = new PedidoBLL();
            }
            return _Pedido;
        }

    }
}
