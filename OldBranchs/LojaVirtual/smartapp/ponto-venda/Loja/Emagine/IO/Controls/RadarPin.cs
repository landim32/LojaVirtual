using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Radar.Model;
using Xamarin.Forms.Maps;

namespace Radar.Controls
{
    public class RadarPin
    {
        public RadarPin() {
            Desenhado = false;
        }

        public Pin Pin { get; set; }
        public string Id { get; set; }
        public int Velocidade { get; set; }
        public int Sentido { get; set; }
        public string Imagem { get; set; }
        public bool Desenhado { get; set; }
        public  RadarTipoEnum Tipo { get; set; }
    }
}
