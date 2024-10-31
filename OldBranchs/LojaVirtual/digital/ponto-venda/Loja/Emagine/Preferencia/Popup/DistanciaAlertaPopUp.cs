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
    public class DistanciaAlertaPopUp : BaseFormPopup
    {
        Label _DistanciaUrbanoLabel;
        Label _DistanciaEstradaLabel;
        Slider _UrbanoSlider;
        Slider _EstradaSlider;

        protected override double getHeight()
        {
            return 500;
        }

        protected override void inicializarComponente()
        {
            base.inicializarComponente();
            _DistanciaUrbanoLabel = new Label
            {
                Style = EstiloUtils.Popup.Texto,
                Text = ""
            };
            _DistanciaEstradaLabel = new Label
            {
                Style = EstiloUtils.Popup.Texto,
                Text = ""
            };

            _UrbanoSlider = new Slider
            {
                Maximum = 500,
                Minimum = 50
            };
            _UrbanoSlider.ValueChanged += (sender, e) =>
            {
                var newStep = Math.Floor(e.NewValue);
                //((Slider)sender).Value = newStep;
                _DistanciaUrbanoLabel.Text = newStep.ToString() + " M";
            };

            _EstradaSlider = new Slider
            {
                Maximum = 1500,
                Minimum = 300
            };
            _EstradaSlider.ValueChanged += (sender, e) =>
            {
                var newStep = Math.Floor(e.NewValue);
                //((Slider)sender).Value = newStep;
                _DistanciaEstradaLabel.Text = newStep.ToString() + " M";
            };

        }

        public override void gravar()
        {
            PreferenciaUtils.DistanciaAlertaUrbano = (int)Math.Floor(_UrbanoSlider.Value);
            PreferenciaUtils.DistanciaAlertaEstrada = (int)Math.Floor(_EstradaSlider.Value);
            PopupNavigation.PopAsync();
        }

        public override View inicializarConteudo()
        {
            return new StackLayout
            {
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    new Label {
                        Text = "No Perítro Urbano",
                        Style = EstiloUtils.Popup.Campo
                    },
                    new Label {
                        Text = "Velocidade de até 90 Km/H",
                        Style = EstiloUtils.Popup.Descricao
                    },
                    _DistanciaUrbanoLabel,
                    _UrbanoSlider,
                    new Label {
                        Text = "No Estrada",
                        Style = EstiloUtils.Popup.Campo
                    },
                    new Label {
                        Text = "Velocidade acima de 90 Km/H",
                        Style = EstiloUtils.Popup.Descricao
                    },
                    _DistanciaEstradaLabel,
                    _EstradaSlider,
                }
            };
        }

        protected override string getTitulo()
        {
            return "Distância para o Alerta";
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            _UrbanoSlider.Value = PreferenciaUtils.DistanciaAlertaUrbano;
            _DistanciaUrbanoLabel.Text = _UrbanoSlider.Value.ToString() + " M";
            _EstradaSlider.Value = PreferenciaUtils.DistanciaAlertaEstrada;
            _DistanciaEstradaLabel.Text = _EstradaSlider.Value.ToString() + " M";
        }
    }
}
