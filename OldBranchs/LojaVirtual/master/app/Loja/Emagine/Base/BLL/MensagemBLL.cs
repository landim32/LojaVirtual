using Emagine.Base.Model;
using Emagine.Base.Utils;
using System;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace Emagine.Base.BLL
{
    public class MensagemBLL : RestAPIBase
    {
        public async Task<bool> enviar(MensagemInfo mensagem)
        {
            try{
                var args = new List<object>();
                args.Add(mensagem);
                var ret = await queryPut(GlobalUtils.URLAplicacao + "/api/mensagem/enviar", args.ToArray());
                return true;
            } catch{
                return false;
            }

        }
    }
}
