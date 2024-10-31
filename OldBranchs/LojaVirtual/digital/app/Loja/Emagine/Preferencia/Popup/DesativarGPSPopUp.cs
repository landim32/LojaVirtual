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
    public class DesativarGPSPopUp : BaseListaPopup
    {
        Switch _FecharSwitch;
        Switch _ExibirSwitch;

        protected override void inicializarComponente()
        {
            base.inicializarComponente();
            _FecharSwitch = new Switch
            {
                Style = EstiloUtils.Popup.CheckBox
            };
            _FecharSwitch.Toggled += (sender, e) =>
            {
                if (_FecharSwitch.IsToggled == true)
                {
                    _ExibirSwitch.IsToggled = false;
                    PreferenciaUtils.AoDesativarGPS = AoDesativarGPSEnum.FecharOPrograma;
                }
                else {
                    PreferenciaUtils.AoDesativarGPS = AoDesativarGPSEnum.FazerNada;
                }
            };

            _ExibirSwitch = new Switch
            {
                Style = EstiloUtils.Popup.CheckBox
            };
            _ExibirSwitch.Toggled += (sender, e) =>
            {
                if (_ExibirSwitch.IsToggled == true)
                {
                    _FecharSwitch.IsToggled = false;
                    PreferenciaUtils.AoDesativarGPS = AoDesativarGPSEnum.ExibirNotificacao;
                }
                else {
                    PreferenciaUtils.AoDesativarGPS = AoDesativarGPSEnum.FazerNada;
                }
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
                                Text = "Fechar o Radar"
                            },
                            _FecharSwitch
                        }
                    },
                    criarLinha(),
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        Children = {
                            new Label {
                                Style = EstiloUtils.Popup.Texto,
                                Text = "Exibir Notificação"
                            },
                            _ExibirSwitch
                        }
                    }
                }
            };
        }

        protected override string getTitulo()
        {
            return "Ao Desativar o GPS";
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            if (PreferenciaUtils.AoDesativarGPS == AoDesativarGPSEnum.FecharOPrograma)
                _FecharSwitch.IsToggled = true;
            else if (PreferenciaUtils.AoDesativarGPS == AoDesativarGPSEnum.ExibirNotificacao)
                _ExibirSwitch.IsToggled = true;
        }
    }
}
