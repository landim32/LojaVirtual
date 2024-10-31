using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pagamento.Model
{
    public class PagamentoItemInfo
    {
        [JsonProperty("descricao")]
        public string Descricao { get; set; }

        [JsonProperty("valor")]
        public double Valor { get; set; }

        [JsonProperty("quantidade")]
        public long Quantidade { get; set; }

    }
}
