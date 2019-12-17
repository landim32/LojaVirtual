using System;
using Newtonsoft.Json;

namespace Emagine.Frete.Model
{
    public class FreteLocalInfo
    {
        [JsonProperty("id_local")]
        public int Id { get; set; }

        [JsonProperty("id_frete")]
        public int IdFrete { get; set; }

        [JsonIgnore]
        public FreteLocalTipoEnum Tipo { get; set; }

        [JsonProperty("cod_tipo")]
        public int _CodTipo {
            get {
                return (int) Tipo;
            }
            set {
                Tipo = (FreteLocalTipoEnum) value;
            }
        }

        [JsonProperty("ordem")]
        public int Ordem { get; set; }


        [JsonProperty("latitude")]
        public double? Latitude { get; set; }

        [JsonProperty("longitude")]
        public double? Longitude { get; set; }

        [JsonProperty("cep")]
        public string Cep { get; set; }

        [JsonProperty("uf")]
        public string Uf { get; set; }

        [JsonProperty("cidade")]
        public string Cidade { get; set; }

        [JsonProperty("bairro")]
        public string Bairro { get; set; }

        [JsonProperty("numero")]
        public string Numero { get; set; }

        [JsonProperty("logradouro")]
        public string Logradouro { get; set; }

        [JsonProperty("latitude")]
        public double? Latitude { get; set; }

        [JsonProperty("longitude")]
        public double? Longitude { get; set; }

    }
}
