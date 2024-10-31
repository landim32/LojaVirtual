using System;
using System.Collections.Generic;
using Newtonsoft.Json;

namespace Emagine.Frete.Model
{
    public class AceiteRetornoInfo
    {
        [JsonProperty("id_frete")]
        public int IdFrete { get; set; }

        [JsonProperty("id_motorista")]
        public int IdMotorista { get; set; }

        [JsonProperty("aceite")]
        public bool Aceite { get; set; }

        /*
        [JsonProperty("valor")]
        public double? Valor { get; set; }
        */

        [JsonProperty("mensagem")]
        public string Mensagem { get; set; }
    }
}
