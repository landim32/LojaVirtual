using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.Model
{
    public class RadarBuscaInfo
    {
        private List<RadarTipoEnum> _filtro;

        public RadarBuscaInfo() {
            _filtro = new List<RadarTipoEnum>();
        }

        public double latitudeCos { get; set; }
        public double longitudeCos { get; set; }
        public double latitudeSin { get; set; }
        public double longitudeSin { get; set; }
        public double distanciaCos { get; set; }
        public IList<RadarTipoEnum> Filtros { get; set; }
    }
}
