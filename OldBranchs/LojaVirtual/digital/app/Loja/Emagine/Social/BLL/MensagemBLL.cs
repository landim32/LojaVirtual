using Emagine.Base.BLL;
using Emagine.Base.Model;
using Emagine.Base.Utils;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Social.BLL
{
    public class MensagemBLL: RestAPIBase
    {
        public async Task<IList<MensagemInfo>> listarAviso(int idUsuario)
        {
            string url = GlobalUtils.URLAplicacao + "/api/aviso/" + idUsuario;
            return await queryGet<IList<MensagemInfo>>(url);
        }
    }
}
