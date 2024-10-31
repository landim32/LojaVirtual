using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pagamento.Model
{
    public enum SituacaoPagamentoEnum
    {
        Aberto = 1,
        Pago = 2,
        Verificando = 3,
        AguardandoPagamento = 4,
        Cancelado = 5
    }
}
