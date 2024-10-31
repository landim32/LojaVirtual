using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms.Maps;

namespace Emagine.Mapa.Model
{
    public class MapaRotaInfo
    {
        public MapaRotaInfo() {
            Polyline = new List<Position>();
        }

        public string PolylineStr { get; set; }
        public IList<Position> Polyline { get; set; }
        public int Tempo { get; set; }
        public string TempoStr { get; set; }
        public int Distancia { get; set; }
        public string DistanciaStr { get; set; }
        public LocalInfo PosicaoAtual { get; set; }
    }
}
