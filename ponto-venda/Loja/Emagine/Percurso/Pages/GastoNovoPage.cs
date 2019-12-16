using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Threading.Tasks;
using Emagine.Utils;
using Plugin.Media;
using Radar.Controls;
using Radar.IBLL;
using Radar.Model;
using Radar.Utils;
using Xamarin.Forms;
using Radar.Factory;
using System.IO;

namespace Radar.Pages
{
	public class GastoNovoPage : ContentPage
	{
        private double _width;

        private Entry _ValorEntry;
        private DropDownPicker _TipoGastoPicker;
		private Image _FotoImage;
		private Entry _LocalEntry;
        private Label _LatitudeLabel;
        private Label _LongitudeLabel;
        private Entry _ObservacaoEntry;

        private void inicializarComponente() {

            _ValorEntry = new Entry
            {
                Placeholder = "Digite o valor",
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.Center,
                WidthRequest = _width,
                Keyboard = Keyboard.Numeric
            };
            _ValorEntry.SetBinding(Entry.TextProperty, new Binding("Valor"));
            //_ValorEntry.Behaviors.Add(new NumberValidatorBehavior());

            _TipoGastoPicker = new DropDownPicker
            {
                //WidthRequest = Device.OnPlatform(100, 120, 100),
                WidthRequest = _width,
                //HeightRequest = 25,
                DropDownHeight = 150,
                Title = "Tipo",
                SelectedText = "Indefinido",
                SelectedIndex = 0,
                //FontSize = Device.OnPlatform(10, 14, 10),
                CellHeight = 20,
                SelectedBackgroundColor = Color.FromRgb(0, 70, 172),
                SelectedTextColor = Color.White,
                BorderColor = Color.Purple,
                ArrowColor = Color.Blue
            };
            var lista = new List<string>();
            foreach (var tipo in Enum.GetValues(typeof(GastoTipoEnum))) {
                switch ((GastoTipoEnum)tipo) {
                    case GastoTipoEnum.Abastecimento:
                        lista.Add("Abastecimento");
                        break;
                    case GastoTipoEnum.Despesas:
                        lista.Add("Despesas");
                        break;
                    case GastoTipoEnum.Multas:
                        lista.Add("Multas");
                        break;
                    default:
                        lista.Add("Indefinido");
                        break;
                }
            }
            _TipoGastoPicker.Source = lista;
            _TipoGastoPicker.SetBinding(DropDownPicker.SelectedIndexProperty, new Binding("Tipo"));

            _LocalEntry = new Entry
            {
                //Placeholder = "Digite o titulo",
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.Center,
                WidthRequest = _width,
            };
            _LocalEntry.SetBinding(Entry.TextProperty, new Binding("Endereco"));

            _ObservacaoEntry = new Entry
            {
                Placeholder = "Observação",
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.Center,
                WidthRequest = _width,
            };
            _ObservacaoEntry.SetBinding(Entry.TextProperty, new Binding("Observacao"));

            _LatitudeLabel = new Label {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start
            };
            _LatitudeLabel.SetBinding(Label.TextProperty, new Binding("Latitude", stringFormat: "Latitude: {0}"));

            _LongitudeLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start
            };
            _LongitudeLabel.SetBinding(Label.TextProperty, new Binding("Longitude", stringFormat: "Latitude: {0}"));

            _FotoImage = new Image()
            {
                //Source = "ic_add_a_photo_48pt.png",
                Source = "ic_add_a_photo_black_48dp.png",
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                WidthRequest = TelaUtils.LarguraSemPixel * 0.4,
                HeightRequest = TelaUtils.LarguraSemPixel * 0.4
            };
            _FotoImage.GestureRecognizers.Add(
                new TapGestureRecognizer()
                {
                    Command = new Command(() => {
                        tirarFoto();
                    }
                )
            });
        }

