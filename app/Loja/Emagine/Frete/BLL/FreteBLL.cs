using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Frete.Model;
using Emagine.Login.BLL;
using Newtonsoft.Json;

namespace Emagine.Frete.BLL
{
    public class FreteBLL: RestAPIBase
    {
        private FreteInfo _freteAtual;

        public FreteInfo pegarAtual()
        {
            if (_freteAtual != null) {
                return _freteAtual;
            }
            if (App.Current.Properties.ContainsKey("frete")) {
                string freteStr = App.Current.Properties["frete"].ToString();
                var frete = JsonConvert.DeserializeObject<FreteInfo>(freteStr);
                return frete;
            }
            return null;
        }

        public void gravarAtual(FreteInfo frete)
        {
            _freteAtual = frete;
            App.Current.Properties["frete"] = JsonConvert.SerializeObject(_freteAtual);
            App.Current.SavePropertiesAsync();
        }

        public async Task<List<FreteInfo>> listarDisponivel(int idUsuario)
        {
            return await queryGet<List<FreteInfo>>(GlobalUtils.URLAplicacao + "/api/frete/listar-disponivel/" + idUsuario);
        }

        public Task<FreteRetornoInfo> atualizar(int idFrete)
        {
            return queryGet<FreteRetornoInfo>(GlobalUtils.URLAplicacao + "/api/frete/atualizar/" + idFrete.ToString());
        }
        
        public async Task limparAtual() {
            _freteAtual = null;
            App.Current.Properties.Remove("frete");
            await App.Current.SavePropertiesAsync();
        }

        public Task<FreteInfo> pegar(int id_frete)
        {
            return queryGet<FreteInfo>(GlobalUtils.URLAplicacao + "/api/frete/pegar/" + id_frete.ToString());
        }

        public Task<List<FreteInfo>> listar(int idUsuario = 0, int idMotorista = 0, FreteSituacaoEnum? situacao = null)
        {
            string url = GlobalUtils.URLAplicacao + "/api/frete/listar";
            if (situacao.HasValue) {
                url += "/" + ((idUsuario > 0) ? idUsuario.ToString() : "0");
                url += "/" + ((idMotorista > 0) ? idMotorista.ToString() : "0");
                url += "/" + ((int)situacao).ToString();
            }
            else
            {
                if (idMotorista > 0)
                {
                    url += "/" + ((idUsuario > 0) ? idUsuario.ToString() : "0");
                    url += "/" + ((idMotorista > 0) ? idMotorista.ToString() : "0");
                }
                else if (idUsuario > 0) {
                    url += "/" + ((idUsuario > 0) ? idUsuario.ToString() : "0");
                }
            }
            return queryGet<List<FreteInfo>>(url);
        }

        public Task<List<FreteHistoricoInfo>> listarHistorico(long idFrete)
        {
            return queryGet<List<FreteHistoricoInfo>>(GlobalUtils.URLAplicacao + "/api/frete/historico/" + idFrete.ToString());
        }

        public Task<int> inserir(FreteInfo entrega)
        {
            var args = new List<object>();
            args.Add(entrega);
            return queryPut<int>(GlobalUtils.URLAplicacao + "/api/frete/inserir", args.ToArray());
        }

        public Task<AceiteRetornoInfo> aceitar(AceiteEnvioInfo aceite)
        {
            var args = new List<object>() { aceite };
            return queryPut<AceiteRetornoInfo>(GlobalUtils.URLAplicacao + "/api/frete/aceitar", args.ToArray());
        }

        public Task alterar(FreteInfo entrega)
        {
            var args = new List<object>();
            args.Add(entrega);
            return queryPut<object>(GlobalUtils.URLAplicacao + "/api/frete/alterar", args.ToArray());
        }

        public Task alterarSituacao(int idFrete, FreteSituacaoEnum situacao) {
            string url = GlobalUtils.URLAplicacao + "/api/frete/situacao/" + idFrete.ToString() + "/" + (int)situacao;
            return queryGet(url);
        }

        public Task excluir(int id_frete)
        {
            return execGet(GlobalUtils.URLAplicacao + "/api/frete/excluir/" + id_frete.ToString());
        }
    }
}
