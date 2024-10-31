using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Reflection;

namespace Radar.Model
{
    public class PercursoGastoInfo: PercursoResumoInfo
    {
        public double Valor { get; set; }
        public string FotoBase64 { get; set; }
    }
}
