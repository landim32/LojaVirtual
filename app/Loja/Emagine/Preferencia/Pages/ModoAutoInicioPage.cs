using Emagine.Utils;
using Radar.BLL;
using Radar.Estilo;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Pages
{
    public class ModoAutoInicioPage : BasePreferenciaPage
    {
        Switch _HabilitarSwitch;

        protected override string Titulo
        {
            get
            {
                return "Início automático";
            }
        }

        protected override void inicializarComponente()
        {
            _HabilitarSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.InicioDesligamento
            };
            _HabilitarSwitch.Toggled += (sender, e) => {
                PreferenciaUtils.InicioDesligamento = e.Value;
            };
        }

        protected override void inicializarTela()
        {
            adicionarSwitch(_HabilitarSwitch, "Habilitar", "Inicia ou desliga automaticamente quanto qualquer outro App que consome GPS for iniciado ou desligado");
        }
    }
}
