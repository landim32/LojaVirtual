using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Frete.Model
{
    public class TipoCarroceriaInfo
    {
        [JsonProperty("id_carroceria")]
        public int Id { get; set; }

        [JsonProperty("nome")]
        public string Nome { get; set; }

        [JsonProperty("foto")]
        public string Foto { get; set; }

        [JsonProperty("foto_url")]
        public string FotoUrl { get; set; }

        public override string ToString()
        {
            return Nome;
        }

    }
}
