using Emagine.Produto.Model;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pedido.Model
{
    public class PedidoItemInfo
    {
        [JsonProperty("id_pedido")]
        public int IdPedido { get; set; }

        [JsonProperty("id_produto")]
        public int IdProduto { get; set; }

        [JsonProperty("quantidade")]
        public int Quantidade { get; set; }

        [JsonProperty("produto")]
        public ProdutoInfo Produto { get; set; }
    }
}
