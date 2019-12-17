using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Emagine.CRM.Model
{
    public class AndamentoInfo
    {
        [JsonProperty("id_andamento")]
        public int IdAndamento { get; set; }
        [JsonProperty("id_atendimento")]
        public int IdAtendimento { get; set; }
        [JsonProperty("id_cliente")]
        public int? IdCliente { get; set; }
        [JsonProperty("id_usuario")]
        public int? IdUsuario { get; set; }
        [JsonProperty("cod_situacao")]
        public int CodSituacao { get; set; }
        [JsonProperty("valor_proposta")]
        public double? ValorProposta { get; set; }
        [JsonProperty("mensagem")]
        public string Mensagem { get; set; }

        [JsonProperty("data_inclusao")]
        public string _DataInclusao
        {
            get
            {
                return DataInclusao.ToString("yyyy-MM-dd HH:mm:ss");
            }
            set
            {
                DataInclusao = DateTime.Parse(value);
            }
        }

        [JsonIgnore]
        public DateTime DataInclusao { get; set; }
    }
}
