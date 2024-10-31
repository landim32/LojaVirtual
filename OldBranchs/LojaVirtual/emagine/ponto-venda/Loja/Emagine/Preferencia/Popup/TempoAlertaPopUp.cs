using Radar.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.Popup
{
    public class TempoAlertaPopUp : BaseSliderPopUp
    {
        protected override double Maximo
        {
            get
            {
                return 10;
            }
        }

        protected override double Minimo
        {
            get
            {
                return 0;
            }
        }

        protected override double Valor
        {
            get
            {
                return PreferenciaUtils.TempoAlerta;
            }

            set
            {
                PreferenciaUtils.TempoAlerta = (int)Math.Floor(value);
            }
        }

        protected override string getTitulo()
        {
            return "Tempo para o Alerta";
        }

        protected override string formatarTexto(double valor)
        {
            return valor.ToString() + ((valor > 1) ? " Segundos" : " Segundo");
        }
    }
}
