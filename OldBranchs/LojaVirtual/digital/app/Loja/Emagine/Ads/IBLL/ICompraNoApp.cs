using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.IBLL
{
    public interface ICompraNoApp
    {
        bool comprar(string idProduto);
        bool possuiProduto(string idProduto);
    }
}
