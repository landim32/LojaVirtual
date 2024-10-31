using Emagine.Model;
using Radar.BLL;
using Radar.Estilo;
using Radar.Utils;
using Rg.Plugins.Popup.Services;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Popup
{
    public class CanalAudioPopUp : BaseListaPopup
    {
        Switch _MusicaSwitch;
        Switch _AlarmeSwitch;
        Switch _NotificacaoSwitch;

        protected override string getTitulo()
        {
            return "Canal de Áudio";
        }

        protected override double getHeight()
        {
            return 320;
        }

        protected override void inicializarComponente()
        {
            base.inicializarComponente();

            _MusicaSwitch = new Switch {
                Style = EstiloUtils.Popup.CheckBox,
            };
            _MusicaSwitch.Toggled += (sender, e) =>
            {
                if (_MusicaSwitch.IsToggled == true)
                {
                    _AlarmeSwitch.IsToggled = false;
                    _NotificacaoSwitch.IsToggled = false;
                }
                else {
                    if (!_AlarmeSwitch.IsToggled && !_NotificacaoSwitch.IsToggled) {
                        _MusicaSwitch.IsToggled = true;
                    }
                }
                PreferenciaUtils.CanalAudio = AudioCanalEnum.Musica;
            };
            _AlarmeSwitch = new Switch {
                Style = EstiloUtils.Popup.CheckBox
            };
            _AlarmeSwitch.Toggled += (sender, e) =>
            {
                if (_AlarmeSwitch.IsToggled == true)
                {
                    _MusicaSwitch.IsToggled = false;
                    _NotificacaoSwitch.IsToggled = false;
                }
                else {
                    if (!_MusicaSwitch.IsToggled && !_NotificacaoSwitch.IsToggled)
                        _AlarmeSwitch.IsToggled = true;
                }
                PreferenciaUtils.CanalAudio = AudioCanalEnum.Alarme;
            };
            _NotificacaoSwitch = new Switch {
                Style = EstiloUtils.Popup.CheckBox
            };
            _NotificacaoSwitch.Toggled += (sender, e) =>
            {
                if (_NotificacaoSwitch.IsToggled == true)
                {
                    _MusicaSwitch.IsToggled = false;
                    _AlarmeSwitch.IsToggled = false;
                }
                else {
                    if (!_MusicaSwitch.IsToggled && !_AlarmeSwitch.IsToggled)
                        _NotificacaoSwitch.IsToggled = true;
                }
                PreferenciaUtils.CanalAudio = AudioCanalEnum.Notificacao;
            };
        }

        public override View inicializarConteudo()
        {
            return new StackLayout
            {
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        Children = {
                            new Label {
                                Style = EstiloUtils.Popup.Texto,
                                Text = "Música"
                            },
                            _MusicaSwitch
                        }
                    },
                    criarLinha(),
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        Children = {
                            new Label {
                                Style = EstiloUtils.Popup.Texto,
                                Text = "Alarmes"
                            },
                            _AlarmeSwitch
                        }
                    },
                    criarLinha(),
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        Children = {
                            new Label {
                                Style = EstiloUtils.Popup.Texto,
                                Text = "Notificações"
                            },
                            _NotificacaoSwitch
                        }
                    },
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            switch (PreferenciaUtils.CanalAudio)
            {
                case AudioCanalEnum.Musica:
                    _MusicaSwitch.IsToggled = true;
                    break;
                case AudioCanalEnum.Alarme:
                    _AlarmeSwitch.IsToggled = true;
                    break;
                case AudioCanalEnum.Notificacao:
                    _NotificacaoSwitch.IsToggled = true;
                    break;
            }
        }
    }
}
