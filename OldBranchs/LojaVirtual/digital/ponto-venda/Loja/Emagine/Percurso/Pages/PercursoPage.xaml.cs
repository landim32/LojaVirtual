using Emagine.Utils;
using Radar.BLL;
using Radar.Factory;
using Radar.Model;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Radar.Controls;
using System.Diagnostics;

namespace Radar.Pages
{
	public partial class PercursoPage : ContentPage
	{
		WrapLayout desc = new WrapLayout();

		Label tempoCorrendo = new Label();
		Label tempoParado = new Label();
		Label paradas = new Label();
		Label velocidadeMaxima = new Label();
		Label velocidadeMedia = new Label();
		Label radares = new Label();

		Image relogioIco = new Image();
		Image paradoIco = new Image();
		Image ampulhetaIco = new Image();
		Image velocimetroIco = new Image();
		Image velocimetroIco2 = new Image();
		Image radarIco = new Image();

		public PercursoPage()
		{
			InitializeComponent();

		}

		protected override void OnAppearing()
		{
			PercursoBLL regraPercurso = PercursoFactory.create();
			percursoListView.ItemTemplate = new DataTemplate(typeof(PercursoPageCell));
			percursoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));


			var percursos = regraPercurso.listar();

			//desc.VerticalOptions = LayoutOptions.Center;
			desc.HorizontalOptions = LayoutOptions.Fill;
			desc.WidthRequest = TelaUtils.LarguraSemPixel * 0.7;
			desc.Spacing = 1;


			tempoCorrendo.HorizontalOptions = LayoutOptions.Start;
			tempoParado.HorizontalOptions = LayoutOptions.Start;
			paradas.HorizontalOptions = LayoutOptions.Start;
			paradas.VerticalOptions = LayoutOptions.Center;
			velocidadeMaxima.HorizontalOptions = LayoutOptions.Start;
			velocidadeMedia.HorizontalOptions = LayoutOptions.Start;
			radares.HorizontalOptions = LayoutOptions.Start;

			relogioIco.Source = ImageSource.FromFile("relogio_20x20_preto.png");
			paradoIco.Source = ImageSource.FromFile("mao_20x20_preto.png");
			ampulhetaIco.Source = ImageSource.FromFile("ampulheta_20x20_preto.png");
			velocimetroIco.Source = ImageSource.FromFile("velocimetro_20x20_preto.png");
			velocimetroIco2.Source = ImageSource.FromFile("velocimetro_20x20_preto.png");
			radarIco.Source = ImageSource.FromFile("radar_20x20_preto.png");

			desc.Children.Add(relogioIco);
			desc.Children.Add(tempoCorrendo);
			desc.Children.Add(ampulhetaIco);
			desc.Children.Add(tempoParado);
			desc.Children.Add(paradoIco);
			desc.Children.Add(paradas);
			desc.Children.Add(velocimetroIco);
			desc.Children.Add(velocidadeMedia);
			desc.Children.Add(velocimetroIco2);
			desc.Children.Add(velocidadeMaxima);
			desc.Children.Add(radarIco);
			desc.Children.Add(radares);

