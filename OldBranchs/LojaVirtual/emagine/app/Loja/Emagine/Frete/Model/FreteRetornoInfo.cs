using System;
using System.Collections.Generic;
using Newtonsoft.Json;

namespace Emagine.Frete.Model
{
    public class FreteRetornoInfo
    {
        [JsonProperty("id_frete")]
        public int IdFrete { get; set; }

        [JsonProperty("id_motorista")]
        public int IdMotorista { get; set; }

        [JsonProperty("cod_situacao")]
        public int _CodSituacao
        {
            get
            {
                return (int)Situacao;
            }
            set
            {
                Situacao = (FreteSituacaoEnum)value;
            }
        }

        [JsonIgnore]
        public FreteSituacaoEnum Situacao { get; set; }

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

        [JsonProperty("mensagens")]
        public List<string> Mensagens { get; set; }

    }
}
