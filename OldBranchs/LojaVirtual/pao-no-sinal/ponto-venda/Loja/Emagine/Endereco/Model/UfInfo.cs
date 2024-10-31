using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Endereco.Model
{
    public class UfInfo
    {
        [JsonProperty("uf")]
        public string Uf { get; set; }
        [JsonProperty("nome")]
        public string Nome { get; set; }
    }
}
