using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Model
{
    public class LojaInfo
    {
        [JsonProperty("id_loja")]
        public int Id { get; set; }

        [JsonProperty("slug")]
        public string Slug { get; set; }

        [JsonProperty("email")]
        public string Email { get; set; }

        [JsonProperty("foto")]
        public string Foto { get; set; }

        [JsonProperty("foto_url")]
        public string FotoUrl { get; set; }

        [JsonProperty("nome")]
        public string Nome { get; set; }

        [JsonProperty("descricao")]
        public string Descricao { get; set; }

        [JsonProperty("cep")]
        public string Cep { get; set; }

        [JsonProperty("logradouro")]
        public string Logradouro { get; set; }

        [JsonProperty("complemento")]
        public string Complemento { get; set; }

        [JsonProperty("numero")]
        public string Numero { get; set; }

        [JsonProperty("bairro")]
        public string Bairro { get; set; }

        [JsonProperty("cidade")]
        public string Cidade { get; set; }

        [JsonProperty("uf")]
        public string Uf { get; set; }

        [JsonProperty("latitude")]
        public double? Latitude { get; set; }

        [JsonProperty("longitude")]
        public double? Longitude { get; set; }

        [JsonProperty("endereco_completo")]
        public string EnderecoCompleto { get; set; }

        [JsonProperty("distancia")]
        public double? Distancia { get; set; }

        [JsonProperty("distancia_str")]
        public string DistanciaStr { get; set; }

        [JsonProperty("cod_gateway")]
        public string CodGateway { get; set; }

        [JsonProperty("usa_retirar")]
        public bool UsaRetirar { get; set; }

        [JsonProperty("usa_retirada_mapeada")]
        public bool UsaRetiradaMapeada { get; set; }

        [JsonProperty("usa_entregar")]
        public bool UsaEntregar { get; set; }

        [JsonProperty("controle_estoque")]
        public bool ControleEstoque { get; set; }

        [JsonProperty("usa_gateway")]
        public bool UsaGateway { get; set; }

        [JsonProperty("aceita_credito_online")]
        public bool AceitaCreditoOnline { get; set; }

        [JsonProperty("aceita_debito_online")]
        public bool AceitaDebitoOnline { get; set; }

        [JsonProperty("aceita_boleto")]
        public bool AceitaBoleto { get; set; }

        [JsonProperty("aceita_dinheiro")]
        public bool AceitaDinheiro { get; set; }

        [JsonProperty("aceita_cartao_offline")]
        public bool AceitaCartaoOffline { get; set; }

        [JsonProperty("cod_situacao")]
        public int CodSituacao { get; set; }
    }
}
