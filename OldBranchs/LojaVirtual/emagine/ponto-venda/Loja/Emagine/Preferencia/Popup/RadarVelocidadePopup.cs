using Emagine.Model;
using Radar.BLL;
using Radar.Estilo;
using Radar.Factory;
using Radar.Model;
using Radar.Pages;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Popup
{
    public class RadarVelocidadePopup: BaseListaPopup
    {
        private RadarInfo _radar;
        private RadarListaPage _parent;

        private Label _VelocidadeLabel;
        private Slider _VelocidadeSlider;
        private Label _TipoLabel;
        private Picker _TipoSwitch;

        public RadarVelocidadePopup(RadarInfo radar, RadarListaPage parent) {
            _radar = radar;
            _parent = parent;
            Velocidade = _radar.Velocidade;
            TipoRadar = _radar.Tipo;
        }

        protected override void inicializarComponente()
        {
            SalvarVisivel = true;
            _VelocidadeLabel = new Label
            {
                Style = EstiloUtils.Popup.Texto,
                Text = ""
            };
            _VelocidadeSlider = new Slider
            {
                Minimum = 0,
                Maximum = 110
            };
            _VelocidadeSlider.ValueChanged += (sender, e) => {
                _VelocidadeSlider.Value = Math.Round(e.NewValue);
                Velocidade = Math.Floor(_VelocidadeSlider.Value);
                _VelocidadeLabel.Text = formatarTexto(Velocidade);
            };
            _TipoLabel = new Label {
                Style = EstiloUtils.Popup.Texto,
                Text = "Tipo de Radar"
            };
            _TipoSwitch = new Picker();
            var tiposRadar = Enum.GetValues(typeof(RadarTipoEnum));
            var regraRadar = RadarFactory.create();
            foreach (var tipoRadar in tiposRadar) {
                _TipoSwitch.Items.Add(regraRadar.tipoRadarParaTexto((RadarTipoEnum)tipoRadar));
            }
            base.inicializarComponente();
        }

        public override View inicializarConteudo()
        {
            return new StackLayout
            {
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _VelocidadeLabel,
                    _VelocidadeSlider,
                    _TipoLabel,
                    _TipoSwitch
                }
            };
        }

        protected double Velocidade {
            get {
                return _radar.Velocidade;
            }
            set {
                int velocidade = (int)Math.Floor(value);
                velocidade = velocidade - (velocidade % 10);
                if (_radar.Velocidade != velocidade && velocidade >= 20 && velocidade <= 110)
                {
                    _radar.Velocidade = velocidade;
                }
                _VelocidadeLabel.Text = formatarTexto(_radar.Velocidade);
                _VelocidadeSlider.Value = _radar.Velocidade;
            }
        }

        private RadarTipoEnum TipoRadar {
            get
            {
                return _radar.Tipo;
            }
            set {
                int i = 0;
                var tiposRadar = Enum.GetValues(typeof(RadarTipoEnum));
                foreach (var tipoRadar in tiposRadar)
                {
                    if ((RadarTipoEnum)tipoRadar == value) {
                        _TipoSwitch.SelectedIndex = i;
                        _radar.Tipo = value;
                        break;
                    }
                    i++;
                }
            }
        }

        protected override string getTitulo()
        {
            return "Alterar Radar";
        }

        protected override void salvar() {
            var regraRadar = RadarFactory.create();
            regraRadar.gravar(_radar);
            if (_parent != null)
                _parent.atualizarRadar();
        }

        protected string formatarTexto(double valor)
        {
            int velocidade = (int)Math.Floor(valor);
            velocidade = velocidade - (velocidade % 10);
            return velocidade.ToString() + " Km/h";
        }

        protected override double getHeight()
        {
            return 400;
        }
    }
}
