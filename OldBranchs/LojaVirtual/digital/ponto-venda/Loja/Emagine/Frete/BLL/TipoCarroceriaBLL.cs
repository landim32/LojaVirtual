using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Frete.Model;
using Emagine.Login.BLL;

namespace Emagine.Frete.BLL
{
    public class TipoCarroceriaBLL: RestAPIBase
    {
        public async Task<List<TipoCarroceriaInfo>> listar()
        {
            return await queryGet<List<TipoCarroceriaInfo>>(GlobalUtils.URLAplicacao + "/api/carroceria/listar");
        }
    }
}
