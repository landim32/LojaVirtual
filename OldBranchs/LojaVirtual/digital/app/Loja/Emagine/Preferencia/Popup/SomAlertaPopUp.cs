using Emagine.Model;
using Emagine.Utils;
using Radar.BLL;
using Radar.Estilo;
using Radar.Model;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Popup
{
    public class SomAlertaPopUp : BaseListaPopup
    {
        private IDictionary<SomAlarmeEnum, string> _SomAlarme = new Dictionary<SomAlarmeEnum, string>() {
            { SomAlarmeEnum.Alarme01, "Alarme 01" },
            { SomAlarmeEnum.Alarme02, "Alarme 02" },
            { SomAlarmeEnum.Alarme03, "Alarme 03" },
            { SomAlarmeEnum.Alarme04, "Alarme 04" },
            { SomAlarmeEnum.Alarme05, "Alarme 05" },
            { SomAlarmeEnum.Alarme06, "Alarme 06" },
            { SomAlarmeEnum.Alarme07, "Alarme 07" },
            { SomAlarmeEnum.Alarme08, "Alarme 08" },
            { SomAlarmeEnum.Alarme09, "Alarme 09" },
            { SomAlarmeEnum.Alarme10, "Alarme 10" },
            { SomAlarmeEnum.Alarme11, "Alarme 11" },
            { SomAlarmeEnum.Alarme12, "Alarme 12" },
            { SomAlarmeEnum.Alarme13, "Alarme 13" }
        };
        private IDictionary<SomAlarmeEnum, AlarmeSwitch> _Controls = new Dictionary<SomAlarmeEnum, AlarmeSwitch>();

        protected override string getTitulo()
        {
            return "Som do Alerta";
        }

        protected override double getHeight()
        {
            return 700;
        }

        protected override void inicializarComponente()
        {
            base.inicializarComponente();

            foreach (var item in _SomAlarme) {
                var alarmeSwitch = new AlarmeSwitch {
                    Style = EstiloUtils.Popup.CheckBox,
                    SomAlarme = item.Key
                };
                alarmeSwitch.Toggled += (sender, e) =>
                {
                    var alSwitch = (AlarmeSwitch)sender;
                    if (alSwitch.IsToggled)
                    {
                        foreach (var s in _Controls)
                        {
                            if (alSwitch.SomAlarme != s.Key)
                                s.Value.IsToggled = false;
                        }
                        PreferenciaUtils.SomAlarme = alSwitch.SomAlarme;
                        var regraAviso = new AvisoSonoroBLL();
                        if (PreferenciaUtils.CanalAudio == AudioCanalEnum.Notificacao)
                        {
                            string arquivoSom = regraAviso.pegarArquivo(alSwitch.SomAlarme);
                            MensagemUtils.notificar(104, "Radar+", "Reproduzindo som de alarme para escolha!", audio: arquivoSom);
                        }
                        else {
                            regraAviso.play(alSwitch.SomAlarme);
                        }
                    }
                    else {
                        bool marcado = false;
                        foreach (var s in _Controls) {
                            if (s.Value.IsToggled) {
                                marcado = true;
                                break;
                            }
                        }
                        if (!marcado) {
                            alSwitch.IsToggled = true;
                        }
                    }
                };
                _Controls.Add(item.Key, alarmeSwitch);
            }
        }

        public override View inicializarConteudo()
        {
            var stackLayout = new StackLayout
            {
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill
            };
            foreach (var s in _Controls)
            {
                stackLayout.Children.Add(new StackLayout {
                    Orientation = StackOrientation.Horizontal,
                    HorizontalOptions = LayoutOptions.Fill,
                    Children = {
                            new Label {
                                Style = EstiloUtils.Popup.Texto,
                                Text = _SomAlarme[s.Key]
                            },
                            s.Value
                        }
                });
                stackLayout.Children.Add(criarLinha());
            }
            return stackLayout;
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            foreach (var s in _Controls)
            {
                s.Value.IsToggled = (s.Value.SomAlarme == PreferenciaUtils.SomAlarme);
            }
        }

        public class AlarmeSwitch: Switch {
            public SomAlarmeEnum SomAlarme { get; set; }
        }
    }
}
