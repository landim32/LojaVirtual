using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Frete.Model
{
    public class AceiteEnvioInfo
    {
        [JsonProperty("id_frete")]
        public int IdFrete { get; set; }

        [JsonProperty("id_motorista")]
        public int IdMotorista { get; set; }

        [JsonProperty("aceite")]
        public bool Aceite { get; set; }

        [JsonProperty("valor")]
        public double Valor { get; set; }
    }
}
