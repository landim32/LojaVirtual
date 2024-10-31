using Emagine.Utils;
using Radar.Model;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Pages
{
    public class PreferenciaPage: ContentPage
    {
        private static PreferenciaPage _PreferenciaPageAtual;
        public List<ListaInfo> menus;

        public static PreferenciaPage Atual
        {
            get
            {
                return _PreferenciaPageAtual;
            }
            private set
            {
                _PreferenciaPageAtual = value;
            }
        }

        public PreferenciaPage()
        {

            menus = new List<ListaInfo>();
            ListView lstView = new ListView();
            lstView.RowHeight = 60;
            this.Title = "Configurações";
            lstView.ItemTemplate = new DataTemplate(typeof(MenusCelula));
            lstView.ItemTapped += OnTap;

            menus.Add(new ListaInfo()
            {
                Titulo = "Mapa",
                Imagem = "modomapa.png",
                aoClicar = (sender, e) => {
                    NavegacaoUtils.PushAsync(new ModoMapaPage());
                }
            });
            menus.Add(new ListaInfo()
            {
                Titulo = "Alertas",
                Imagem = "alerta.png",
                aoClicar = (sender, e) =>
                {
                    NavigationX.create(this).PushAsync(new ModoAlertaPage(), true);
                }
            });
            menus.Add(new ListaInfo()
            {
                Titulo = "Som",
                Imagem = "audio.png",
                aoClicar = (sender, e) =>
                {
                    NavigationX.create(this).PushAsync(new ModoAudioPage(), true);
                }
            });
            menus.Add(new ListaInfo()
            {
                Titulo = "Alerta de Voz",
                Imagem = "reproducaodevoz.png",
                aoClicar = (sender, e) =>
                {
                    NavigationX.create(this).PushAsync(new ModoReproducaoVozPage(), true);
                }
            });
            menus.Add(new ListaInfo()
            {
                Titulo = "Geral",
                Imagem = "gerais.png",
                aoClicar = (sender, e) =>
                {
                    NavigationX.create(this).PushAsync(new ModoGeralPage(), true);
                }
            });
            if (Device.OS == TargetPlatform.Android)
            {
                menus.Add(new ListaInfo()
                {
                    Titulo = "Início automático",
                    Imagem = "autoiniciodesligamento.png",
                    aoClicar = (sender, e) =>
                    {
                        NavigationX.create(this).PushAsync(new ModoAutoInicioPage(), true);
                    }
                });
            }
            menus.Add(new ListaInfo()
            {
                Titulo = "Percurso",
                Imagem = "percursos.png",
                aoClicar = (sender, e) =>
                {
                    NavigationX.create(this).PushAsync(new ModoPercursoPage(), true);

                }
            });
            menus.Add(new ListaInfo()
            {
                Titulo = "Radares",
                Imagem = "meusradares.png",
                aoClicar = (sender, e) =>
                {
                    this.Navigation.PushAsync(new ModoMeuRadarPage());

                }
            });
            lstView.ItemsSource = menus;
            lstView.HasUnevenRows = true;
            lstView.SeparatorColor = Color.Transparent;

            Content = lstView;
        }


        public void OnTap(object sender, ItemTappedEventArgs e)
        {

            ListaInfo item = (ListaInfo)e.Item;
            if (item.aoClicar != null)
            {
                item.aoClicar(sender, e);
            }

        }

        public class MenusCelula : ViewCell
        {

            public MenusCelula()
            {


                Label nameLabel = new Label
                {
                    TextColor = Color.FromHex(TemaInfo.PrimaryText),
                    FontFamily = "Roboto-Condensed",
                    FontSize = 20,
                    HorizontalOptions = LayoutOptions.Start,
                    VerticalOptions = LayoutOptions.Center,


                };

                var icone = new Image();
                nameLabel.SetBinding(Label.TextProperty, new Binding("Titulo"));
                if (Device.OS == TargetPlatform.iOS)
                {
                    icone.Margin = new Thickness(10, 0, 0, 0);
                }
                var horizontalLayout = new StackLayout();
                var frameOuter = new Frame();

                icone.WidthRequest = 60;
                icone.HeightRequest = 60;
                icone.SetBinding(Image.SourceProperty, new Binding("Imagem"));


                icone.HorizontalOptions = LayoutOptions.Start;

                //horizontalLayout.Padding = new Thickness(10, 20, 10, 20);
                horizontalLayout.Orientation = StackOrientation.Horizontal;
                horizontalLayout.HorizontalOptions = LayoutOptions.Fill;
                horizontalLayout.VerticalOptions = LayoutOptions.Fill;
                horizontalLayout.HeightRequest = AbsoluteLayout.AutoSize;
                horizontalLayout.WidthRequest = AbsoluteLayout.AutoSize;
                //horizontalLayout.HeightRequest = 40;
                //frameOuter.Padding = new Thickness(5, 0, 5, 0);
                //frameOuter.HeightRequest = 66;
                //frameInner.OutlineColor = Color.Black;
                frameOuter.BackgroundColor = Color.FromHex(TemaInfo.BlueAccua);
                frameOuter.HeightRequest = AbsoluteLayout.AutoSize;
                if (Device.OS == TargetPlatform.iOS)
                {
                    icone.WidthRequest = 60;

                    frameOuter.Padding = new Thickness(5, 10, 5, 10);
                    frameOuter.WidthRequest = TelaUtils.Largura * 0.9;
                    frameOuter.Margin = new Thickness(5, 10, 5, 0);

                }
                else {
                    frameOuter.Margin = new Thickness(5, 10, 5, 10);
                }


                //verticaLayout.Children.Add(nameLabel);
                horizontalLayout.Children.Add(icone);
                horizontalLayout.Children.Add(nameLabel);
                //frameInner.Content = horizontalLayout;
                frameOuter.Content = horizontalLayout;



                // add to parent view
                View = frameOuter;
                this.View.BackgroundColor = Color.FromHex(TemaInfo.BlueAccua);

                //this.Tapped += (sender, e) =>
                //{
                //	this.View.BackgroundColor = Color.FromHex(TemaInfo.AccentColor);
                //	Task.Delay(2000);
                //	this.View.BackgroundColor = Color.FromHex(TemaInfo.BlueAccua);
                //};

            }


        }

		protected override bool OnBackButtonPressed()
		{
			if (Device.OS == TargetPlatform.Android)
				this.Navigation.PopAsync();

			return base.OnBackButtonPressed();
		}

        protected override void OnAppearing()
        {
            base.OnAppearing();
            _PreferenciaPageAtual = this;
        }

        protected override void OnDisappearing()
        {

            base.OnDisappearing();
            _PreferenciaPageAtual = null;
        }
    }
}
