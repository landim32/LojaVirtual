using Emagine.Model;
using Radar.BLL;
using Radar.Estilo;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Popup
{
    public abstract class BaseSliderPopUp : BaseListaPopup
    {
        Label _TextoLabel;
        Slider _Slider;
        protected abstract double Minimo { get; }
        protected abstract double Maximo { get; }
        protected abstract double Valor { get; set; }

        protected virtual string formatarTexto(double valor) {
            return valor.ToString();
        }

        protected override double getHeight()
        {
            return 300;
        }

        protected override void inicializarComponente()
        {
            base.inicializarComponente();

            _TextoLabel = new Label {
                Style = EstiloUtils.Popup.Texto,
                Text = ""
            };
            _Slider = new Slider {
				Minimum = Minimo,
                Maximum = Maximo
            };
            _Slider.ValueChanged += (sender, e) => {
                _Slider.Value = Math.Round(e.NewValue);
                Valor = Math.Floor(_Slider.Value);
                _TextoLabel.Text = formatarTexto(Valor);
            };
        }

        public override View inicializarConteudo()
        {
            return new StackLayout
            {
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _TextoLabel,
                    _Slider
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            _Slider.Value = Valor;
            _TextoLabel.Text = formatarTexto(_Slider.Value);
        }
    }
}
