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
    public class ClienteBLL: RestAPIBase
    {
        public Task<ClienteInfo> pegar(int idCliente) {
            var url = string.Format("{0}/api/cliente/pegar/{1}", GlobalUtils.URLAplicacao, idCliente);
            return queryGet<ClienteInfo>(url);
        }

        public Task<ClienteInfo> pegarPorEmail(string email)
        {
            var url = string.Format("{0}/api/cliente/pegar-por-email", GlobalUtils.URLAplicacao);
            var param = new Object[1] { email };
            return queryPut<ClienteInfo>(url, param);
        }

        public Task<int> inserir(ClienteInfo cliente)
        {
            var url = string.Format("{0}/api/cliente/inserir", GlobalUtils.URLAplicacao);
            var param = new Object[1] { cliente };
            return queryPut<int>(url, param);
        }
    }
}
