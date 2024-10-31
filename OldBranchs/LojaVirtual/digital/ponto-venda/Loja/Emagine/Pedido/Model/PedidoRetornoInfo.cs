using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Pedido.Model
{
    public class PedidoRetornoInfo
    {
        [JsonProperty("id_pedido")]
        public int IdPedido { get; set; }

        [JsonProperty("cod_situacao")]
        public int _CodSituacao {
            get {
                return (int)Situacao;
            }
            set {
                Situacao = (SituacaoEnum)value;
            }
        }

        [JsonIgnore]
        public SituacaoEnum Situacao { get; set; }

        [JsonProperty("latitude")]
        public double Latitude { get; set; }

        [JsonProperty("longitude")]
        public double Longitude { get; set; }

        [JsonProperty("distancia")]
        public int Distancia { get; set; }

        [JsonProperty("distancia_str")]
        public string DistanciaStr { get; set; }

        [JsonProperty("tempo")]
        public int Tempo { get; set; }

        [JsonProperty("tempo_str")]
        public string TempoStr { get; set; }

        [JsonProperty("polyline")]
        public string Polyline { get; set; }

        [JsonProperty("mensagem")]
        public string Mensagem { get; set; }
    }
}
