using Radar.BLL;
using Radar.Factory;
using Radar.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Radar.Controls;
using Radar.Utils;
using System.Diagnostics;

namespace Radar.Pages
{
    public partial class RadarListaPage : ContentPage
    {
        public RadarListaPage()
        {
            InitializeComponent();


        }

        protected override void OnAppearing()
        {
			RadarBLL regraRadar = RadarFactory.create();
			RadarListView.RowHeight = 150;
			RadarListView.ItemTapped += OnTap;
			regraRadar.atualizarEndereco();
			RadarListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
			RadarListView.ItemTemplate = new DataTemplate(typeof(ConteudoCelula));

			var radar = regraRadar.listar(true);

			//desc.VerticalOptions = LayoutOptions.Center;


			if (radar.Count > 0)
			{
				//percursoListView.SetBinding(Label.TextProperty, new Binding("Data"));
				this.BindingContext = radar;

			}
            
        }
		public void OnTap(object sender, ItemTappedEventArgs e)
		{

			if (e == null)
				return;

		}
        

		public class ConteudoCelula : ViewCell
		{
			StackLayout desc = new StackLayout();


			public ConteudoCelula()
			{
				
				MenuItem excluirRadar = new MenuItem();

				excluirRadar.CommandParameter = "{Binding .}";
				excluirRadar.Text = "Excluir";
				excluirRadar.IsDestructive = true;
				excluirRadar.Clicked += async (object sender, EventArgs e) =>
				{
					RadarInfo radar = (RadarInfo)((MenuItem)sender).BindingContext;
					RadarBLL regraRadar = RadarFactory.create();
					regraRadar.excluir(radar.Id);
					ListView RadarListView = this.Parent as ListView;

					RadarListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));

					var percursos = regraRadar.listar(true);
					RadarListView.BindingContext = percursos;
					RadarListView.ItemTemplate = new DataTemplate(typeof(ConteudoCelula));
				};

				this.ContextActions.Add(excluirRadar);


				//desc.VerticalOptions = LayoutOptions.Center;
				desc.HorizontalOptions = LayoutOptions.FillAndExpand;
				desc.Orientation = StackOrientation.Horizontal;

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
					HorizontalOptions = LayoutOptions.Start,
					Margin = new Thickness(0, 0, 0, 0),
					WidthRequest = main.WidthRequest * 0.2

				};

				StackLayout cardLeftStack = new StackLayout()
				{
					Orientation = StackOrientation.Vertical

				};

				Image percursoIco = new Image()
				{
					Source = ImageSource.FromFile("meusradares.png"),
					WidthRequest = cardLeft.WidthRequest / 1.5,
					HorizontalOptions = LayoutOptions.Center,
					VerticalOptions = LayoutOptions.CenterAndExpand
				};


				cardLeftStack.Children.Add(percursoIco);
				cardLeft.Content = cardLeftStack;

				Frame cardRigth = new Frame()
				{
					HorizontalOptions = LayoutOptions.Start,
					WidthRequest = main.WidthRequest * 0.7

				};

				WrapLayout cardRigthStackHor = new WrapLayout()
				{
					//Orientation = StackOrientation.Horizontal,
					HorizontalOptions = LayoutOptions.Fill,
					VerticalOptions = LayoutOptions.Fill,
					Spacing = 1

				};
				StackLayout cardRigthStackVer = new StackLayout()
				{
					Orientation = StackOrientation.Vertical,
					Spacing = 1

				};

				Label titulo = new Label()
				{
					//Text = "31/0ut - 17:41",
					HorizontalOptions = LayoutOptions.StartAndExpand,
					FontSize = 26,
					FontFamily = "Roboto-Condensed",
					TextColor = Color.FromHex(TemaInfo.PrimaryColor)
				};
				titulo.SetBinding(Label.TextProperty, new Binding("DataTituloStr"));

				Label limite = new Label()
				{
					HorizontalOptions = LayoutOptions.StartAndExpand,
					FontSize = 14,
					FontFamily = "Roboto-Condensed",
					TextColor = Color.FromHex(TemaInfo.PrimaryColor)
				};
				limite.SetBinding(Label.TextProperty, new Binding("VelocidadeStr"));


				Label latitude = new Label()
				{ 
					
					HorizontalOptions = LayoutOptions.StartAndExpand,
					FontSize = 14,
					FontFamily = "Roboto-Condensed",
					TextColor = Color.FromHex(TemaInfo.PrimaryColor)
				};
				latitude.SetBinding(Label.TextProperty,  new Binding("LatitudeText"));
			
				Label longitude = new Label()
				{
					//Text = "Longitude: -49,23480 ",
					HorizontalOptions = LayoutOptions.StartAndExpand,
					FontSize = 14,
					FontFamily = "Roboto-Condensed",
					TextColor = Color.FromHex(TemaInfo.PrimaryColor)
				};
				longitude.SetBinding(Label.TextProperty, new Binding("LongitudeText"));


				Label angulo = new Label()
				{
					//Text = "Ângulo: 179.0 ",
					HorizontalOptions = LayoutOptions.StartAndExpand,
					FontSize = 14,
					FontFamily = "Roboto-Condensed",
					TextColor = Color.FromHex(TemaInfo.PrimaryColor)
				};
				angulo.SetBinding(Label.TextProperty,  new Binding("DirecaoText"));


				Label endereco = new Label()
				{
					//Text = "Rua H-149, 1-73 Cidade Vera Cruz/ Aparecida de Goiânia ",
					HorizontalOptions = LayoutOptions.StartAndExpand,
					//VerticalOptions = LayoutOptions.StartAndExpand,
					//WidthRequest = cardRigth.WidthRequest * 0.8,
					FontSize = 16,
					FontFamily = "Roboto-Condensed",
					//HorizontalTextAlignment = TextAlignment.Center
				};
				endereco.SetBinding(Label.TextProperty, new Binding("Endereco"));


				BoxView linha = new BoxView()
				{
					HeightRequest = 1,
					BackgroundColor = Color.FromHex(TemaInfo.DividerColor),
					VerticalOptions = LayoutOptions.CenterAndExpand
				};

				if (TelaUtils.Orientacao == "LandscapeLeft" || TelaUtils.Orientacao == "LandscapeRight")
				{
					percursoIco.WidthRequest = cardLeft.WidthRequest / 2;
					//cardLeft.WidthRequest = main.WidthRequest * 0.15;
					cardRigth.WidthRequest = main.WidthRequest * 0.45;
				}
				cardRigthStackVer.Children.Add(titulo);
				cardRigthStackVer.Children.Add(linha);
				cardRigthStackHor.Children.Add(limite);
				cardRigthStackHor.Children.Add(latitude);
				cardRigthStackHor.Children.Add(longitude);
				cardRigthStackHor.Children.Add(angulo);
				cardRigthStackVer.Children.Add(cardRigthStackHor);
				cardRigthStackVer.Children.Add(endereco);

				cardRigthStackVer.WidthRequest = main.WidthRequest * 0.8;
				//desc.Children.Add(cardRigthStack);

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
