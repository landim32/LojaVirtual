using System;
namespace Emagine.Frete.Model
{
    public class FreteOldInfo
    {
        public int Id { get; set; }
        public double OrigemLatitude { get; set; }
        public double OrigemLongitude { get; set; }
        public string OrigemDescricao { get; set; }
        public double DestinoLatitude { get; set; }
        public double DestinoLongitude { get; set; }
        public string DestinoDescricao { get; set; }
        public double Peso { get; set; }
        public double Largura { get; set; }
        public double Altura { get; set; }
        public double Profundidade { get; set; }
        public double Valor { get; set; }
        public double Distancia { get; set; }
        public SituacaoFreteEnum Situacao { get; set; }

        public string Dimensao {
            get {
                string str = string.Empty;
                str = string.Format("{0:N0}cm", Largura) + " x " +
                            string.Format("{0:N0}cm", Altura) + " x " +
                            string.Format("{0:N0}cm", Profundidade);
                return str;
            }
        }

        public string SituacaoStr {
            get {
                var retorno = string.Empty;
                switch (Situacao) {
                    case SituacaoFreteEnum.Agendado:
                        retorno = "Agendado";
                        break;
                    case SituacaoFreteEnum.Aguardando:
                        retorno = "Aguardando";
                        break;
                    case SituacaoFreteEnum.Cancelada:
                        retorno = "Cancelada";
                        break;
                    case SituacaoFreteEnum.EntregaConfirmada:
                        retorno = "Entrega Confirmada";
                        break;
                    case SituacaoFreteEnum.EntregaNaoConfirmado:
                        retorno = "Entrega não Confirmada";
                        break;
                    case SituacaoFreteEnum.Entregando:
                        retorno = "Entregando";
                        break;
                    case SituacaoFreteEnum.PegandoEncomendaConfirmada:
                        retorno = "Encomenda Pega (Confirmada)";
                        break;
                    case SituacaoFreteEnum.PegandoEncomendaNaoConfirmada:
                        retorno = "Encomenda Pega (Não Confirmada)";
                        break;
                }
                return retorno;
            }
        }

    }
}
