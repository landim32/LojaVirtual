using Emagine.Base.BLL;
using Emagine.Base.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.CRM.BLL
{
    public class ProjetoBLL: RestAPIBase
    {
        public Task<string> pegarProximaUrl(string comecaCom) {
            var url = string.Format("{0}/api/projeto/pegar-proxima-url", GlobalUtils.URLAplicacao);
            var param = new Object[1] { comecaCom };
            return queryPut<string>(url, param);
        }

        public Task<int> inserirUrl(string url)
        {
            var urlApp = string.Format("{0}/api/projeto/inserir-url", GlobalUtils.URLAplicacao);
            var param = new Object[1] { url };
            return queryPut<int>(urlApp, param);
        }

        public Task excluirUrl(string url)
        {
            var urlApp = string.Format("{0}/api/projeto/excluir-url", GlobalUtils.URLAplicacao);
            var param = new Object[1] { url };
            return execPut(urlApp, param);
        }
    }
}