        public GastoNovoPage()
        {
            Title = "Novo Custo";
            var gasto = new GastoInfo {
                DataInclusao = DateTime.Now
            };
            var local = GPSUtils.UltimaLocalizacao;
            if (local != null) {
                gasto.Latitude = (float) local.Latitude;
                gasto.Longitude = (float) local.Longitude;
            }
            BindingContext = gasto;

            var gravarButton = new ToolbarItem {
                Text = "Inserir"
            };
            gravarButton.Clicked += (sender, e) => {
                var novoGasto = (GastoInfo)BindingContext;
                var regraGasto = GastoFactory.create();
                regraGasto.gravar(novoGasto);

                MensagemUtils.avisar("Gasto incluído com sucesso!");
                ((MasterDetailPage)Application.Current.MainPage).Detail = new NavigationPage(new VelocimetroPage());
                //NavigationX.create(this).PopAsync();
            };
            ToolbarItems.Add(gravarButton);

            if (TelaUtils.Orientacao == "Landscape") {
                _width = (int)TelaUtils.LarguraSemPixel * 0.5;
            }
            else {
                _width = (int)TelaUtils.LarguraSemPixel * 0.8;
            }

            inicializarComponente();

            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Content = new StackLayout
                {
                    BackgroundColor = Color.Transparent,
                    Orientation = StackOrientation.Vertical,
                    VerticalOptions = LayoutOptions.StartAndExpand,
                    HorizontalOptions = LayoutOptions.CenterAndExpand,
                    Children = {
                        new StackLayout{
                            Orientation = StackOrientation.Horizontal,
                            Children = {
                                new Image {
                                    Source = "ic_monetization_on_black_24dp.png",
                                    VerticalOptions = LayoutOptions.Center,
                                    HorizontalOptions = LayoutOptions.Center,
                                },
                                _ValorEntry
                            }
                        },
                        new StackLayout()
                        {
                            Orientation = StackOrientation.Horizontal,
                            Children = {
                                new Image()
                                {
                                    Source = "ic_event_black_24dp.png",
                                    VerticalOptions = LayoutOptions.Center,
                                    HorizontalOptions = LayoutOptions.Center,
                                },
                                new DatePicker
                                {
                                    IsVisible = true,
                                    IsEnabled = true,
                                    WidthRequest = _width,
                                }
                            }
                        },
                        new StackLayout()
                        {
                            Orientation = StackOrientation.Horizontal,
                            Children = {
                                new Image {
                                    Source = "ic_map_black_24dp.png",
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Center
                                },
                                new StackLayout {
                                    Orientation = StackOrientation.Vertical,
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Children = {
                                        _LocalEntry,
                                        new StackLayout {
                                            Margin = new Thickness(5, 0),
                                            Orientation = StackOrientation.Horizontal,
                                            VerticalOptions = LayoutOptions.Start,
                                            HorizontalOptions = LayoutOptions.Start,
                                            Spacing = 2,
                                            Children = {
                                                _LatitudeLabel,
                                                _LongitudeLabel
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        new StackLayout()
                        {
                            Orientation = StackOrientation.Horizontal,
                            Children = {
                                new Image {
                                    Source = "ic_shopping_cart_black_24dp.png",
                                    VerticalOptions = LayoutOptions.Center,
                                    HorizontalOptions = LayoutOptions.Center,
                                },
                                _TipoGastoPicker
                            }
                        },
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            Children = {
                                new Image{
                                    Source = "ic_edit_black_24dp.png",
                                    VerticalOptions = LayoutOptions.Center,
                                    HorizontalOptions = LayoutOptions.Center,
                                },
                                _ObservacaoEntry
                            }
                        },
                        new StackLayout
                        {
                            Orientation = StackOrientation.Horizontal,
                            Children = {
                                _FotoImage
                            }
                        }
                    }
                }
            };
		}
        
        private void pegaEndereco()
        {
            if (InternetUtils.estarConectado())
            {
                /*
                LocalizacaoInfo localEndereco = GPSUtils.UltimaLocalizacao;
                float latitude = (float)localEndereco.Latitude;
                float longitude = (float)localEndereco.Longitude;
                */
                var gasto = (GastoInfo)BindingContext;
                if (string.IsNullOrEmpty(_LocalEntry.Text)) {
                    GeocoderUtils.pegarAsync(gasto.Latitude, gasto.Longitude, (send, e) => {
                        if (string.IsNullOrEmpty(_LocalEntry.Text))
                            _LocalEntry.Text = e.Endereco.ToString();
                    });
                }
            }
        }

		private async void tirarFoto()
		{
			if (CrossMedia.Current.IsCameraAvailable && CrossMedia.Current.IsTakePhotoSupported)
			{
                var nomeArquivo = $"{DateTime.UtcNow}.jpg";
                var mediaOptions = new Plugin.Media.Abstractions.StoreCameraMediaOptions
				{
					//Directory = "Cupons",
					Name = nomeArquivo
					//SaveToAlbum = true
				};

				// Take a photo of the business receipt.
				var file = await CrossMedia.Current.TakePhotoAsync(mediaOptions);
				if (file == null)
					return;

                GastoInfo gasto = (GastoInfo)this.BindingContext;
                gasto.FotoPath = nomeArquivo;
                var fs = file.GetStream();
                byte[] buffer = new byte[fs.Length];
                fs.Read(buffer, 0, buffer.Length);
                fs.Dispose();
                fs = null;
                gasto.FotoBase64 = Convert.ToBase64String(buffer);


                //DisplayAlert("Salvar em", file.Path, "OK");
                var path = file.Path;
				_FotoImage.Source = ImageSource.FromStream(() =>
				{
					var stream = file.GetStream();
					file.Dispose();
					return stream;
				});

				_FotoImage.Source = path;
				_FotoImage.WidthRequest = TelaUtils.LarguraSemPixel * 0.5;
				_FotoImage.HeightRequest = TelaUtils.LarguraSemPixel * 0.5;

                //var gasto = (GastoInfo)BindingContext;
                //gasto.Foto = Path.GetFileName(path);
			}
			else {
				await DisplayAlert("Dispositivo não possiu camera ou camera desativada", null, "OK");
			}
		}

        protected override void OnAppearing()
        {
            base.OnAppearing();
            pegaEndereco();
        }
    }
}

