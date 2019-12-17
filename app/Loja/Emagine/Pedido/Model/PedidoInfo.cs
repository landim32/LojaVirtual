using Emagine.Pagamento.Model;
using Emagine.Produto.Model;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pedido.Model
{
    public class PedidoInfo
    {
        public PedidoInfo() {
            Itens = new List<PedidoItemInfo>();
        }

        [JsonIgnore]
        public EntregaEnum Entrega { get; set; }

        [JsonProperty("cod_entrega")]
        public int _CodEntrega
        {
            get
            {
                return (int)Entrega;
            }
            set
            {
                Entrega = (EntregaEnum)value;
            }
        }

        [JsonProperty("entrega_str")]
        public string EntregaStr { get; set; }

        [JsonProperty("id_pedido")]
        public int Id { get; set; }

        [JsonProperty("id_loja")]
        public int IdLoja { get; set; }

        [JsonProperty("loja")]
        public LojaInfo Loja { get; set; }

        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }

        [JsonProperty("id_pagamento")]
        public int? IdPagamento { get; set; }

        [JsonProperty("pagamento")]
        public PagamentoInfo Pagamento { get; set; }

        [JsonProperty("data_inclusao")]
        public string DataInclusao { get; set; }

        [JsonProperty("ultima_alteracao")]
        public string UltimaAlteracao { get; set; }

        [JsonProperty("data_inclusao_str")]
        public string DataInclusaoStr { get; set; }

        [JsonProperty("ultima_alteracao_str")]
        public string UltimaAlteracaoStr { get; set; }

        [JsonIgnore]
        public FormaPagamentoEnum FormaPagamento { get; set; }

        [JsonProperty("cod_pagamento")]
        public int _CodPagamento {
            get {
                return (int)FormaPagamento;
            }
            set {
                FormaPagamento = (FormaPagamentoEnum)value;
            }
        }

        [JsonProperty("pagamento_str")]
        public string PagamentoStr { get; set; }

        [JsonIgnore]
        public SituacaoEnum Situacao { get; set; }

        [JsonProperty("cod_situacao")]
        public int _CodSituacao
        {
            get
            {
                return (int)Situacao;
            }
            set
            {
                Situacao = (SituacaoEnum)value;
            }
        }

        [JsonProperty("situacao_str")]
        public string SituacaoStr { get; set; }

        [JsonProperty("nota")]
        public int? Nota { get; set; }

        [JsonProperty("valor_frete")]
        public double? ValorFrete { get; set; }

        [JsonProperty("valor_frete_str")]
        public string ValorFreteStr { get; set; }

        [JsonProperty("troco_para")]
        public double? TrocoPara { get; set; }

        [JsonProperty("troco_para_str")]
        public string TrocoParaStr { get; set; }

        [JsonProperty("total")]
        public double? Total { get; set; }

        [JsonProperty("total_str")]
        public string TotalStr { get; set; }

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

        [JsonProperty("observacao")]
        public string Observacao { get; set; }

        [JsonProperty("itens")]
        public IList<PedidoItemInfo> Itens { get; set; }
    }
}
