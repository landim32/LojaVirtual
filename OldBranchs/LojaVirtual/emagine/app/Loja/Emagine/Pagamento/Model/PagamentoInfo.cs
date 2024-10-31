using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pagamento.Model
{
    public class PagamentoInfo
    {
        public PagamentoInfo() {
            Itens = new List<PagamentoItemInfo>();
            Opcoes = new List<PagamentoOpcaoInfo>();
        }

        [JsonProperty("id_pagamento")]
        public int IdPagamento { get; set; }

        [JsonProperty("id_usuario")]
        public int IdUsuario { get; set; }

        [JsonProperty("data_vencimento")]
        public string _DataVencimento {
            get {
                return DataVencimento.ToString("yyyy-MM-dd HH:mm:ss");
            }
            set {
                DateTime data = DateTime.MinValue;
                if (DateTime.TryParseExact(value, "yyyy-MM-dd HH:mm:ss", new CultureInfo("pt-BR"), DateTimeStyles.None, out data)) {
                    DataVencimento = data;
                }
            }
        }

        [JsonIgnore]
        public DateTime DataVencimento { get; set; }

        [JsonProperty("data_pagamento")]
        public string _DataPagamento {
            get {
                return DataPagamento.ToString("yyyy-MM-dd HH:mm:ss");
            }
            set {
                DateTime data = DateTime.MinValue;
                if (DateTime.TryParseExact(value, "yyyy-MM-dd HH:mm:ss", new CultureInfo("pt-BR"), DateTimeStyles.None, out data)) {
                    DataPagamento = data;
                }
            }
        }

        [JsonIgnore]
        public DateTime DataPagamento { get; set; }

        [JsonProperty("valor_desconto")]
        public float ValorDesconto { get; set; }

        [JsonProperty("valor_juro")]
        public float ValorJuro { get; set; }

        [JsonProperty("valor_multa")]
        public float ValorMulta { get; set; }

        [JsonProperty("troco_para")]
        public double TrocoPara { get; set; }

        [JsonProperty("observacao")]
        public string Observacao { get; set; }

        [JsonProperty("mensagem")]
        public string Mensagem { get; set; }

        [JsonProperty("numero_cartao")]
        public string NumeroCartao { get; set; }

        [JsonProperty("data_expiracao")]
        public string _DataExpiracao {
            get {
                return DataExpiracao.ToString("yyyy-MM-dd HH:mm:ss");
            }
            set {
                DateTime data = DateTime.MinValue;
                if (DateTime.TryParseExact(value, "yyyy-MM-dd HH:mm:ss", new CultureInfo("pt-BR"), DateTimeStyles.None, out data)) {
                    DataExpiracao = data;
                }
            }
        }

        [JsonIgnore]
        public DateTime DataExpiracao { get; set; }

        [JsonProperty("nome_cartao")]
        public string NomeCartao { get; set; }

        [JsonProperty("cvv")]
        public string CVV { get; set; }

        [JsonProperty("token")]
        public string Token { get; set; }

        [JsonProperty("cpf")]
        public string Cpf { get; set; }

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

        [JsonProperty("cep")]
        public string Cep { get; set; }

        [JsonProperty("boleto_url")]
        public string BoletoUrl { get; set; }

        [JsonProperty("autenticacao_url")]
        public string AutenticacaoUrl { get; set; }

        [JsonProperty("itens")]
        public IList<PagamentoItemInfo> Itens { get; set; }

        [JsonProperty("opcoes")]
        public IList<PagamentoOpcaoInfo> Opcoes { get; set; }

        [JsonIgnore]
        public BandeiraCartaoEnum Bandeira { get; set; }

        [JsonIgnore]
        public SituacaoPagamentoEnum Situacao { get; set; }

        [JsonIgnore]
        public TipoPagamentoEnum Tipo { get; set; }

        [JsonIgnore]
        public double ValorTotal {
            get {
                double total = 0;
                foreach (var item in Itens) {
                    total += item.Valor * item.Quantidade;
                }
                total += ValorMulta;
                total += ValorJuro;
                total -= ValorDesconto;
                return total;
            }
        }

        [JsonIgnore]
        public string ValorTotalStr {
            get {
                return ValorTotal.ToString("N2");
            }
        }

        [JsonIgnore]
        public bool TemEndereco {
            get {
                return (
                    !string.IsNullOrEmpty(this.Cep) &&
                    !string.IsNullOrEmpty(this.Logradouro) &&
                    !string.IsNullOrEmpty(this.Numero) &&
                    !string.IsNullOrEmpty(this.Cidade) &&
                    !string.IsNullOrEmpty(this.Uf)
                );
            }
        }

        #region Campos escondidos

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

        [JsonProperty("cod_tipo")]
        public int _CodTipo
        {
            get
            {
                return (int)Tipo;
            }
            set
            {
                Tipo = (TipoPagamentoEnum)value;
            }
        }

        [JsonProperty("cod_bandeira")]
        public int? _CodBandeira
        {
            get
            {
                return (int)Bandeira;
            }
            set
            {
                Bandeira = (BandeiraCartaoEnum)(value ?? 1);
            }
        }

        #endregion
    }
}
