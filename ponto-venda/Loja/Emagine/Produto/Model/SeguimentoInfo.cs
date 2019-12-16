using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Produto.Model
{
    public class SeguimentoInfo
    {
        [JsonProperty("id_seguimento")]
        public int Id { get; set; }
        [JsonProperty("apenas_pj")]
        public bool ApenasPJ { get; set; }
        [JsonProperty("slug")]
        public string Slug { get; set; }
        [JsonProperty("icone")]
        public string Icone { get; set; }
        [JsonProperty("icone_url")]
        public string IconeUrl { get; set; }
        [JsonProperty("nome")]
        public string Nome { get; set; }
    }
}
