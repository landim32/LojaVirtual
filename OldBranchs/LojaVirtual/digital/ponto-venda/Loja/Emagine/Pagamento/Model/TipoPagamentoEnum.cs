using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pagamento.Model
{
    public enum TipoPagamentoEnum
    {
        CreditoOnline = 1,
        DebitoOnline = 2,
        Boleto = 3,
        Dinheiro = 4,
        CartaoOffline = 5
    }
}
