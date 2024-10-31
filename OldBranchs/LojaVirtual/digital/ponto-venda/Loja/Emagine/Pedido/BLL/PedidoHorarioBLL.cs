using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Pedido.Model;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pedido.BLL
{
    public class PedidoHorarioBLL : RestAPIBase
    {
        public async Task<IList<PedidoHorarioInfo>> listar(int idLoja)
        {
            string url = GlobalUtils.URLAplicacao + "/api/horario/listar/" + idLoja.ToString();
            return await queryGet<IList<PedidoHorarioInfo>>(url);
        }
    }
}
