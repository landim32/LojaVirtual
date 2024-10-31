using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Emagine.CRM.Model
{
    public class ClienteInfo
    {
        [JsonProperty("id_cliente")]
        public int IdCliente { get; set; }
        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }
        [JsonProperty("nome")]
        public string Nome { get; set; }
        [JsonProperty("telefone1")]
        public string Telefone1 { get; set; }
        [JsonProperty("email1")]
        public string Email1 { get; set; }
        [JsonProperty("cod_situacao")]
        public int CodSituacao { get; set; }
    }
}
