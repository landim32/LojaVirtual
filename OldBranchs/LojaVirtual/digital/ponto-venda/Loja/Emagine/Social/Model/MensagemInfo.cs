using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Social.Model
{
    public class MensagemInfo
    {
        [JsonProperty("id_mensagem")]
        public int Id { get; set; }
        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }
        [JsonProperty("id_autor")]
        public int IdAutor { get; set; }
        [JsonProperty("data_inclusao")]
        public string DataInclusao { get; set; }
        [JsonProperty("lido")]
        public bool Lido { get; set; }
        [JsonProperty("mensagem")]
        public string Mensagem { get; set; }
        [JsonProperty("url")]
        public string Url { get; set; }
    }
}
