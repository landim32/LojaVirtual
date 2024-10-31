using Emagine.Utils;
using Radar.BLL;
using Radar.Controls;
using Radar.Factory;
using Radar.Model;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using System.Diagnostics;
using Emagine.Controls;

namespace Radar.Pages
{
    public class VelocimetroPage: BaseVisualPage
    {

		private AbsoluteLayout _absoluteLayout;
        private Velocimetro _velocimetro;
        private Image _radarImage;
		private Label caminhoLivre;

		public override float VelocidadeAtual {
            get {
                return _velocimetro.VelocidadeAtual;
            }
            set {
                _velocimetro.VelocidadeAtual = value;
            }
        }

        public override float VelocidadeRadar {
            get {
				return _velocimetro.VelocidadeRadar;
            }
            set {
				if ((int)_velocimetro.VelocidadeRadar != (int)value)
                {
                    _velocimetro.VelocidadeRadar = value;
                    atualizarVelocidadeRadar(value);
                }
            }
        }

        protected override void atualizarVelocidadeRadar(float velocidadeRadar) {
            var regraRadar = RadarFactory.create();
            var pathRadar = regraRadar.imagemRadar(velocidadeRadar);
			if (velocidadeRadar > 0)
			{
				_radarImage.Source = ImageSource.FromFile(pathRadar);
				caminhoLivre.Text = null;
                if (_AdicionarRadarButton != null)
				    _absoluteLayout.Children.Remove(_AdicionarRadarButton);
                if (_RemoverRadarButton != null)
				    _absoluteLayout.Children.Add(_RemoverRadarButton);
			}
			else {
                if (_RemoverRadarButton != null)
                    _absoluteLayout.Children.Remove(_RemoverRadarButton);
                if (_AdicionarRadarButton != null)
                    _absoluteLayout.Children.Add(_AdicionarRadarButton);
				caminhoLivre.Text = "CAMINHO LIVRE";
				_radarImage.Source = null;
			}
					
        }

