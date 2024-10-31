using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Login.Model
{
    public class UsuarioNovaSenhaInfo
    {
        [JsonProperty("email")]
        public string Email { get; set; }

        [JsonProperty("senha_antigo")]
        public string SenhaAntiga { get; set; }

        [JsonProperty("senha")]
        public string Senha { get; set; }
    }
}
