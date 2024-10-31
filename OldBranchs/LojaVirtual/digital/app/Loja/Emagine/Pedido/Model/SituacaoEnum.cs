using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pedido.Model
{
    public enum SituacaoEnum
    {
        Pendente = 1,
        AguardandoPagamento = 2,
        Entregando = 3,
        Entregue = 4,
        Finalizado = 5,
        Preparando = 6,
        Cancelado = 7
    }
}
