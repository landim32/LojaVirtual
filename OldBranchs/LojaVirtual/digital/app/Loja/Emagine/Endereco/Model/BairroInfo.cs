using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Endereco.Model
{
    public class BairroInfo
    {
        [JsonProperty("id_bairro")]
        public int Id { get; set; }
        [JsonProperty("id_cidade")]
        public int IdCidade { get; set; }
        [JsonProperty("nome")]
        public string Nome { get; set; }
    }
}
