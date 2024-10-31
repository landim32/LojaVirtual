using System;
using Newtonsoft.Json;

namespace Emagine.Base.Model
{
    public class MensagemInfo
    {
        [JsonProperty("mensagem")]
        public string Mensagem { get; set; }

        [JsonProperty("assunto")]
        public string Assunto { get; set; }
    }
}
