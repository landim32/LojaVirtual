using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Login.Model
{
    public class UsuarioPreferenciaInfo
    {
        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }

        [JsonProperty("chave")]
        public string Chave { get; set; }

        [JsonProperty("valor")]
        public string Valor { get; set; }
    }
}
