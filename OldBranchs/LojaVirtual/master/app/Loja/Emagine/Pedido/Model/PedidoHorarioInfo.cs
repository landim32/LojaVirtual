using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Pedido.Model
{
    public class PedidoHorarioInfo
    {
        [JsonProperty("id_horario")]
        public int Id { get; set; }

        [JsonProperty("id_loja")]
        public int IdLoja { get; set; }

        [JsonProperty("inicio")]
        public int Inicio { get; set; }

        [JsonProperty("fim")]
        public int Fim { get; set; }

        [JsonProperty("inicio_str")]
        public string InicioStr { get; set; }

        [JsonProperty("fim_str")]
        public string FimStr { get; set; }

        [JsonProperty("horario")]
        public string Horario { get; set; }
    }
}
