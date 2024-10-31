using Emagine.Pagamento.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pagamento.Factory
{
    public static class CartaoFactory
    {
        private static CartaoBLL _Cartao;

        public static CartaoBLL create()
        {
            if (_Cartao == null)
            {
                _Cartao = new CartaoBLL();
            }
            return _Cartao;
        }

    }
}
