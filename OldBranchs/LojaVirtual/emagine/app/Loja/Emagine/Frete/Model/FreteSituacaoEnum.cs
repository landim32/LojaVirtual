using System;
namespace Emagine.Frete.Model
{
    public enum FreteSituacaoEnum
    {
        AguardandoPagamento = 1,
        ProcurandoMotorista = 2,
        AprovandoMotorista = 9,
        Aguardando = 8,
        /*
        PegandoEncomenda = 4,
        Entregando = 5,
        Entregue = 6,
        EntregaConfirmada = 7,
        Cancelado = 8
        */
        PegandoEncomenda = 3,
        Entregando = 4,
        Entregue = 5,
        EntregaConfirmada = 6,
        Cancelado = 7
    }
}
