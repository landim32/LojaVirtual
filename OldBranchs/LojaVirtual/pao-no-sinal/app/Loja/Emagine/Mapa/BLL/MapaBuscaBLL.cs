using Emagine.Base.Utils;
using Emagine.Mapa.Model;
using Emagine.Mapa.Utils;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Mapa.BLL
{
    public class MapaBuscaBLL
    {
        private string montaParametrosGet(List<KeyValuePair<string, string>> parans)
        {
            var ret = "";
            foreach (var param in parans)
            {
                ret += param.Key + "=" + Uri.EscapeDataString(param.Value) + "&";
            }
            if (ret != "")
            {
                ret = ret.Substring(0, ret.Length - 1);
            }
            return ret;
        }

        public string montaLinkGet(string url, List<KeyValuePair<string, string>> parans)
        {
            return url + "?" + montaParametrosGet(parans);
        }

        public async Task<IList<string>> listarAutoCompletarPorPalavraChave(string palavraChave, LocalInfo posicao) {
            using (var client = new HttpClient())
            {
                HttpResponseMessage response = await client.GetAsync(HttpUtils.montaLinkGet(
                    "https://maps.googleapis.com/maps/api/place/autocomplete/json",
                    new List<KeyValuePair<string, string>>() {
                            new KeyValuePair<string, string>("input", palavraChave),
                            new KeyValuePair<string, string>("location", posicao.Latitude + "," + posicao.Longitude),
                            new KeyValuePair<string, string>("language", "pt_BR"),
                            new KeyValuePair<string, string>("key", MapaUtils.MAPA_API_KEY)
                    }
                ));
                response.EnsureSuccessStatusCode();
                var strResposta = await response.Content.ReadAsStringAsync();
                var retGoogle = JsonConvert.DeserializeObject<GoogleLocationInfo>(strResposta);
                var retorno = new List<string>();
                foreach (var item in retGoogle.predictions)
                {
                    retorno.Add(item.description);
                }
                return retorno;
            }
        }

        public async Task<LocalInfo> pegarPosicaoPorPalavraChave(string palavraChave, LocalInfo posicao) {
            using (var client = new HttpClient())
            {
                HttpResponseMessage response = await client.GetAsync(HttpUtils.montaLinkGet(
                    "https://maps.googleapis.com/maps/api/geocode/json",
                    new List<KeyValuePair<string, string>>() {
                        new KeyValuePair<string, string>("address", palavraChave),
                        new KeyValuePair<string, string>("language", "pt_BR"),
                        new KeyValuePair<string, string>("key", MapaUtils.MAPA_API_KEY)
                    }
                ));
                response.EnsureSuccessStatusCode();
                var strResposta = await response.Content.ReadAsStringAsync();
                var retGeocoder = JsonConvert.DeserializeObject<GoogleGeocodingInfo>(strResposta);

                if (retGeocoder != null && retGeocoder.status == "OK")
                {
                    return new LocalInfo {
                        Latitude = retGeocoder.results.First().geometry.location.lat,
                        Longitude = retGeocoder.results.First().geometry.location.lng
                    };
                }
                return null;
            }
        }
    }
}
