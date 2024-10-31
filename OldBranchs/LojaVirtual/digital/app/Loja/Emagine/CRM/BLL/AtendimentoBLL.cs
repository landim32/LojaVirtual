using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.CRM.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.CRM.BLL
{
    public class AtendimentoBLL: RestAPIBase
    {
        public Task<AtendimentoInfo> pegar(int id_atendimento)
        {
            var url = string.Format("{0}/api/atendimento/pegar/{1}", GlobalUtils.URLAplicacao, id_atendimento);
            return queryGet<AtendimentoInfo>(url);
        }

        public Task<AtendimentoInfo> pegarPorUrl(string url) {
            var urlApp = string.Format("{0}/api/atendimento/pegar-por-url", GlobalUtils.URLAplicacao);
            var param = new Object[1] { url };
            return queryPut<AtendimentoInfo>(urlApp, param);
        }

        public Task<int> inserir(AtendimentoInfo atendimento)
        {
            var url = string.Format("{0}/api/atendimento/inserir", GlobalUtils.URLAplicacao);
            var param = new Object[1] { atendimento };
            return queryPut<int>(url, param);
        }

        public Task alterar(AtendimentoInfo atendimento)
        {
            var url = string.Format("{0}/api/atendimento/alterar", GlobalUtils.URLAplicacao);
            var param = new Object[1] { atendimento };
            return execPut(url, param);
        }
    }
}
