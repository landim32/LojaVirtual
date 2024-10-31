using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Produto.Model
{
    public class ProdutoFiltroInfo
    {
        [JsonProperty("id_loja")]
        public int? IdLoja { get; set; }
        [JsonProperty("id_usuario")]
        public int? IdUsuario { get; set; }
        [JsonProperty("id_categoria")]
        public int? IdCategoria { get; set; }
        [JsonProperty("destaque")]
        public bool? Destaque { get; set; }

        [JsonProperty("cod_situacao")]
        public int? _CodSituacao
        {
            get
            {
                if (Situacao.HasValue)
                {
                    return (int)Situacao.Value;
                }
                return null;
            }
            set
            {
                if (value.HasValue)
                {
                    Situacao = (SituacaoEnum)value.Value;
                }
                Situacao = null;
            }
        }

        [JsonIgnore]
        public SituacaoEnum? Situacao { get; set; }

        [JsonProperty("apenas_estoque")]
        public bool? ApenasEstoque { get; set; }
        [JsonProperty("apenas_promocao")]
        public bool? ApenasPromocao { get; set; }
        [JsonProperty("palavra_chave")]
        public string PalavraChave { get; set; }
        [JsonProperty("exibe_origem")]
        public bool? ExibeOrigem { get; set; }
        [JsonProperty("pagina")]
        public int? Pagina { get; set; }
        [JsonProperty("tamanho_pagina")]
        public int? TamanhoPagina { get; set; }
        [JsonProperty("condicao")]
        public bool? Condicao { get; set; }
    }
}