        public VelocimetroPage()
        {

            Title = "Velocimetro";
            inicializarComponente();
            PercursoBLL percursoBLL = new PercursoBLL();
            percursoBLL.atualizarEndereco();

            _velocimetro = new Velocimetro
            {
                VerticalOptions = LayoutOptions.StartAndExpand,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                WidthRequest = TelaUtils.Largura,
                HeightRequest = TelaUtils.Altura,
                BackgroundColor = Color.Transparent,
                Margin = new Thickness(0, 50, 0, 0)
            };

            _absoluteLayout = new AbsoluteLayout();
            //absoluteLayout.HorizontalOptions = LayoutOptions.Fill;

            Frame placa = new Frame();
            placa.HorizontalOptions = LayoutOptions.FillAndExpand;
            placa.VerticalOptions = LayoutOptions.FillAndExpand;
            StackLayout dentroPlaca = new StackLayout();

            dentroPlaca.HorizontalOptions = LayoutOptions.FillAndExpand;
            dentroPlaca.VerticalOptions = LayoutOptions.Fill;
            dentroPlaca.Orientation = StackOrientation.Vertical;
            dentroPlaca.Spacing = 1;

            RadarBLL radarBLL = RadarFactory.create();

            _radarImage = new Image();

            _radarImage.Aspect = Aspect.Fill;
            _radarImage.WidthRequest = 50;
            _radarImage.HeightRequest = 50;
            _radarImage.VerticalOptions = LayoutOptions.CenterAndExpand;
            _radarImage.HorizontalOptions = LayoutOptions.Center;

            Label fiscalizacao = new Label();
            fiscalizacao.Text = "FISCALIZAÇÃO ELETRÔNICA";
            fiscalizacao.FontSize = 10;
            fiscalizacao.TextColor = Color.Black;
            fiscalizacao.VerticalOptions = LayoutOptions.CenterAndExpand;
            fiscalizacao.HorizontalOptions = LayoutOptions.Center;

            caminhoLivre = new Label();
            caminhoLivre.Text = "CAMINHO LIVRE";
            caminhoLivre.FontSize = 10;
            caminhoLivre.TextColor = Color.Black;
            caminhoLivre.VerticalOptions = LayoutOptions.CenterAndExpand;
            caminhoLivre.HorizontalOptions = LayoutOptions.Center;

            _DistanciaRadarLabel.TextColor = Color.Black;
            _DistanciaRadarLabel.VerticalOptions = LayoutOptions.CenterAndExpand;
            _DistanciaRadarLabel.HorizontalOptions = LayoutOptions.Center;


            if (TelaUtils.Dispositivo == "Pad")
            {
                _velocimetro.Margin = new Thickness(0, -50, 0, 0);
                _radarImage.WidthRequest = 70;
                _radarImage.HeightRequest = 70;
                AbsoluteLayout.SetLayoutBounds(placa, new Rectangle(0.52, 0.85, 0.3, 0.25));
                AbsoluteLayout.SetLayoutFlags(placa, AbsoluteLayoutFlags.All);
            }
            else {
                AbsoluteLayout.SetLayoutBounds(placa, new Rectangle(0.5, 0.85, 0.4, 0.3));
                AbsoluteLayout.SetLayoutFlags(placa, AbsoluteLayoutFlags.All);
            }


            if (TelaUtils.Orientacao == "Landscape")
            {
                _velocimetro.Margin = new Thickness(0, -40, 0, 0);

                double size = Math.Sqrt(Math.Pow(TelaUtils.LarguraDPI, 2) + Math.Pow(TelaUtils.AlturaDPI, 2));

                if (size > 6)
                {
                    AbsoluteLayout.SetLayoutBounds(placa, new Rectangle(0.4, 0.85, 0.25, 0.25));
                    AbsoluteLayout.SetLayoutFlags(placa, AbsoluteLayoutFlags.All);
                }
                else {

                    if (size > 7)
                    {
                        AbsoluteLayout.SetLayoutBounds(placa, new Rectangle(0.35, 0.85, 0.25, 0.25));
                        AbsoluteLayout.SetLayoutFlags(placa, AbsoluteLayoutFlags.All);
                    }
                    else {
                        AbsoluteLayout.SetLayoutBounds(placa, new Rectangle(0.78, 0.5, 0.25, 0.5));
                        AbsoluteLayout.SetLayoutFlags(placa, AbsoluteLayoutFlags.All);

                    }
                }


                //placa.Margin = new Thickness(TelaUtils.LarguraSemPixel / 3, 0, 0, 0);

                _radarImage.WidthRequest = 50;
                _radarImage.HeightRequest = 50;

                AbsoluteLayout.SetLayoutBounds(_BussolaFundo, new Rectangle(1, 0.05, 0.2, 0.2));
                AbsoluteLayout.SetLayoutFlags(_BussolaFundo, AbsoluteLayoutFlags.All);
                AbsoluteLayout.SetLayoutBounds(_BussolaAgulha, new Rectangle(1, 0.05, 0.2, 0.2));
                AbsoluteLayout.SetLayoutFlags(_BussolaAgulha, AbsoluteLayoutFlags.All);
                AbsoluteLayout.SetLayoutBounds(_GPSSentidoLabel, new Rectangle(1, 0.25, 0.2, 0.2));
                AbsoluteLayout.SetLayoutFlags(_GPSSentidoLabel, AbsoluteLayoutFlags.All);

                AbsoluteLayout.SetLayoutBounds(_PrecisaoFundoImage, new Rectangle(1, 0.8, 0.2, 0.2));
                AbsoluteLayout.SetLayoutFlags(_PrecisaoFundoImage, AbsoluteLayoutFlags.All);
                AbsoluteLayout.SetLayoutBounds(_PrecisaoImage, new Rectangle(1, 0.8, 0.2, 0.2));
                AbsoluteLayout.SetLayoutFlags(_PrecisaoImage, AbsoluteLayoutFlags.All);
                AbsoluteLayout.SetLayoutBounds(_PrecisaoLabel, new Rectangle(1, 1, 0.2, 0.2));
                AbsoluteLayout.SetLayoutFlags(_PrecisaoLabel, AbsoluteLayoutFlags.All);

                //AbsoluteLayout.SetLayoutBounds(_VelocidadeRadarLabel, new Rectangle(0.8, 0.85, 0.2, 0.2));
                //AbsoluteLayout.SetLayoutFlags(_VelocidadeRadarLabel, AbsoluteLayoutFlags.All);
                //AbsoluteLayout.SetLayoutBounds(_DistanciaRadarLabel, new Rectangle(0.8, 0.95, 0.2, 0.2));
                //AbsoluteLayout.SetLayoutFlags(_DistanciaRadarLabel, AbsoluteLayoutFlags.All);

                //_AdicionarRadarButton.Margin = new Thickness(TelaUtils.LarguraSemPixel / 2 + 200, 0, 0, 20);
            }

            if (TelaUtils.Orientacao == "LandscapeLeft" || TelaUtils.Orientacao == "LandscapeRight")
            {
                if (TelaUtils.Dispositivo == "Pad")
                {
                    _velocimetro.Margin = new Thickness(10, -310, 0, 0);

                    AbsoluteLayout.SetLayoutBounds(placa, new Rectangle(0.52, 0.85, 0.2, 0.3));
                    AbsoluteLayout.SetLayoutFlags(placa, AbsoluteLayoutFlags.All);
                    _radarImage.VerticalOptions = LayoutOptions.StartAndExpand;
                }
                else {
                    _velocimetro.Margin = new Thickness(TelaUtils.Largura * 0.5 - 70, -270, 0, 0);
                    AbsoluteLayout.SetLayoutBounds(_velocimetro, new Rectangle(1, 1, 1, 0.5));
                    AbsoluteLayout.SetLayoutFlags(_velocimetro, AbsoluteLayoutFlags.All);
                    _radarImage.WidthRequest = 40;
                    _radarImage.HeightRequest = 40;
                    _radarImage.Margin = new Thickness(0, -9, 0, 0);
                    fiscalizacao.Margin = new Thickness(0, -5, 0, 0);
                    fiscalizacao.VerticalOptions = LayoutOptions.FillAndExpand;
                    fiscalizacao.FontSize = 9;
                    dentroPlaca.Margin = new Thickness(0, -5, 0, 0);
                    _DistanciaRadarLabel.VerticalOptions = LayoutOptions.Start;
                    AbsoluteLayout.SetLayoutBounds(placa, new Rectangle(0.5, 0.85, 0.2, 0.3));
                    AbsoluteLayout.SetLayoutFlags(placa, AbsoluteLayoutFlags.All);

                }
            }
            Padding = 5;

            dentroPlaca.Children.Add(_radarImage);

            dentroPlaca.Children.Add(caminhoLivre);



            dentroPlaca.Children.Add(fiscalizacao);
            dentroPlaca.Children.Add(_DistanciaRadarLabel);

            _absoluteLayout.Children.Add(_velocimetro);
            placa.Content = dentroPlaca;
            _absoluteLayout.Children.Add(placa);

            //  absoluteLayout.Children.Add(_VelocidadeRadarLabel);
            //  absoluteLayout.Children.Add(_DistanciaRadarLabel);
            if (PreferenciaUtils.Bussola)
            {
                _absoluteLayout.Children.Add(_BussolaFundo);
                _absoluteLayout.Children.Add(_BussolaAgulha);
                _absoluteLayout.Children.Add(_GPSSentidoLabel);
            }
            if (PreferenciaUtils.SinalGPS)
            {
                _absoluteLayout.Children.Add(_PrecisaoFundoImage);
                _absoluteLayout.Children.Add(_PrecisaoImage);
                _absoluteLayout.Children.Add(_PrecisaoLabel);
            }
            if (PreferenciaUtils.ExibirBotaoAdicionar)
                _absoluteLayout.Children.Add(_AdicionarRadarButton);

            if (PreferenciaUtils.Gratis)
            {
                var banner = new AdMobView
                {
                    WidthRequest = 320,
                    HeightRequest = 50,
                    HorizontalOptions = LayoutOptions.Center,
                    VerticalOptions = LayoutOptions.End
                };
                AbsoluteLayout.SetLayoutBounds(banner, new Rectangle(0, 0, 1, 1));
                AbsoluteLayout.SetLayoutFlags(banner, AbsoluteLayoutFlags.All);
                _absoluteLayout.Children.Add(banner);
            }

            Content = _absoluteLayout;
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            //_velocimetroPageAtual = this;
            GlobalUtils.Visual = this;
            /*
            if (PreferenciaUtils.SalvarPercurso) {
                var regraPercurso = PercursoFactory.create();
                var inicializou = regraPercurso.iniciarGravacao();
            }
            */
        }

        protected override void OnDisappearing()
        {
            base.OnDisappearing();
            //_velocimetroPageAtual = null;
            GlobalUtils.Visual = null;
        }

        public override void atualizarPosicao(LocalizacaoInfo posicao)
        {
            //_map.atualizarPosicao(posicao);
        }

        public override void redesenhar() {
            _velocimetro.redesenhar();
        }
    }
}
