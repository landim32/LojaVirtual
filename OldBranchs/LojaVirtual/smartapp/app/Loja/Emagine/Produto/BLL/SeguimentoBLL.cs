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
    public class SeguimentoBLL : RestAPIBase
    {
        public async Task<IList<SeguimentoInfo>> listar()
        {
            string url = GlobalUtils.URLAplicacao + "/api/seguimento/listar";
            return await queryGet<IList<SeguimentoInfo>>(url);
        }

        public async Task<IList<SeguimentoInfo>> listarComLoja()
        {
            string url = GlobalUtils.URLAplicacao + "/api/seguimento/listar-com-loja";
            return await queryGet<IList<SeguimentoInfo>>(url);
        }

        public async Task<IList<SeguimentoInfo>> buscar(double latitude, double longitude, int raio)
        {
            var url = GlobalUtils.URLAplicacao + "/api/seguimento/buscar";
            var args = new List<object>() { new SeguimentoBuscaInfo {
                Latitude = latitude,
                Longitude = longitude,
                Raio = raio
            } };
            return await queryPut<IList<SeguimentoInfo>>(url, args.ToArray());
        }

        /*
        public async Task<SeguimentoInfo> pegar(int idSeguimento)
        {
            var url = string.Format("{0}/api/seguimento/pegar/{1}", GlobalUtils.URLAplicacao, idSeguimento);
            return await queryGet<SeguimentoInfo>(url);
        }

        public async Task<SeguimentoInfo> pegarPorSlug(string slug)
        {
            var url = string.Format("{0}/api/seguimento/pegar-por-slug/{1}", GlobalUtils.URLAplicacao, slug);
            return await queryGet<SeguimentoInfo>(url);
        }

        public async Task<int> inserir(SeguimentoInfo seguimento)
        {
            string url = GlobalUtils.URLAplicacao + "/api/seguimento/inserir";
            var args = new List<object>() { seguimento };
            return await queryPut<int>(url, args.ToArray());
        }

        public async Task<int> alterar(SeguimentoInfo seguimento)
        {
            string url = GlobalUtils.URLAplicacao + "/api/seguimento/alterar";
            var args = new List<object>() { seguimento };
            return await queryPut<int>(url, args.ToArray());
        }

        public async Task excluir(int idSeguimento)
        {
            var url = string.Format("{0}/api/seguimento/excluir/{1}", GlobalUtils.URLAplicacao, idSeguimento);
            await queryGet<SeguimentoInfo>(url);
        }
        */
    }
}
