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
    public class NivelZoomPopUp : BaseSliderPopUp
    {
        protected override double Maximo
        {
            get {
                return 30;
            }
        }

        protected override double Minimo {
            get {
                return 0;
            }
        }

        protected override double Valor {
            get {
                return PreferenciaUtils.NivelZoom;
            }

            set {
                PreferenciaUtils.NivelZoom = (int)Math.Floor(value);
            }
        }

        protected override string getTitulo()
        {
            return "Nivel de Zoom";
        }
    }
}
