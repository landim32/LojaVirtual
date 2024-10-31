using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Mapa.Model;
using Emagine.Produto.IBLL;
using Emagine.Produto.Model;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.BLL.Base
{
    public class LojaBLL : RestAPIBase, ILojaBLL
    {
        protected LojaInfo _loja = null;
        protected IList<LojaInfo> _lojas = null;

        public virtual int RaioBusca { get; set; }

        public virtual bool podeMudarLoja()
        {
            return true;
        }

        public virtual async Task<IList<LojaInfo>> listar(int? idUsuario = null) {
            var url = GlobalUtils.URLAplicacao + "/api/loja/listar";
            if (idUsuario.HasValue) {
                url += "/" + idUsuario.Value.ToString();
            }
            return await queryGet<IList<LojaInfo>>(url);
        }

        public virtual async Task<IList<LojaInfo>> buscar(double latitude, double longitude, int raio, int idSeguimento = 0)
        {
            var url = GlobalUtils.URLAplicacao + "/api/loja/buscar";
            var args = new List<object>() {
                new LojaBuscaInfo
                {
                    Latitude = latitude,
                    Longitude = longitude,
                    Raio = raio,
                    IdSeguimento = idSeguimento
                }
            };
            return await queryPut<IList<LojaInfo>>(url, args.ToArray());
        }

        public virtual async Task<LojaInfo> pegar(int idLoja)
        {
            var url = GlobalUtils.URLAplicacao + "/api/loja/pegar/" + idLoja.ToString();
            return await queryGet<LojaInfo>(url);
        }

        public virtual Task gravarAtual(LojaInfo loja)
        {
            return new TaskFactory().StartNew(() => { _loja = loja; });
            //App.Current.Properties["loja"] = JsonConvert.SerializeObject(loja);
            //App.Current.SavePropertiesAsync();
        }

        public virtual Task limparAtual()
        {
            _loja = null;
            _lojas = null;
            return new Task(()=> { });
        }

        public virtual LojaInfo pegarAtual()
        {
            return _loja;
        }
    }
}