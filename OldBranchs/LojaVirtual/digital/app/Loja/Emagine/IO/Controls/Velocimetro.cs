using Radar.BLL;
using Radar.Model;
using System;
using System.Diagnostics;
using Xamarin.Forms;
using Radar.Utils;

namespace Radar.Controls
{
	public class Velocimetro : BoxView
	{
		private int _loopInicio = 30;
		private int _loopFim = 90;

		private float _velocidadeAtual = 0;
		private float _velocidadeRadar = 0;

		public Velocimetro()
		{
			//WidthRequest = 480;
			//HeightRequest = 640;
		}

		public float VelocidadeAtual
		{
			get { return _velocidadeAtual; }
			set { _velocidadeAtual = value; }
		}

		public float VelocidadeRadar
		{
			get { return _velocidadeRadar; }
			set { _velocidadeRadar = value; }
		}

		/*
        public float TelaUtils.Largura
        {
            get { return pegarAlturaTela(); }
        }

        public float TelaUtils.Altura
        {
            get { return pegarLarguraTela(); }
        }
        */

		//VelocidadeInfo posicoes = new VelocidadeInfo();
		// Pixel density
		private readonly float density;

		public static readonly BindableProperty ShapeTypeProperty = BindableProperty.Create<Velocimetro, ShapeType>(s => s.ShapeType, ShapeType.Box);

		public static readonly BindableProperty StrokeColorProperty = BindableProperty.Create<Velocimetro, Color>(s => s.StrokeColor, Color.Default);

		public static readonly BindableProperty StrokeWidthProperty = BindableProperty.Create<Velocimetro, float>(s => s.StrokeWidth, 1f);

		public static readonly BindableProperty IndicatorPercentageProperty = BindableProperty.Create<Velocimetro, float>(s => s.IndicatorPercentage, 0f);

		public static readonly BindableProperty CornerRadiusProperty = BindableProperty.Create<Velocimetro, float>(s => s.CornerRadius, 0f);

		public static readonly BindableProperty PaddingProperty = BindableProperty.Create<Velocimetro, Thickness>(s => s.Padding, default(Thickness));

		public ShapeType ShapeType
		{
			get { return (ShapeType)GetValue(ShapeTypeProperty); }
			set { SetValue(ShapeTypeProperty, value); }
		}

		public Color StrokeColor
		{
			get { return (Color)GetValue(StrokeColorProperty); }
			set { SetValue(StrokeColorProperty, value); }
		}

		public float StrokeWidth
		{
			get { return (float)GetValue(StrokeWidthProperty); }
			set { SetValue(StrokeWidthProperty, value); }
		}

		public float IndicatorPercentage
		{
			get { return (float)GetValue(IndicatorPercentageProperty); }
			set
			{
				if (ShapeType != ShapeType.CircleIndicator)
					throw new ArgumentException("Can only specify this property with CircleIndicator");
				SetValue(IndicatorPercentageProperty, value);
			}
		}

		public float CornerRadius
		{
			get { return (float)GetValue(CornerRadiusProperty); }
			set
			{
				if (ShapeType != ShapeType.Box)
					throw new ArgumentException("Can only specify this property with Box");
				SetValue(CornerRadiusProperty, value);
			}
		}

		public Thickness Padding
		{
			get { return (Thickness)GetValue(PaddingProperty); }
			set { SetValue(PaddingProperty, value); }
		}

		public delegate void desenharTextoHandler(string Texto, float x, float y, PonteiroCorEnum cor);
		public desenharTextoHandler desenharTexto;

		public delegate void desenharTextoLabelHandler(string Texto, float x, float y, PonteiroCorEnum cor);
		public desenharTextoLabelHandler desenharTextoLabel;

		public delegate void desenharTextoVelocidadeHandler(string Texto, float x, float y, PonteiroCorEnum cor);
		public desenharTextoVelocidadeHandler desenharTextoVelocidade;

		public delegate void desenharPonteiroHandler(RetanguloInfo rect, PonteiroCorEnum cor);
		public desenharPonteiroHandler desenharPonteiro;

