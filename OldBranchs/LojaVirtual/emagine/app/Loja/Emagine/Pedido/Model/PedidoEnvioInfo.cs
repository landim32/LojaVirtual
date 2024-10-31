using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Pedido.Model
{
    public class PedidoEnvioInfo
    {
        [JsonProperty("id_pedido")]
        public int IdPedido { get; set; }
        [JsonProperty("latitude")]
        public double Latitude { get; set; }
        [JsonProperty("longitude")]
        public double Longitude { get; set; }
    }
}
