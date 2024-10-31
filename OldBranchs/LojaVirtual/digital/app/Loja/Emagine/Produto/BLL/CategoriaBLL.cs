using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Web;

namespace Loja.Emagine.Produto.BLL
{
    public class CategoriaBLL : RestAPIBase
    {
        //private IList<CategoriaInfo> _categorias = null;

        /*
        public async Task<IList<CategoriaInfo>> listar(int idLoja)
        {
            if (_categorias == null)
            {
                UserDialogs.Instance.ShowLoading("Carregando...");
                try
                {
                    _categorias = await queryGet<IList<CategoriaInfo>>(GlobalUtils.URLAplicacao + "/api/categoria/listar/" + idLoja);
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                }
            }
            return _categorias;
        }
        */
        public async Task<IList<CategoriaInfo>> listar(int idLoja)
        {
            /*
            if (_categorias == null)
            {
                _categorias = await queryGet<IList<CategoriaInfo>>(GlobalUtils.URLAplicacao + "/api/categoria/listar/" + idLoja);
            }
            return _categorias;
            */
            return await queryGet<IList<CategoriaInfo>>(GlobalUtils.URLAplicacao + "/api/categoria/listar/" + idLoja);
        }

        public async Task<CategoriaInfo> pegarPorNome(int idLoja, string nome)
        {
            var url = string.Format("{0}/api/categoria/{1}/pegar-por-nome?p={2}", GlobalUtils.URLAplicacao, idLoja, HttpUtility.UrlEncode(nome));
            return await queryGet<CategoriaInfo>(url);
        }

        public async Task<IList<CategoriaInfo>> listarPai(int idLoja)
        {
            var categorias = await listar(idLoja);
            return (
                from categoria in categorias
                where categoria.IdLoja == idLoja && categoria.IdPai == null
                select categoria
            ).ToList();
        }

        public async Task<IList<CategoriaInfo>> listarPorCategoria(int idLoja, int idCategoria)
        {
            var categorias = await listar(idLoja);
            return (
                from categoria in categorias
                where categoria.IdLoja == idLoja && categoria.IdPai == idCategoria
                select categoria
            ).ToList();
        }

        public async Task<int> inserir(CategoriaInfo categoria)
        {
            string url = GlobalUtils.URLAplicacao + "/api/categoria/inserir";
            var args = new List<object>() { categoria };
            return await queryPut<int>(url, args.ToArray());
        }

        public async Task<int> alterar(CategoriaInfo categoria)
        {
            string url = GlobalUtils.URLAplicacao + "/api/categoria/alterar";
            var args = new List<object>() { categoria };
            return await queryPut<int>(url, args.ToArray());
        }
    }
}
