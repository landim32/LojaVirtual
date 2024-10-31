using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Model
{
    public class ProdutoListaEventArgs: EventArgs
    {
        public IList<ProdutoInfo> Produtos { get; set; }
    }
}
