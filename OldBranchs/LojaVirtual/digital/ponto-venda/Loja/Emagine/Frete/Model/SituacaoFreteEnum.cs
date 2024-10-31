using System;
namespace Emagine.Frete.Model
{
    [Obsolete("Use FreteSituacaoEnum")]
    public enum SituacaoFreteEnum
    {
        Agendado,
        Aguardando,
        PegandoEncomendaNaoConfirmada,
        PegandoEncomendaConfirmada,
        Entregando,
        EntregaNaoConfirmado,
        EntregaConfirmada,
        Cancelada
    }
}
