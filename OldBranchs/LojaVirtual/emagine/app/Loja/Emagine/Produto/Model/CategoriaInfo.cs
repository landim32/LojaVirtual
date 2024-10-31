using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Model
{
    public class CategoriaInfo
    {
        [JsonProperty("id_categoria")]
        public int Id { get; set; }

        [JsonProperty("id_pai")]
        public int? IdPai { get; set; }

        [JsonProperty("id_loja")]
        public int IdLoja { get; set; }

        [JsonProperty("nome")]
        public string Nome { get; set; }

        [JsonProperty("nome_completo")]
        public string NomeCompleto { get; set; }

        [JsonProperty("slug")]
        public string Slug { get; set; }

        [JsonProperty("foto")]
        public string Foto { get; set; }

        [JsonProperty("foto_url")]
        public string FotoUrl { get; set; }

        [JsonProperty("quantidade")]
        public int Quantidade { get; set; }
    }
}
