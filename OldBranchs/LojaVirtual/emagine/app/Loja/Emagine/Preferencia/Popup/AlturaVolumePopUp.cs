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
    public class AlturaVolumePopUp : BasePopup
    {
        Label _ValorLabel;
        Slider _AlturaVolumeSlider;
        Button _OKButton;
        Button _CancelarButton;

        protected override void inicializarComponente()
        {
            _ValorLabel = new Label {
                Text = "",
                Style = EstiloUtils.Popup.Texto
            };
            _AlturaVolumeSlider = new Slider {
                Minimum = 0,
                Maximum = 15
            };
            _AlturaVolumeSlider.ValueChanged += (sender, e) => {
                var newStep = Math.Round(e.NewValue);
                _AlturaVolumeSlider.Value = newStep;
                _ValorLabel.Text = _AlturaVolumeSlider.Value.ToString();
            };
            _OKButton = new Button {
                Style = EstiloUtils.Popup.Botao,
                Text = "Ok",
            };
            _OKButton.Clicked += (sender, e) => {
                PreferenciaUtils.AlturaVolume = (int)Math.Floor(_AlturaVolumeSlider.Value);
                PopupNavigation.PopAsync();
            };

            _CancelarButton = new Button
            {
                Style = EstiloUtils.Popup.Botao,
                Text = "Cancelar",
            };
            _CancelarButton.Clicked += (sender, e) => {
                PopupNavigation.PopAsync();
            };
        }

        protected override View inicializarTela()
        {
            return new StackLayout
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    criarTitulo("Altura do Volume"),
                    criarLinha(),
                    new ScrollView {
                        Content = new StackLayout {
                            Children = {
                                _ValorLabel,
                                _AlturaVolumeSlider
                            }
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.CenterAndExpand,
                        Children = {
                            _CancelarButton,
                            _OKButton
                        }
                    }
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            _AlturaVolumeSlider.Value = PreferenciaUtils.AlturaVolume;
            _ValorLabel.Text = _AlturaVolumeSlider.Value.ToString();
        }

    }
}
