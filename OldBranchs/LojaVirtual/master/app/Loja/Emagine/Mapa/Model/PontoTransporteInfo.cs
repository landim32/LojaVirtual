using System;
using Xamarin.Forms.Maps;

namespace Emagine.Mapa.Model
{
    public class PontoTransporteInfo
    {
        public string Text { get; set; }
        public string TextLabel { get{
                return getTextItem(Tipo) + (Text != "" ? " - " + Text : ""); 
            } set { Text = value; } }
        public string Icone { get {
                return getIconItem(Tipo);
            }}
        public TipoPontoTransporteEnum Tipo { get; set; }
        public Nullable<Position> Posicao { get; set; }

        private string getIconItem(TipoPontoTransporteEnum tipo)
        {
            switch (tipo)
            {
                case TipoPontoTransporteEnum.Carga:
                    return "fa-map-marker";
                case TipoPontoTransporteEnum.Destino:
                    return "fa-flag-checkered";
                case TipoPontoTransporteEnum.Trecho:
                    return "fa-map-marker";
                default:
                    return "fa-plus";
            }
        }
        private string getTextItem(TipoPontoTransporteEnum tipo)
        {
            switch (tipo)
            {
                case TipoPontoTransporteEnum.Carga:
                    return "Origem";
                case TipoPontoTransporteEnum.Destino:
                    return "Destino";
                case TipoPontoTransporteEnum.Trecho:
                    return "Trecho";
                default:
                    return "Adicionar trecho";
            }
        }
    }
}
