using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Text;

namespace Emagine.Produto.Model
{
    public class ProdutoRetornoInfo
    {
        [JsonProperty("pagina")]
        public int Pagina { get; set; }
        [JsonProperty("tamanho_pagina")]
        public int TamanhoPagina { get; set; }
        [JsonProperty("total")]
        public int Total { get; set; }
        [JsonProperty("produtos")]
        public IList<ProdutoInfo> Produtos { get; set; }
    }
}
