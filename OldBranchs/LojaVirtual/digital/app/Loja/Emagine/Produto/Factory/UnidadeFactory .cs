using Emagine.Produto.BLL;
using Loja.Emagine.Produto.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Produto.Factory
{
    public static class UnidadeFactory
    {
        private static UnidadeBLL _Unidade;

        public static UnidadeBLL create()
        {
            if (_Unidade == null)
            {
                _Unidade = new UnidadeBLL();
            }
            return _Unidade;
        }

    }
}
