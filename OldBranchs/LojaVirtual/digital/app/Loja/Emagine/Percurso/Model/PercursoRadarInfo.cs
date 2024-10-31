using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.Model
{
    public class PercursoRadarInfo: PercursoResumoInfo
    {
        public int Velocidade { get; set; }
        public double MinhaVelocidade { get; set; }
        public RadarTipoEnum Tipo { get; set; }

        public string VelocidadeStr {
            get {
                return Velocidade.ToString("N0") + " Km/h";
            }
        }

        public string MinhaVelocidadeStr
        {
            get
            {
                return MinhaVelocidade.ToString("N0") + " Km/h";
            }
        }
    }
}