		/*
        public delegate float pegarAlturaTelaHandler();
        public pegarAlturaTelaHandler pegarAlturaTela;

        public delegate float pegarLarguraTelaHandler();
        public pegarLarguraTelaHandler pegarLarguraTela;
        */

		public delegate void redesenharHandler();
		public redesenharHandler redesenhar;

		public PonteiroCorEnum pegarCor(float velocidade)
		{
			if (velocidade <= VelocidadeAtual)
			{
				if (VelocidadeRadar > 0)
				{
					if (velocidade >= VelocidadeRadar)
						return PonteiroCorEnum.Vermelho;
					else
						return PonteiroCorEnum.Verde;
				}
				else
					return PonteiroCorEnum.Verde;
			}
			else {
				if (VelocidadeRadar > 0)
				{
					if (velocidade == VelocidadeRadar)
						return PonteiroCorEnum.Vermelho;
					else if (velocidade < VelocidadeRadar)
						return PonteiroCorEnum.CinzaClaro;
					else if (velocidade > VelocidadeRadar)
						return PonteiroCorEnum.Cinza;
				}
				else
					return PonteiroCorEnum.CinzaClaro;
			}
			return PonteiroCorEnum.Cinza;
		}

		public void desenhar()
		{
			PonteiroCorEnum textoCor = PonteiroCorEnum.Verde;
			if (VelocidadeRadar > 0 && VelocidadeAtual > VelocidadeRadar)
				textoCor = PonteiroCorEnum.Vermelho;
			
			
				if (TelaUtils.Largura > TelaUtils.Altura)
				{
					desenharTextoLabel("km/h", TelaUtils.Largura / 4.3F, TelaUtils.Altura / 1.7F, textoCor);
					if (Math.Floor(VelocidadeAtual) < 0)
					{
						desenharTextoVelocidade("0", TelaUtils.Largura / 4F, TelaUtils.Altura / 2F, textoCor);

					}
					else {
						desenharTextoVelocidade(Math.Floor(VelocidadeAtual).ToString(), TelaUtils.Largura / 4F, TelaUtils.Altura / 2F, textoCor);

					}
					
				}
				else
				{
					desenharTextoLabel("km/h", TelaUtils.Largura / 2.5F, TelaUtils.Altura / 3F, textoCor);
					desenharTextoVelocidade(Math.Floor(VelocidadeAtual).ToString(), TelaUtils.Largura / 2.3F, TelaUtils.Altura / 3.5F, textoCor);
				}
			
			//int contadorTexto = 0;
			if (Device.OS == TargetPlatform.iOS)
			{
				_loopInicio = 50;
				_loopFim = 110;
			}

			/*
            for (var loop = _loopInicio; loop <= _loopFim; loop++)
            {
                float tamX = 0;
                float tamY = 0;
                PonteiroCorEnum cor = PonteiroCorEnum.Cinza;
                if (loop % 5 == 0)
                {

                    if (TelaUtils.Largura > TelaUtils.Altura)
                    {
                        tamX = (TelaUtils.Largura / 3.8F) + (float)Math.Floor(((TelaUtils.Largura * 25 / 100) / 1.50F * Math.Cos(loop * 6 * Math.PI / 240)));
                        tamY = (TelaUtils.Altura / 2F) + (float)Math.Floor(((TelaUtils.Largura * 25 / 100) / 1.50F * Math.Sin(loop * 6 * Math.PI / 240)));

                    }
                    else {
                        tamX = (TelaUtils.Largura / 2.2F) + (float)Math.Floor(((TelaUtils.Altura * 23 / 100) / 1.50F * Math.Cos(loop * 6 * Math.PI / 240)));
                        tamY = (TelaUtils.Altura / 3.4F) + (float)Math.Floor(((TelaUtils.Altura * 23 / 100) / 1.50F * Math.Sin(loop * 6 * Math.PI / 240)));

                    }

                    if (contadorTexto <= (int)VelocidadeAtual / 2)
                    {
                        cor = PonteiroCorEnum.Cinza;
                    }
                    else {
                        cor = PonteiroCorEnum.Verde;
                    }

                    if (contadorTexto == (int)VelocidadeRadar / 2)
                    {
                        cor = PonteiroCorEnum.Vermelho;
                    }

                    if (contadorTexto > (int)VelocidadeRadar / 2)
                    {
                        cor = PonteiroCorEnum.CinzaClaro;
                    }

                    desenharTexto(num.ToString(), tamX, tamY, cor);
                    num = num + 10;
                }
                contadorTexto++;


            }
            */
			int count = 0;//, num = 120;
			if (Device.OS == TargetPlatform.iOS)
			{
				_loopInicio = 10;
				_loopFim = 70;
			}

			float tamX = 0, tamY = 0;
			int velocidade = 120;
			//for (var loop = _loopInicio - 20; loop <= _loopFim - 20; loop++)
			for (var loop = _loopFim - 20; loop >= _loopInicio - 20; loop--)
			{

				RetanguloInfo rect = new RetanguloInfo();

				velocidade = count * 2;
				PonteiroCorEnum cor = pegarCor(velocidade);


				if (loop % 5 == 0)
				{

					//int posicao = loop + 20;
					int posicao = 100 - loop;
					if (TelaUtils.Largura > TelaUtils.Altura)
					{
						if (TelaUtils.Dispositivo == "Pad" || TelaUtils.Dispositivo == "Phone")
						{
							rect.Left = (int)Math.Floor((TelaUtils.Largura / 3.5F) + (float)((TelaUtils.Largura * 40 / 100) / 1.50F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Right = (int)Math.Floor(TelaUtils.Largura / 3.5F + (float)((TelaUtils.Largura * 40 / 100) / 1.90F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Top = (int)Math.Floor((TelaUtils.Altura / 2F) + (float)((TelaUtils.Largura * 40 / 100) / 1.50F * Math.Cos(loop * 6 * Math.PI / 240)));
							rect.Bottom = (int)Math.Floor(TelaUtils.Altura / 2F + (float)((TelaUtils.Largura * 40 / 100) / 1.90 * Math.Cos(loop * 6 * Math.PI / 240)));

							tamX = (TelaUtils.Largura / 3.8F) + (float)Math.Floor(((TelaUtils.Largura * 25 / 100) / 1.50F * Math.Cos(posicao * 6 * Math.PI / 240)));
							tamY = (TelaUtils.Altura / 2F) + (float)Math.Floor(((TelaUtils.Largura * 25 / 100) / 1.50F * Math.Sin(posicao * 6 * Math.PI / 240)));
						}
						else {
							rect.Left = (int)Math.Floor((TelaUtils.Largura / 3.5F) + (float)((TelaUtils.Largura * 30 / 100) / 1.50F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Right = (int)Math.Floor(TelaUtils.Largura / 3.5F + (float)((TelaUtils.Largura * 30 / 100) / 1.90F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Top = (int)Math.Floor((TelaUtils.Altura / 2F) + (float)((TelaUtils.Largura * 30 / 100) / 1.50F * Math.Cos(loop * 6 * Math.PI / 240)));
							rect.Bottom = (int)Math.Floor(TelaUtils.Altura / 2F + (float)((TelaUtils.Largura * 30 / 100) / 1.90 * Math.Cos(loop * 6 * Math.PI / 240)));

							tamX = (TelaUtils.Largura / 3.8F) + (float)Math.Floor(((TelaUtils.Largura * 20 / 100) / 1.50F * Math.Cos(posicao * 6 * Math.PI / 240)));
							tamY = (TelaUtils.Altura / 2F) + (float)Math.Floor(((TelaUtils.Largura * 20 / 100) / 1.50F * Math.Sin(posicao * 6 * Math.PI / 240)));

						}
					}
					else {
						
							rect.Left = (int)Math.Floor((TelaUtils.Largura / 2F) + (float)((TelaUtils.Largura * 60 / 100) / 1.50F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Right = (int)Math.Floor(TelaUtils.Largura / 2F + (float)((TelaUtils.Largura * 60 / 100) / 1.90F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Top = (int)Math.Floor((TelaUtils.Altura / 3.5F) + (float)((TelaUtils.Largura * 60 / 100) / 1.50F * Math.Cos(loop * 6 * Math.PI / 240)));
							rect.Bottom = (int)Math.Floor(TelaUtils.Altura / 3.5F + (float)((TelaUtils.Largura * 60 / 100) / 1.90 * Math.Cos(loop * 6 * Math.PI / 240)));

							tamX = (TelaUtils.Largura / 2.2F) + (float)Math.Floor(((TelaUtils.Altura * 23 / 100) / 1.50F * Math.Cos(posicao * 6 * Math.PI / 240)));
							tamY = (TelaUtils.Altura / 3.4F) + (float)Math.Floor(((TelaUtils.Altura * 23 / 100) / 1.50F * Math.Sin(posicao * 6 * Math.PI / 240)));
					
						if (TelaUtils.Orientacao == "LandscapeLeft" || TelaUtils.Orientacao == "LandscapeRight")
						{

							rect.Left = (int)Math.Floor((TelaUtils.Largura / 2F) + (float)((TelaUtils.Largura * 50 / 100) / 1.50F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Right = (int)Math.Floor(TelaUtils.Largura / 2F + (float)((TelaUtils.Largura * 50 / 100) / 1.90F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Top = (int)Math.Floor((TelaUtils.Altura / 3F) + (float)((TelaUtils.Largura * 50 / 100) / 1.50F * Math.Cos(loop * 6 * Math.PI / 240)));
							rect.Bottom = (int)Math.Floor(TelaUtils.Altura / 3F + (float)((TelaUtils.Largura * 50 / 100) / 1.90 * Math.Cos(loop * 6 * Math.PI / 240)));
							if (TelaUtils.Dispositivo == "Pad")
							{
								tamX = (TelaUtils.Largura / 2F) + (float)Math.Floor(((TelaUtils.Altura * 24 / 100) / 1.50F * Math.Cos(posicao * 6 * Math.PI / 240)));
								tamY = (TelaUtils.Altura / 3F) + (float)Math.Floor(((TelaUtils.Altura * 24 / 100) / 1.50F * Math.Sin(posicao * 6 * Math.PI / 240)));
							}else{
								tamX = (TelaUtils.Largura / 2F) + (float)Math.Floor(((TelaUtils.Altura * 18 / 100) / 1.50F * Math.Cos(posicao * 6 * Math.PI / 240)));
								tamY = (TelaUtils.Altura / 3F) + (float)Math.Floor(((TelaUtils.Altura * 18 / 100) / 1.50F * Math.Sin(posicao * 6 * Math.PI / 240)));

							}
						}
					
					}


					/*
                    if (count <= (120 - (int)VelocidadeAtual) / 2 - 2)
                    {
                        cor = PonteiroCorEnum.Verde;
                    }

                    if (count < (120 - (int)VelocidadeRadar) / 2 + 2)
                    {
                        cor = PonteiroCorEnum.CinzaClaro;
                    }

                    if (count == radarVelocidade())
                    {
                        cor = PonteiroCorEnum.Vermelho;
                    }
                    */

					//desenharPonteiro(rect, cor);
					//if (loop < (_loopFim - 40))
					//{
					desenharTexto(velocidade.ToString(), tamX, tamY, cor);
					//num = num - 10;
					//}
				}
				else {
					if (TelaUtils.Largura > TelaUtils.Altura)
					{
						if (TelaUtils.Dispositivo == "Pad" || TelaUtils.Dispositivo == "Phone")
						{
							rect.Left = (int)Math.Floor((TelaUtils.Largura / 3.5F) + (float)((TelaUtils.Largura * 40 / 100) / 1.50F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Right = (int)Math.Floor(TelaUtils.Largura / 3.5F + (float)((TelaUtils.Largura * 40 / 100) / 1.70F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Top = (int)Math.Floor((TelaUtils.Altura / 2F) + (float)((TelaUtils.Largura * 40 / 100) / 1.50F * Math.Cos(loop * 6 * Math.PI / 240)));
							rect.Bottom = (int)Math.Floor(TelaUtils.Altura / 2F + (float)((TelaUtils.Largura * 40 / 100) / 1.70 * Math.Cos(loop * 6 * Math.PI / 240)));
						}
						else {
							rect.Left = (int)Math.Floor((TelaUtils.Largura / 3.5F) + (float)((TelaUtils.Largura * 30 / 100) / 1.50F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Right = (int)Math.Floor(TelaUtils.Largura / 3.5F + (float)((TelaUtils.Largura * 30 / 100) / 1.70F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Top = (int)Math.Floor((TelaUtils.Altura / 2F) + (float)((TelaUtils.Largura * 30 / 100) / 1.50F * Math.Cos(loop * 6 * Math.PI / 240)));
							rect.Bottom = (int)Math.Floor(TelaUtils.Altura / 2F + (float)((TelaUtils.Largura * 30 / 100) / 1.70 * Math.Cos(loop * 6 * Math.PI / 240)));

						}
					}
					else {
						
							rect.Left = (int)Math.Floor((TelaUtils.Largura / 2F) + (float)((TelaUtils.Largura * 60 / 100) / 1.50F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Right = (int)Math.Floor(TelaUtils.Largura / 2F + (float)((TelaUtils.Largura * 60 / 100) / 1.70F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Top = (int)Math.Floor((TelaUtils.Altura / 3.5F) + (float)((TelaUtils.Largura * 60 / 100) / 1.50F * Math.Cos(loop * 6 * Math.PI / 240)));
							rect.Bottom = (int)Math.Floor(TelaUtils.Altura / 3.5F + (float)((TelaUtils.Largura * 60 / 100) / 1.70 * Math.Cos(loop * 6 * Math.PI / 240)));

						if (TelaUtils.Orientacao == "LandscapeLeft" || TelaUtils.Orientacao == "LandscapeRight")
						{

							rect.Left = (int)Math.Floor((TelaUtils.Largura / 2F) + (float)((TelaUtils.Largura * 50 / 100) / 1.50F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Right = (int)Math.Floor(TelaUtils.Largura / 2F + (float)((TelaUtils.Largura * 50 / 100) / 1.70F * Math.Sin(loop * 6 * Math.PI / 240)));
							rect.Top = (int)Math.Floor((TelaUtils.Altura / 3F) + (float)((TelaUtils.Largura * 50 / 100) / 1.50F * Math.Cos(loop * 6 * Math.PI / 240)));
							rect.Bottom = (int)Math.Floor(TelaUtils.Altura / 3F + (float)((TelaUtils.Largura * 50 / 100) / 1.70 * Math.Cos(loop * 6 * Math.PI / 240)));

						}
					}

					/*
                    if (count <= (120 - (int)VelocidadeAtual) / 2)
                    {
                        cor = PonteiroCorEnum.Verde;
                    }

                    if (count < (120 - (int)VelocidadeRadar) / 2)
                    {
                        cor = PonteiroCorEnum.CinzaClaro;
                    }
                    */
					//desenharPonteiro(rect, cor);
				}
				/*

                if (count <= (120 - (int)VelocidadeAtual) / 2 - 2)
                    cor = PonteiroCorEnum.Verde;

                if (count < (120 - (int)VelocidadeRadar) / 2 + 2)
                    cor = PonteiroCorEnum.CinzaClaro;

                if (count == radarVelocidade())
                    cor = PonteiroCorEnum.Vermelho;
                */

				desenharPonteiro(rect, cor);
				count++;
			}

		}

		private float Resize(float input)
		{
			return input * density;
		}

		private float Resize(double input)
		{
			return Resize((float)input);
		}

		public int radarVelocidade()
		{
			int fator = 0;
			switch ((int)VelocidadeRadar)
			{
				case 110:
					fator = 5;
					break;
				case 100:
					fator = 10;
					break;
				case 90:
					fator = 15;
					break;
				case 80:
					fator = 20;
					break;
				case 70:
					fator = 25;
					break;
				case 60:
					fator = 30;
					break;
				case 50:
					fator = 35;
					break;
				case 40:
					fator = 40;
					break;
				case 30:
					fator = 45;
					break;
			}
			return fator;
		}

	}

	public enum ShapeType
	{
		Box,
		Circle,
		CircleIndicator
	}
}