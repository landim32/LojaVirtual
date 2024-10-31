using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pagamento.Model
{
    public class PagamentoRetornoInfo
    {
        [JsonProperty("id_pagamento")]
        public int IdPagamento { get; set; }

        [JsonProperty("mensagem")]
        public string Mensagem { get; set; }

        [JsonProperty("boleto_url")]
        public string BoletoUrl { get; set; }

        [JsonProperty("autenticacao_url")]
        public string AutenticacaoUrl { get; set; }

        [JsonIgnore]
        public SituacaoPagamentoEnum Situacao { get; set; }

        [JsonProperty("cod_situacao")]
        public int _CodSituacao
        {
            get
            {
                return (int)Situacao;
            }
            set
            {
                Situacao = (SituacaoPagamentoEnum)value;
            }
        }

    }
}
