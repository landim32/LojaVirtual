using Radar.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.Popup
{
    public class TempoDuracaoPopUp : BaseSliderPopUp
    {
        protected override double Maximo
        {
            get
            {
                return 6;
            }
        }

        protected override double Minimo
        {
            get
            {
                return 0;
            }
        }

        protected override double Valor {
            get {
                return PreferenciaUtils.TempoDuracaoVibracao;
            }
            set {
                PreferenciaUtils.TempoDuracaoVibracao = (int)Math.Floor(value);
            }
        }

        protected override string getTitulo()
        {
            return "Tempo de Duração";
        }

        protected override string formatarTexto(double valor)
        {
            return valor.ToString() + ((valor > 1) ? " Segundos" : " Segundo");
        }
    }
}
