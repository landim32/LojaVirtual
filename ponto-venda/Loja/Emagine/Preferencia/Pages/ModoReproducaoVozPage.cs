using Emagine.Utils;
using Radar.BLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Emagine.Extensions;
using Radar.Model;
using Radar.Utils;
using Radar.Estilo;

namespace Radar.Pages
{
    public class ModoReproducaoVozPage: BasePreferenciaPage
    {
        Switch _HabilitarVozSwitch;
        Switch _LigarDesligarSwitch;
        Switch _AlertaSonoroSwitch;

        protected override string Titulo {
            get {
                return "Alerta de Voz";
            }
        }

        protected override void inicializarComponente() {
            _HabilitarVozSwitch = new Switch {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.HabilitarVoz
            }; 
            _HabilitarVozSwitch.Toggled += (sender, e) => {
                PreferenciaUtils.HabilitarVoz = e.Value;
            };

            _LigarDesligarSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.LigarDesligar
            };
            _LigarDesligarSwitch.Toggled += (sender, e) => {
                PreferenciaUtils.LigarDesligar = e.Value;
            };

            _AlertaSonoroSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.AlertaSonoro
            };
            _AlertaSonoroSwitch.Toggled += (sender, e) => {
                PreferenciaUtils.AlertaSonoro = e.Value;
            };
        }

        protected override void inicializarTela()
        {
            adicionarSwitch(_HabilitarVozSwitch, "Habilitar Voz", "Avisa com voz a chegada em algum radar");
            adicionarSwitch(_LigarDesligarSwitch, "Ao Ligar e Desligar", "Reproduz voz ao iniciar ou delisgar o aplicativo");
            adicionarSwitch(_AlertaSonoroSwitch, "Alerta Sonoro", "Além da reprodução de voz, emitir também o alerta sonoro");
            adicionarBotao("Reproduzir Teste", () => {
                var tipoRadares = new List<RadarTipoEnum>() {
                        RadarTipoEnum.Lombada,
                        RadarTipoEnum.Pedagio,
                        RadarTipoEnum.PoliciaRodoviaria,
                        RadarTipoEnum.RadarFixo,
                        RadarTipoEnum.RadarMovel,
                        RadarTipoEnum.SemaforoComRadar
                    };
                var velocidades = new List<int>() { 40, 50, 60, 70, 80 };
                var distancias = new List<int>() { 100, 200, 300, 400, 500, 600, 700 };

                var tipoRadar = tipoRadares.Randomize().FirstOrDefault();
                var velocidade = velocidades.Randomize().FirstOrDefault();
                var distancia = distancias.Randomize().FirstOrDefault();

                var aviso = new AvisoSonoroBLL();
                aviso.play(tipoRadar, velocidade, distancia);
            });
        }
    }
}
