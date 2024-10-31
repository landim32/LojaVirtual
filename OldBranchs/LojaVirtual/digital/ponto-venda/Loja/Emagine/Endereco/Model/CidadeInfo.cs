using System;
using Newtonsoft.Json;

namespace Emagine.Endereco.Model
{
    public class CidadeInfo
    {
        [JsonProperty("id_cidade")]
        public int Id { get; set; }

        [JsonProperty("uf")]
        public string UF { get; set; }

        [JsonProperty("nome")]
        public string Nome { get; set; }

        [JsonProperty("latitude")]
        public float? Latitude { get; set; }

        [JsonProperty("longitude")]
        public float? Longitude { get; set; }

    }
}
