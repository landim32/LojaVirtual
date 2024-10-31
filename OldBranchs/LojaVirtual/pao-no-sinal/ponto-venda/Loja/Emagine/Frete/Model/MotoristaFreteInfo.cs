using System;
using System.Collections.Generic;
using Emagine.Mapa.Model;
using Newtonsoft.Json;

namespace Emagine.Frete.Model
{
    public class MotoristaFreteInfo
    {
        [JsonProperty("id_frete")]
        public int IdFrete { get; set; }

        [JsonProperty("endereco_origem")]
        public string EnderecoOrigem { get; set; }

        [JsonProperty("endereco_destino")]
        public string EnderecoDestino { get; set; }

        [JsonProperty("local_origem")]
        public LocalInfo LocalOrigem { get; set; }

        [JsonProperty("local_destino")]
        public LocalInfo LocalDestino { get; set; }

        [JsonProperty("duracao")]
        public long Duracao { get; set; }

        [JsonProperty("duracao_str")]
        public string DuracaoStr { get; set; }

        [JsonProperty("duracao_encomenda")]
        public long DuracaoEncomenda { get; set; }

        [JsonProperty("duracao_encomenda_str")]
        public string DuracaoEncomendaStr { get; set; }

        [JsonProperty("distancia")]
        public long Distancia { get; set; }

        [JsonProperty("distancia_str")]
        public string DistanciaStr { get; set; }

        [JsonProperty("distancia_encomenda")]
        public long DistanciaEncomenda { get; set; }

        [JsonProperty("distancia_encomenda_str")]
        public string DistanciaEncomendaStr { get; set; }

        [JsonProperty("valor")]
        public float Valor { get; set; }
    
    }
}
