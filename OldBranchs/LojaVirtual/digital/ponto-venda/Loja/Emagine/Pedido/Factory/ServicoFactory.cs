using Emagine.Banner.BLL;
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
    public static class ServicoFactory
    {
        private static BaseServicoBLL _servico;

        public static BaseServicoBLL create()
        {
            if (_servico == null)
            {
                //_servico = new ServicoBLL();
                _servico = new BannerServicoBLL();
            }
            return _servico;
        }

    }
}
