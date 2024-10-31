using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Frete.Model;
using Emagine.Login.BLL;

namespace Emagine.Frete.BLL
{
    public class TipoVeiculoBLL: RestAPIBase
    {
        public async Task<List<TipoVeiculoInfo>> listar()
        {
            return await queryGet<List<TipoVeiculoInfo>>(GlobalUtils.URLAplicacao + "/api/veiculo-tipo/listar");
        }
    }
}
