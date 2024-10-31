using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Endereco.Model
{
    public class EnderecoBuscaInfo
    {
        [JsonProperty("palavrachave")]
        public string PalavraChave { get; set; }
        [JsonProperty("id_bairro")]
        public int IdBairro { get; set; }
    }
}
