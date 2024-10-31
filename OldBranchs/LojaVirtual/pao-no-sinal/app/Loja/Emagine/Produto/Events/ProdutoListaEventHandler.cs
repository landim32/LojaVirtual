using Emagine.Produto.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Events
{
    public delegate Task ProdutoListaEventHandler(object sender, ProdutoListaEventArgs args);
}
