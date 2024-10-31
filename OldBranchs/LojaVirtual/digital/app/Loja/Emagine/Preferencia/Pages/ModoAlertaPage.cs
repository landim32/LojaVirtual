using Emagine.Utils;
using Radar.BLL;
using Radar.Estilo;
using Radar.Popup;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Pages
{
    public class ModoAlertaPage: BasePreferenciaPage
    {
        Switch _RadarMovelSwitch;
        Switch _PedagioSwitch;
        Switch _PoliciaRodoviariaSwitch;
        Switch _LombadaSwitch;
        Switch _AlertaInteligenteSwitch;
        Switch _BeepAvisoSwitch;
        Switch _VibrarAlertaSwitch;
        Switch _SobreposicaoVisualSwitch;

        protected override string Titulo
        {
            get
            {
                return "Alerta";
            }
        }

        protected override void inicializarComponente()
        {
            _RadarMovelSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.RadarMovel
            };
            _RadarMovelSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.RadarMovel = e.Value;
            };

            _PedagioSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.Pedagio
            };
            _PedagioSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.Pedagio = e.Value;
            };

            _PoliciaRodoviariaSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.PoliciaRodoviaria
            };
            _PoliciaRodoviariaSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.PoliciaRodoviaria = e.Value;
            };

            _LombadaSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.Lombada
            };
            _LombadaSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.Lombada = e.Value;
            };

            _AlertaInteligenteSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.AlertaInteligente
            };
            _AlertaInteligenteSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.AlertaInteligente = e.Value;
            };

            _BeepAvisoSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.BeepAviso
            };
            _BeepAvisoSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.BeepAviso = e.Value;
            };

            _VibrarAlertaSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.VibrarAlerta
            };
            _VibrarAlertaSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.VibrarAlerta = e.Value;
            };

            _SobreposicaoVisualSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.SobreposicaoVisual
            };
            _SobreposicaoVisualSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.SobreposicaoVisual = e.Value;
            };
        }

        protected override void inicializarTela()
        {
            adicionarSwitch(_RadarMovelSwitch, "Radar Móvel");
            adicionarSwitch(_PedagioSwitch, "Pedágio");
            adicionarSwitch(_PoliciaRodoviariaSwitch, "Polícia Rodoviária");
            adicionarSwitch(_LombadaSwitch, "Lombada");
            adicionarSwitch(_AlertaInteligenteSwitch, "Alerta Inteligente", "Só emitir alerta caso a velocidade atual esteja próxima da velocidade limite");
            adicionarSwitch(_BeepAvisoSwitch, "Beep de Aviso", "Emitir som ao passar por um radar");
            adicionarSwitch(_VibrarAlertaSwitch, "Vibrar ao emitir Alerta");

            adicionarBotao("Tempo de Duração", () => {
                NavigationX.create(this).PushPopupAsyncX(new TempoDuracaoPopUp(), true);
            }, "Defina por quanto tempo o dispositivo deverá vibrar");

            adicionarBotao("Tempo para o Alerta", () => {
                NavigationX.create(this).PushPopupAsyncX(new TempoAlertaPopUp(), true);
            }, "Defina com quanto tempo de antencedência o alerta deve ser emitido");

            adicionarBotao("Distância para o Alerta", () => {
                NavigationX.create(this).PushPopupAsyncX(new DistanciaAlertaPopUp(), true);
            }, "Defina com que distância o alerta deve ser emitido");

            if (Device.OS == TargetPlatform.Android)
            {
                adicionarSwitch(_SobreposicaoVisualSwitch, "Sobreposição Visual", "Exibir alertas visuais quando o App estiver funcionando em segundo plano");
            }
        }
    }
}
