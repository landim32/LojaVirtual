using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Endereco.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Endereco.BLL
{
    public class CidadeBLL : RestAPIBase
    {
        public async Task<List<CidadeInfo>> pegar(string busca)
        {
            return await queryGet<List<CidadeInfo>>(GlobalUtils.URLAplicacao + "/api/cidade/buscar?p=" + Uri.EscapeDataString(busca));
        }
    }
}
