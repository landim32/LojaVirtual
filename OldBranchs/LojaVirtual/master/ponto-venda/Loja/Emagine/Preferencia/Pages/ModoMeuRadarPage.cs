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
    public class ModoMeuRadarPage: BasePreferenciaPage
    {
        Switch _ExibirBotaoAdicionarSwitch;
        Switch _ExibirBotaoRemoverSwitch;

        protected override string Titulo
        {
            get
            {
                return "Radares";
            }
        }

        protected override void inicializarComponente()
        {
            _ExibirBotaoAdicionarSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.ExibirBotaoAdicionar
            };
            _ExibirBotaoAdicionarSwitch.Toggled += (sender, e) => {
                PreferenciaUtils.ExibirBotaoAdicionar = e.Value;
            };

            _ExibirBotaoRemoverSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.ExibirBotaoRemover
            };
            _ExibirBotaoRemoverSwitch.Toggled += (sender, e) => {
                PreferenciaUtils.ExibirBotaoRemover = e.Value;
            };
        }

        protected override void inicializarTela()
        {
            adicionarSwitch(_ExibirBotaoAdicionarSwitch, "Exibir botão para adicionar", "Se habilitado um botão de Adicionar(+) será exibido na tela de Mapa e Velocimetro");
            adicionarSwitch(_ExibirBotaoRemoverSwitch, "Exibir botão para remover", "Se habilitado um botão de Remover(-) será exibido quando aparecer um alerta de radar");
        }
    }
}
