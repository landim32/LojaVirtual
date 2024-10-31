using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Veiculo.Model
{
    public class VeiculoInfo
    {
        public string Foto { get; set; }
        public string Titulo { get; set; }
        public string NomeLoja { get; set; }
        public string Motor { get; set; }
        public string Assento { get; set; }
        public string Direcao { get; set; }
        public string ArCondicionado { get; set; }
        public string Cambio { get; set; }
        public string Porta { get; set; }
        public string AbsStr { get; set; }
        public string AirbagStr { get; set; }

        public double Valor { get; set; }
        public DateTime DataInicio { get; set; }
        public DateTime DataFim { get; set; }

        public string ValorStr {
            get {
                return "R$ " + Valor.ToString("N2");
            }
        }

        public string DataInicioStr {
            get {
                return DataInicio.ToString("dd/MM/yyyy HH:mm");
            }
        }

        public string DataFimStr
        {
            get
            {
                return DataFim.ToString("dd/MM/yyyy HH:mm");
            }
        }
    }
}
