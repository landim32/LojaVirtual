using System;
using System.Collections.Generic;
using Newtonsoft.Json;

namespace Emagine.Frete.Model
{
    public class MotoristaRetornoInfo
    {
        [JsonProperty("id_motorista")]
        public int? IdMotorista { get; set; }

        [JsonProperty("id_frete")]
        public int? IdFrete { get; set; }

        [JsonProperty("cod_situacao")]
        private int? _CodSituacao { get; set; }

        public FreteSituacaoEnum? CodSituacao { 
            get 
            {
                if (_CodSituacao != null)
                    return (FreteSituacaoEnum)_CodSituacao;
                else
                    return null;
            } 
            set 
            {
                _CodSituacao = (value.HasValue == true ? (int)value.Value : 1);
            } 
        }

        [JsonProperty("distancia")]
        public int? Distancia { get; set; }

        [JsonProperty("distancia_str")]
        public string DistanciaStr { get; set; }

        [JsonProperty("tempo")]
        public int? Tempo { get; set; }

        [JsonProperty("tempo_str")]
        public string TempoStr { get; set; }

        [JsonProperty("polyline")]
        public string Polyline { get; set; }

        [JsonProperty("fretes")]
        public IList<MotoristaFreteInfo> Fretes { get; set; }

        [JsonProperty("mensagens")]
        public IList<string> Mensagens { get; set; }

    }
}
