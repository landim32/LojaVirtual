using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Emagine.Base.BLL;
using Emagine.Base.Utils;
using Emagine.Frete.Model;
using Emagine.Mapa.Utils;
using Newtonsoft.Json;
using Plugin.Geolocator;

namespace Emagine.Frete.BLL
{
    public class MotoristaBLL: RestAPIBase
    {
        private static MotoristaInfo _motoristaAtual;

        public async Task<MotoristaInfo> pegar(int id_usuario)
        {
            return await queryGet<MotoristaInfo>(GlobalUtils.URLAplicacao + "/api/motorista/pegar/" + id_usuario.ToString());
        }

        public async Task inserir(MotoristaInfo motorista)
        {
            var args = new List<object>();
            args.Add(motorista);
            await execPut(GlobalUtils.URLAplicacao + "/api/motorista/inserir", args.ToArray());
        }

        public async Task<bool> inserirDisponibilidade(DisponibilidadeInfo disponibilidade)
        {
            try{
                var args = new List<object>();
                args.Add(disponibilidade);
                await execPut(GlobalUtils.URLAplicacao + "/api/disponibilidade/inserir", args.ToArray());
                return true;
            } catch( Exception e)
            {
                throw e;
            }

        }

        public async Task<bool> alterarDisponibilidade(DisponibilidadeInfo disponibilidade)
        {
            try
            {
                var args = new List<object>();
                args.Add(disponibilidade);
                await execPut(GlobalUtils.URLAplicacao + "/api/disponibilidade/alterar", args.ToArray());
                return true;
            }
            catch (Exception e)
            {
                throw e;
            }

        }

        public async Task<bool> excluirDisponibilidade(DisponibilidadeInfo disponibilidade)
        {
            try
            {
                var ret = await queryGet(GlobalUtils.URLAplicacao + "/api/disponibilidade/excluir/" + disponibilidade.Id.ToString());
                return true;
            }
            catch (Exception e)
            {
                throw e;
            }
        }

        public async Task<List<DisponibilidadeInfo>> listarDisponibilidade()
        {
            return await queryGet<List<DisponibilidadeInfo>>(GlobalUtils.URLAplicacao + "/api/disponibilidade/listar/" + pegarAtual().Id.ToString());
        }

        public async Task<int> getAvaliacao(int id_motorista)
        {
            try{
                var ret = await queryGet(GlobalUtils.URLAplicacao + "/api/motorista/nota/" + id_motorista.ToString());    
                return int.Parse(ret);
            }
            catch(Exception e){
                throw e;
            }

        }

        public async Task alterar(MotoristaInfo motorista)
        {
            var args = new List<object>();
            args.Add(motorista);
            await execPut(GlobalUtils.URLAplicacao + "/api/motorista/alterar", args.ToArray());
        }

        public Task excluir(int id_usuario)
        {
            return execGet(GlobalUtils.URLAplicacao + "/api/motorista/excluir/" + id_usuario.ToString());
        }

        [Obsolete("Use atualizar")]
        public async Task<MotoristaRetornoInfo> listarPedidosAsync()
        {
            try{
                if (MapsUtils.IsLocationAvailable() && pegarAtual() != null)
                {
                    var args = new List<object>();
                    var posicao = await CrossGeolocator.Current.GetLastKnownLocationAsync();
                    args.Add(new
                    {
                        id_motorista = pegarAtual().Id,
                        latitude = posicao.Latitude,
                        longitude = posicao.Longitude,
                        cod_disponibilidade = 1
                    });
                    return await queryPut<MotoristaRetornoInfo>(GlobalUtils.URLAplicacao + "/api/motorista/atualizar", args.ToArray());
                }
                return null;
            }
            catch( Exception e){
                throw e;
            }

        }

        public void gravarAtual(MotoristaInfo motorista)
        {
            _motoristaAtual = motorista;
            App.Current.Properties["motorista"] = JsonConvert.SerializeObject(_motoristaAtual);
            App.Current.SavePropertiesAsync();
        }

        public async Task limparAtual()
        {
            _motoristaAtual = null;
            App.Current.Properties.Remove("motorista");
            await App.Current.SavePropertiesAsync();
        }

        public MotoristaInfo pegarAtual()
        {
            if (_motoristaAtual != null) {
                return _motoristaAtual;
            }
            if (App.Current.Properties.ContainsKey("motorista"))
            {
                string usuarioStr = App.Current.Properties["motorista"].ToString();
                _motoristaAtual = JsonConvert.DeserializeObject<MotoristaInfo>(usuarioStr);
                return _motoristaAtual;
            }
            return null;
        }

        public Task<MotoristaRetornoInfo> atualizar(MotoristaEnvioInfo envio) {
            string url = GlobalUtils.URLAplicacao + "/api/motorista/atualizar";
            var args = new List<object>() { envio };
            return queryPut<MotoristaRetornoInfo>(url, args.ToArray());
        }
    }
}
