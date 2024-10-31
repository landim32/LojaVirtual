
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Banner.Model
{
    public class BannerFiltroInfo
    {
        private const string POR_ORDEM = "ordem";
        private const string ALEATORIO = "aleatorio";

        [JsonProperty("id_banner")]
        public int? IdBanner { get; set; }

        [JsonProperty("slug_banner")]
        public string SlugBanner { get; set; }

        [JsonProperty("id_loja")]
        public int? IdLoja { get; set; }

        [JsonProperty("id_seguimento")]
        public int? IdSeguimento { get; set; }

        [JsonProperty("indice")]
        public int? Indice { get; set; }

        [JsonProperty("quantidade")]
        public int? Quantidade { get; set; }

        [JsonProperty("latitude")]
        public double? Latitude { get; set; }

        [JsonProperty("longitude")]
        public double? Longitude { get; set; }

        [JsonProperty("raio")]
        public int? Raio { get; set; }

        [JsonProperty("ordem")]
        public string _Ordem { get; set; }

        [JsonIgnore]
        public BannerOrdemEnum? Ordem {
            get {
                BannerOrdemEnum retorno = BannerOrdemEnum.PorOrdem;
                switch (_Ordem) {
                    case ALEATORIO:
                        retorno = BannerOrdemEnum.Aleatorio;
                        break;
                    default:
                        retorno = BannerOrdemEnum.PorOrdem;
                        break;
                }
                return retorno;
            }
            set {
                switch (value)
                {
                    case BannerOrdemEnum.Aleatorio:
                        _Ordem = POR_ORDEM;
                        break;
                    default:
                        _Ordem = ALEATORIO;
                        break;
                }
            }
        }
    }
}
