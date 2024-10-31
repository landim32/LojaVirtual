using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Events
{
    public class ProdutoListaEventArgs: EventArgs
    {
        public ProdutoListaEventArgs(ProdutoFiltroInfo filtro) {
            Filtro = filtro;
        }

        public ProdutoFiltroInfo Filtro { get; set; }
        public IList<ProdutoInfo> Produtos { get; set; }
    }
}
