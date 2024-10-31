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
    public class ModoMapaPage : BasePreferenciaPage
    {
        Switch _BussolaSwitch;
        Switch _SinalGPSSwitch;
        Switch _ImagemSateliteSwitch;
        Switch _InfoTrafegoSwitch;
        Switch _RotacionarMapaSwitch;
        Switch _SuavizarAnimacaoSwitch;

        protected override string Titulo
        {
            get
            {
                return "Mapa";
            }
        }

        protected override void inicializarComponente()
        {
            _BussolaSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.Bussola
            };
            _BussolaSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.HabilitarVoz = e.Value;
            };

            _SinalGPSSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.SinalGPS
            };
            _SinalGPSSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.SinalGPS = e.Value;
            };

            _ImagemSateliteSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.ImagemSatelite
            };
            _ImagemSateliteSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.ImagemSatelite = e.Value;
            };

            _InfoTrafegoSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.InfoTrafego
            };
            _InfoTrafegoSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.InfoTrafego = e.Value;
            };

            _RotacionarMapaSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.RotacionarMapa
            };
            _RotacionarMapaSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.RotacionarMapa = e.Value;
            };

            _SuavizarAnimacaoSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.SuavizarAnimacao
            };
            _SuavizarAnimacaoSwitch.Toggled += (sender, e) =>
            {
                PreferenciaUtils.SuavizarAnimacao = e.Value;
            };
        }

        protected override void inicializarTela()
        {
            adicionarSwitch(_BussolaSwitch, "Bussola");
            adicionarSwitch(_SinalGPSSwitch, "Sinal do GPS");
            adicionarSwitch(_ImagemSateliteSwitch, "Imagens do Satélite");
            adicionarSwitch(_InfoTrafegoSwitch, "Informações de Tráfego");
            adicionarSwitch(_RotacionarMapaSwitch, "Rotacionar o Mapa", "Sempre Rotacionar o mapa para mostrar uma visão frontal");
            adicionarBotao("Nível de Zoom", () => {
                NavigationX.create(this).PushPopupAsyncX(new NivelZoomPopUp(), true);
            });
            adicionarSwitch(_SuavizarAnimacaoSwitch, "Suavizar Animação");
        }
    }
}
