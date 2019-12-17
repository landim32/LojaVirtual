using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Emagine.CRM.Model
{
    public class AtendimentoInfo
    {
        [JsonProperty("id_atendimento")]
        public int IdAtendimento { get; set; }
        [JsonProperty("id_cliente")]
        public int IdCliente { get; set; }
        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }
        [JsonProperty("titulo")]
        public string Titulo { get; set; }
        [JsonProperty("url")]
        public string Url { get; set; }
        [JsonProperty("cliente")]
        public ClienteInfo Cliente { get; set; }
        [JsonProperty("andamentos")]
        public IList<AndamentoInfo> Andamentos { get; set; }
        [JsonProperty("tags")]
        public IList<TagInfo> Tags { get; set; }
    }
}
