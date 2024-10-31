using Emagine.Banner.Model;
using Emagine.Base.BLL;
using Emagine.Base.Utils;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Banner.BLL
{
    public class BannerPecaBLL: RestAPIBase
    {
        public async Task<IList<BannerPecaInfo>> gerar(BannerFiltroInfo filtro)
        {
            string url = GlobalUtils.URLAplicacao + "/api/banner/peca/gerar";
            return await queryPut<IList<BannerPecaInfo>>(url, new object[1] { filtro });
        }
    }
}
