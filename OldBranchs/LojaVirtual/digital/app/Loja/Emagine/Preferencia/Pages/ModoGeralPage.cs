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
    public class ModoGeralPage : BasePreferenciaPage
    {
        Switch _VerificarIniciarSwitch;
        Label _UltimaVerificacao;
        Label _UltimaAtualizacao;

        protected override string Titulo
        {
            get
            {
                return "Geral";
            }
        }

        protected override void inicializarComponente()
        {
            _VerificarIniciarSwitch = new Switch
            {
                Style = EstiloUtils.Preferencia.Checkbox,
                IsToggled = PreferenciaUtils.VerificarIniciar
            };
            _VerificarIniciarSwitch.Toggled += (sender, e) => {
                PreferenciaUtils.VerificarIniciar = e.Value;
            };

            var ultimaVerificacao = PreferenciaUtils.UltimaVerificacao;
            _UltimaVerificacao = new Label {
                Text = (ultimaVerificacao == DateTime.MinValue) ? "Não Verificado" : ultimaVerificacao.ToString("dd/MM/yyyy HH:mm:ss")
            };
            var ultimaAtualizacao = PreferenciaUtils.UltimaAtualizacao;
            _UltimaAtualizacao = new Label {
                Text = (ultimaAtualizacao == DateTime.MinValue) ? "Não Atualizado" : ultimaAtualizacao.ToString("dd/MM/yyyy HH:mm:ss")
            };
        }

        protected override void inicializarTela()
        {
            adicionarBotao("Ao destativar o GPS", () =>
            {
                NavigationX.create(this).PushPopupAsyncX(new DesativarGPSPopUp(), true);
            }, "Define a ação a ser executada quando o GPS for desativado");
            adicionarSwitch(_VerificarIniciarSwitch, "Verificar ao Iniciar", "Lembrar sobre a atualização da Base de Dados de Radar ao iniciar o aplicativo");
            adicionarBotao("Intervalo de Verificação", () =>
            {
                NavigationX.create(this).PushPopupAsyncX(new IntervaloVerificacaoPopUp(), true);
            });
            adicionarLabel(_UltimaVerificacao, "Última Verificação");
            adicionarLabel(_UltimaAtualizacao, "Última Atualização");
        }
    }
}
