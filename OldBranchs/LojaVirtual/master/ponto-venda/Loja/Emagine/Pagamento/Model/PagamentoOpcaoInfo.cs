using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pagamento.Model
{
    public class PagamentoOpcaoInfo
    {
        [JsonProperty("id_pagamento")]
        public int IdPagamento { get; set; }

        [JsonProperty("chave")]
        public string Chave { get; set; }

        [JsonProperty("valor")]
        public string Valor { get; set; }
    }
}
