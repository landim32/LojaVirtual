using System;
namespace Emagine.Frete.Model
{
    public enum FreteSituacaoEnum
    {
        AguardandoPagamento = 1,
        ProcurandoMotorista = 2,
        Aguardando = 3,
        PegandoEncomenda = 4,
        Entregando = 5,
        Entregue = 6,
        EntregaConfirmada = 7,
        Cancelado = 8
    }
}
