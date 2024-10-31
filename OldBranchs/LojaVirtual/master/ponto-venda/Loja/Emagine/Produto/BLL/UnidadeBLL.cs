using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Web;

namespace Emagine.Produto.BLL
{
    public class UnidadeBLL : RestAPIBase
    {
        public async Task<IList<UnidadeInfo>> listar(int idLoja)
        {
            return await queryGet<IList<UnidadeInfo>>(GlobalUtils.URLAplicacao + "/api/unidade/listar/" + idLoja);
        }

        public async Task<UnidadeInfo> pegarPorNome(int idLoja, string nome)
        {
            var url = string.Format("{0}/api/unidade/{1}/pegar-por-nome?p={2}", GlobalUtils.URLAplicacao, idLoja, HttpUtility.UrlEncode(nome));
            return await queryGet<UnidadeInfo>(url);
        }

        public async Task<int> inserir(UnidadeInfo unidade)
        {
            string url = GlobalUtils.URLAplicacao + "/api/unidade/inserir";
            var args = new List<object>() { unidade };
            return await queryPut<int>(url, args.ToArray());
        }

        public async Task<int> alterar(UnidadeInfo unidade)
        {
            string url = GlobalUtils.URLAplicacao + "/api/unidade/alterar";
            var args = new List<object>() { unidade };
            return await queryPut<int>(url, args.ToArray());
        }
    }
}
