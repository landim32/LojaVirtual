using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Login.Model
{
    public class UsuarioTrocaSenhaInfo
    {
        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }

        [JsonProperty("senha_antiga")]
        public string SenhaAntiga { get; set; }

        [JsonProperty("senha")]
        public string Senha { get; set; }
    }
}