			if (percursos.Count > 0)
			{
				//percursoListView.SetBinding(Label.TextProperty, new Binding("Data"));
				this.BindingContext = percursos;

			}

		}

		 void gravarPercurso(object sender, EventArgs e)
		{
			//Label gravarButton = (Label)sender;
			PercursoBLL regraPercurso = PercursoFactory.create();
			if (PercursoBLL.Gravando)
			{
				if (regraPercurso.pararGravacao())
				{
					gravarLabel.Text = "Gravar Percurso!";
					infoLabel.Text = "Toque aqui para iniciar a gravação";
					stackDescricaoGravando.Children.Add(gravarLabel);
					stackDescricaoGravando.Children.Add(infoLabel);
					stackDescricaoGravando.Children.Remove(desc);

					icoPlay.Source = ImageSource.FromFile("Play.png");
                    Emagine.Utils.MensagemUtils.avisar("Gravação finalizada!");
                    Emagine.Utils.MensagemUtils.pararNotificaoPermanente(PercursoBLL.NOTIFICACAO_GRAVAR_PERCURSO_ID);

					var percursos = regraPercurso.listar();
					percursoListView.BindingContext = percursos;
					percursoListView.ItemTemplate = new DataTemplate(typeof(PercursoPageCell));
					percursoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));


				}
				else {
                    Emagine.Utils.MensagemUtils.avisar("Não foi possível parar a gravação!");
				}
			}
			else {
				
				if (regraPercurso.iniciarGravacao((s2, e2) =>
				{
					tempoCorrendo.Text = e2.Ponto.TempoGravacao.ToString();
					tempoParado.Text = e2.Ponto.TempoParadoStr;

					paradas.Text = e2.Ponto.QuantidadeParadaStr;

					velocidadeMedia.Text = e2.Ponto.VelocidadeMediaStr;

					velocidadeMaxima.Text = e2.Ponto.VelocidadeMaximaStr;

					radares.Text = e2.Ponto.QuantidadeRadarStr;
				})) 
				{
					
					stackDescricaoGravando.Children.Remove(gravarLabel);
					stackDescricaoGravando.Children.Remove(infoLabel);
					stackDescricaoGravando.Children.Add(desc);

					/*
					//PercursoInfo percursoInfo = new PercursoInfo();

					tempoCorrendo.Text = percursoInfo.TempoGravacaoStr ;

					tempoParado.Text = percursoInfo.TempoParadoStr;

					paradas.Text = percursoInfo.QuantidadeParadaStr;

					velocidadeMedia.Text = percursoInfo.VelocidadeMediaStr;

					velocidadeMaxima.Text = percursoInfo.VelocidadeMaximaStr;

					radares.Text = percursoInfo.QuantidadeRadarStr;
					*/

					icoPlay.Source = ImageSource.FromFile("Stop.png");
                    Emagine.Utils.MensagemUtils.avisar("Iniciando gravação do percurso!");
                    Emagine.Utils.MensagemUtils.notificarPermanente(
                        PercursoBLL.NOTIFICACAO_GRAVAR_PERCURSO_ID, 
                        "Gravando Percurso...", "",
                        PercursoBLL.NOTIFICACAO_PARAR_PERCURSO_ID, 
                        "Parar", PercursoBLL.ACAO_PARAR_GRAVACAO
                    );
				}
				else {
                    Emagine.Utils.MensagemUtils.avisar("Não foi possível iniciar a gravação!");
				}
			}
		}

		public void abrirPercurso(object sender, EventArgs e)
		{
		}
		/*
		public void excluirPercurso(object sender, EventArgs e)
		{
			PercursoInfo percurso = (PercursoInfo)((MenuItem)sender).BindingContext;
			PercursoBLL regraPercurso = PercursoFactory.create();
			regraPercurso.excluir(percurso.Id);
			OnAppearing();
		}

		public void simularPercurso(object sender, EventArgs e)
		{
			PercursoInfo percurso = (PercursoInfo)((MenuItem)sender).BindingContext;
			if (percurso != null)
			{
				GPSUtils.simularPercurso(percurso.Id);
			}
		}
		*/
		public class PercursoPageCell : ViewCell
		{
			WrapLayout desc = new WrapLayout();

			Label tempoCorrendo = new Label();
			Label tempoParado = new Label();
			Label paradas = new Label();
			Label velocidadeMaxima = new Label();
			Label velocidadeMedia = new Label();
			Label radares = new Label();

			Image relogioIco = new Image();
			Image paradoIco = new Image();
			Image ampulhetaIco = new Image();
			Image velocimetroIco = new Image();
			Image velocimetroIco2 = new Image();
			Image radarIco = new Image();

			public PercursoPageCell()
			{
				var excluiPercurso = new MenuItem
				{
					Text = "Excluir"
				};

				excluiPercurso.SetBinding(MenuItem.CommandParameterProperty, new Binding("."));
				excluiPercurso.Clicked += (sender, e) =>
				{
					PercursoInfo percurso = (PercursoInfo)((MenuItem)sender).BindingContext;
					PercursoBLL regraPercurso = PercursoFactory.create();
					regraPercurso.excluir(percurso.Id);

					ListView percursoListView = this.Parent as ListView;

					percursoListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));

					var percursos = regraPercurso.listar();
					percursoListView.BindingContext = percursos;
					percursoListView.ItemTemplate = new DataTemplate(typeof(PercursoPageCell));
				};

				var simulaPercurso = new MenuItem
				{
					Text = "Simular"
				};

				simulaPercurso.SetBinding(MenuItem.CommandParameterProperty, new Binding("."));
				simulaPercurso.Clicked += ( sender, e) =>
				{
					PercursoInfo percurso = (PercursoInfo)((MenuItem)sender).BindingContext;
					if (percurso != null)
						GPSUtils.simularPercurso(percurso.Id);
					OnAppearing();
				};

				ContextActions.Add(simulaPercurso);
				ContextActions.Add(excluiPercurso);


				//desc.VerticalOptions = LayoutOptions.Center;
				desc.HorizontalOptions = LayoutOptions.Fill;
				desc.VerticalOptions = LayoutOptions.CenterAndExpand;
				desc.Spacing = 1;

				tempoCorrendo.HorizontalOptions = LayoutOptions.Start;
				tempoParado.HorizontalOptions = LayoutOptions.Start;
				paradas.HorizontalOptions = LayoutOptions.Start;
				paradas.VerticalOptions = LayoutOptions.Center;
				velocidadeMaxima.HorizontalOptions = LayoutOptions.Start;
				velocidadeMedia.HorizontalOptions = LayoutOptions.Start;
				radares.HorizontalOptions = LayoutOptions.Start;

				relogioIco.Source = ImageSource.FromFile("relogio_20x20_preto.png");
				paradoIco.Source = ImageSource.FromFile("mao_20x20_preto.png");
				ampulhetaIco.Source = ImageSource.FromFile("ampulheta_20x20_preto.png");
				velocimetroIco.Source = ImageSource.FromFile("velocimetro_20x20_preto.png");
				velocimetroIco2.Source = ImageSource.FromFile("velocimetro_20x20_preto.png");
				radarIco.Source = ImageSource.FromFile("radar_20x20_preto.png");

				tempoCorrendo.SetBinding(Label.TextProperty, new Binding("TempoGravacaoStr"));
				tempoCorrendo.FontSize = 14;
				tempoParado.SetBinding(Label.TextProperty, new Binding("TempoParadoStr"));
				tempoParado.FontSize = 14;
				paradas.SetBinding(Label.TextProperty, new Binding("QuantidadeParadaStr"));
				paradas.FontSize = 14;
				velocidadeMedia.SetBinding(Label.TextProperty, new Binding("VelocidadeMediaStr"));
				velocidadeMedia.FontSize = 14;
				velocidadeMaxima.SetBinding(Label.TextProperty, new Binding("VelocidadeMaximaStr"));
				velocidadeMaxima.FontSize = 14;
				radares.SetBinding(Label.TextProperty, new Binding("QuantidadeRadarStr"));
				radares.FontSize = 14;

				desc.Children.Add(relogioIco);
				desc.Children.Add(tempoCorrendo);
				desc.Children.Add(ampulhetaIco);
				desc.Children.Add(tempoParado);
				desc.Children.Add(paradoIco);
				desc.Children.Add(paradas);
				desc.Children.Add(velocimetroIco);
				desc.Children.Add(velocidadeMedia);
				desc.Children.Add(velocimetroIco2);
				desc.Children.Add(velocidadeMaxima);
				desc.Children.Add(radarIco);
				desc.Children.Add(radares);

				StackLayout main = new StackLayout()
				{
					Margin = new Thickness(5, 0, 5, 0),
					VerticalOptions = LayoutOptions.Fill,
					Orientation = StackOrientation.Horizontal,
					HorizontalOptions = LayoutOptions.Fill,

					WidthRequest = TelaUtils.LarguraSemPixel
				};

				Frame cardLeft = new Frame()
				{
					HorizontalOptions = LayoutOptions.Center,
					Margin = new Thickness(0, 0, 0, 90),
					WidthRequest = main.WidthRequest * 0.2

				};

				StackLayout cardLeftStack = new StackLayout()
				{
					Orientation = StackOrientation.Vertical,
					HorizontalOptions = LayoutOptions.Fill,
					VerticalOptions = LayoutOptions.Fill
				
				};

				Image percursoIco = new Image()
				{
					Source = ImageSource.FromFile("percursos.png"),
					WidthRequest = cardLeft.WidthRequest  * 0.3,
					HorizontalOptions = LayoutOptions.Center,
					VerticalOptions = LayoutOptions.Start
				};

				BoxView linha = new BoxView()
				{
					HeightRequest = 1,
					BackgroundColor = Color.FromHex(TemaInfo.DividerColor),
					HorizontalOptions = LayoutOptions.Fill,
					VerticalOptions = LayoutOptions.Start
				};

				Label distanciaText = new Label()
				{
					FontSize = 14,
					TextColor = Color.FromHex(TemaInfo.PrimaryColor),
					FontFamily = "Roboto-Condensed",
					HorizontalOptions = LayoutOptions.Center,
					VerticalOptions = LayoutOptions.Start
				};
				distanciaText.SetBinding(Label.TextProperty, new Binding("DistanciaTotalStr"));

				cardLeftStack.Children.Add(percursoIco);
				//cardLeftStack.Children.Add(linha);
				cardLeftStack.Children.Add(distanciaText);
				cardLeft.Content = cardLeftStack;

				Frame cardRigth = new Frame()
				{
					HorizontalOptions = LayoutOptions.Start,
					WidthRequest = main.WidthRequest * 0.7

				};
				if (TelaUtils.Orientacao == "Landscape")
				{
					cardLeft.Margin = new Thickness(0, 0, 0, 70);
					cardLeft.WidthRequest = main.WidthRequest * 0.15;
					cardRigth.WidthRequest = main.WidthRequest * 0.5;
				}
				if (TelaUtils.Orientacao == "LandscapeLeft" || TelaUtils.Orientacao == "LandscapeRight")
				{
					cardLeft.Margin = new Thickness(0, 0, 0, 70);
					cardLeft.WidthRequest = main.WidthRequest * 0.15;
					cardRigth.WidthRequest = main.WidthRequest * 0.5;
				}
				StackLayout cardRigthStackVer = new StackLayout()
				{
					Orientation = StackOrientation.Vertical,
					Spacing = 1

				};


				Label titulo = new Label()
				{
					HorizontalOptions = LayoutOptions.StartAndExpand,
					FontSize = 26,
					FontFamily = "Roboto-Condensed",
					TextColor = Color.FromHex(TemaInfo.PrimaryColor)
				};
				titulo.SetBinding(Label.TextProperty, new Binding("DataTituloStr"));

				Label endereco = new Label()
				{
					//Text = "Rua H-149, 1-73 Cidade Vera Cruz/ Aparecida de Goiânia",
					WidthRequest = main.WidthRequest * 0.7,
					HorizontalOptions = LayoutOptions.StartAndExpand,
					FontSize = 16,
					FontFamily = "Roboto-Condensed",
					//HorizontalTextAlignment = TextAlignment.Start
				};
				endereco.SetBinding(Label.TextProperty, new Binding("EnderecoStr"));


				cardRigthStackVer.Children.Add(titulo);
				cardRigthStackVer.Children.Add(linha);
				cardRigthStackVer.Children.Add(endereco);
				cardRigthStackVer.Children.Add(desc);

				cardRigth.Content = cardRigthStackVer;

				//if (main.WidthRequest > 320)
				//{

					main.Children.Add(cardLeft);
				//}
				main.Children.Add(cardRigth);

				View = main;

			}


		}
	}
}
