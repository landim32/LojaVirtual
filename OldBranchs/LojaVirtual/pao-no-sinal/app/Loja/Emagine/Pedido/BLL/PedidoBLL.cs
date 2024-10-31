using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Login.Model;
using Emagine.Pedido.Model;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pedido.BLL
{
    public class PedidoBLL: RestAPIBase
    {
        private PedidoInfo _pedido;

        public async Task<IList<PedidoInfo>> listar(int idUsuario, Model.SituacaoEnum? situacao = null) {
            string url = GlobalUtils.URLAplicacao + "/api/pedido/listar/" + idUsuario.ToString();
            if (situacao.HasValue) {
                url += "/" + (int)situacao.Value;
            }
            return await queryGet<IList<PedidoInfo>>(url);
        }

        public async Task<PedidoInfo> pegar(int idPedido)
        {
            string url = GlobalUtils.URLAplicacao + "/api/pedido/pegar/" + idPedido.ToString();
            return await queryGet<PedidoInfo>(url);
        }

        public async Task<int> inserir(PedidoInfo pedido)
        {
            string url = GlobalUtils.URLAplicacao + "/api/pedido/inserir";
            var args = new List<object>() { pedido };
            return await queryPut<int>(url, args.ToArray());
        }

        public async Task alterar(PedidoInfo pedido)
        {
            string url = GlobalUtils.URLAplicacao + "/api/pedido/alterar";
            var args = new List<object>() { pedido };
            await queryPut(url, args.ToArray());
            return;
        }

        public async Task alterarSituacao(int idPedido, Model.SituacaoEnum situacao)
        {
            string url = GlobalUtils.URLAplicacao + "/api/pedido/situacao/" + 
                idPedido.ToString() + "/" + ((int)situacao).ToString();
            await queryGet(url);
            return;
        }

        public async Task excluir(int idPedido)
        {
            string url = GlobalUtils.URLAplicacao + "/api/pedido/excluir/" + idPedido.ToString();
            await execGet(url);
            return;
        }

        public async Task<PedidoRetornoInfo> atualizar(PedidoEnvioInfo pedido)
        {
            string url = GlobalUtils.URLAplicacao + "/api/pedido/atualizar";
            var args = new List<object>() { pedido };
            return await queryPut<PedidoRetornoInfo>(url, args.ToArray());
        }

        public PedidoInfo pegarAtual() {
            if (_pedido != null) {
                return _pedido;
            }
            if (App.Current.Properties.ContainsKey("pedido"))
            {
                string pedidoStr = App.Current.Properties["pedido"].ToString();
                var pedido = JsonConvert.DeserializeObject<PedidoInfo>(pedidoStr);
                return pedido;
            }
            return null;
        }

        public void gravarAtual(PedidoInfo pedido)
        {
            App.Current.Properties["pedido"] = JsonConvert.SerializeObject(pedido);
            App.Current.SavePropertiesAsync();
        }

        public async Task limparAtual()
        {
            App.Current.Properties.Remove("pedido");
            await App.Current.SavePropertiesAsync();
        }
    }
}
