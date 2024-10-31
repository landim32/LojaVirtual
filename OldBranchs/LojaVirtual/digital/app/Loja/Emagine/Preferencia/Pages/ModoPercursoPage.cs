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
    public class ModoPercursoPage: BasePreferenciaPage
    {
        Switch _SalvarPercursoSwitch;

        protected override string Titulo {
            get {
                return "Percursos";
            }
        }

        protected override void inicializarComponente()
        {
            _SalvarPercursoSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.SalvarPercurso
            };
            _SalvarPercursoSwitch.Toggled += (sender, e) => {
                PreferenciaUtils.SalvarPercurso = e.Value;
            };
        }

        protected override void inicializarTela()
        {
            adicionarSwitch(_SalvarPercursoSwitch, "Salvar Percurso", "Salvar o percurso(dados recebidos pelo GPS) enquanto o Radar estiver em funcionamento");
        }
    }
}
