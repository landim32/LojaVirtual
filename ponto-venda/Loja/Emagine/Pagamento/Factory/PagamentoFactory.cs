using Emagine.Pagamento.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pagamento.Factory
{
    public static class PagamentoFactory
    {
        private static PagamentoBLL _Pagamento;

        public static PagamentoBLL create()
        {
            if (_Pagamento == null)
            {
                _Pagamento = new PagamentoBLL();
            }
            return _Pagamento;
        }

    }
}
