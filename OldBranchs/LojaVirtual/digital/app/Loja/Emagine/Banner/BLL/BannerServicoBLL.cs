using Emagine.Banner.Controls;
using Emagine.Pedido.BLL;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Banner.BLL
{
    public class BannerServicoBLL: PedidoServicoBLL
    {
        public BannerView BannerAtual { get; set; }

        protected async override Task executar()
        {
            await base.executar();
            if (BannerAtual != null) {
                BannerAtual.rotacionar();
            }
            return;
        }
    }
}
