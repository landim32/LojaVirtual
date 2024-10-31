using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Model
{
    public class UnidadeInfo
    {
        [JsonProperty("id_unidade")]
        public int Id { get; set; }

        [JsonProperty("id_loja")]
        public int IdLoja { get; set; }

        [JsonProperty("slug")]
        public string Slug { get; set; }

        [JsonProperty("nome")]
        public string Nome { get; set; }
    }
}
