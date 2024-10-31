using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.Model
{
    public class PercursoResumoInfo
    {
        public string Icone { get; set; }
        public float Latitude { get; set; }
        public float Longitude { get; set; }
        public string Descricao { get; set; }
        public DateTime Data { get; set; }
        public TimeSpan Tempo { get; set; }
        public double Distancia { get; set; }

        public string LatitudeStr {
            get {
                return Latitude.ToString("N4") + "º";
            }
        }

        public string LongitudeStr
        {
            get
            {
                return Longitude.ToString("N4") + "º";
            }
        }

        public string DataStr {
            get {
                return Data.ToString("dd/MM/yyyy hh:mm:ss");
            }
        }

        public string TempoStr {
            get {
                return string.Format("{0:D2}:{1:D2}:{2:D2}", Tempo.Hours, Tempo.Minutes, Tempo.Seconds);
            }
        }

        public string DistanciaStr {
            get {
                return (Distancia / 1000).ToString("N1") + " Km";
            }
        }
    }
}
