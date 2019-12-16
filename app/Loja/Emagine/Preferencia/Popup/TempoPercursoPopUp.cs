using Emagine.Model;
using Radar.BLL;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Popup
{
    public class TempoPercursoPopUp : BaseSliderPopUp
    {
        protected override double Minimo
        {
            get
            {
                return 0;
            }
        }

        protected override double Maximo
        {
            get
            {
                return 365;
            }
        }

        protected override double Valor
        {
            get
            {
                return PreferenciaUtils.TempoPercurso;
            }

            set
            {
                PreferenciaUtils.TempoPercurso = (int)Math.Floor(value);
            }
        }

        protected override string getTitulo()
        {
            return "Tempo";
        }

        protected override string formatarTexto(double valor)
        {
            return valor.ToString() + ((valor > 1) ? " Dias" : " Dia");
        }

    }
}
