using System;
using System.Collections.Generic;
using Xamarin.Forms;
using Radar.Utils;
using Radar.Controls;


namespace Radar.Pages
{
	public class NovoCadastroPage : ContentPage
    {
        Image _cupomFiscal;
		DropDownPicker _Drop1;
		Entry _local;
		Image _fotoPessoal;
		double _width;

		public NovoCadastroPage()
        {
			Title = "Novo Cadastro";

			if (TelaUtils.Orientacao == "Landscape")
			{
				_width = (int)TelaUtils.LarguraSemPixel * 0.5;
			}
			else {
				_width = (int)TelaUtils.LarguraSemPixel * 0.8;
			}
			ScrollView scrollMain = new ScrollView();
			scrollMain.Orientation = ScrollOrientation.Vertical;
			scrollMain.VerticalOptions = LayoutOptions.FillAndExpand;

			StackLayout main = new StackLayout();
			main.BackgroundColor = Color.Transparent;
			main.Orientation = StackOrientation.Vertical;
			main.VerticalOptions = LayoutOptions.StartAndExpand;
			main.HorizontalOptions = LayoutOptions.CenterAndExpand;


			StackLayout emailStack = new StackLayout()
			{
				Orientation = StackOrientation.Horizontal
			};

			Image emailIcone = new Image()
			{
				Source = "ic_mail_black_24dp.png",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.Center,
			};

			var email = new Entry
			{
				Placeholder = "Email:",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.CenterAndExpand,
				WidthRequest = _width
			};
			EmailValidatorBehavior SecSenhaValidator = new EmailValidatorBehavior();
			email.Behaviors.Add(SecSenhaValidator);
			emailStack.Children.Add(emailIcone);
			emailStack.Children.Add(email);

			StackLayout nomeStack = new StackLayout()
			{
				Orientation = StackOrientation.Horizontal
			};

			Image nomeIcone = new Image()
			{
				Source = "ic_edit_black_24dp.png",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.Center,
			};

			var nome = new Entry
			{
				Placeholder = "Nome:",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.Center,
				WidthRequest = _width
			};
			nomeStack.Children.Add(nomeIcone);
			nomeStack.Children.Add(nome);

			StackLayout sobrenomeStack = new StackLayout()
			{
				Orientation = StackOrientation.Horizontal
			};

			Image sobrenomeIcone = new Image()
			{
				Source = "ic_edit_black_24dp.png",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.Center,
			};

			var sobreNome = new Entry
			{
				Placeholder = "Sobrenome:",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.Center,
				WidthRequest = _width
			};
			sobrenomeStack.Children.Add(sobrenomeIcone);
			sobrenomeStack.Children.Add(sobreNome);

			StackLayout tipoSexoStack = new StackLayout()
			{
				Orientation = StackOrientation.Horizontal
			};

			Image tipoSexoIcone = new Image()
			{
				Source = "ic_wc_black_24dp.png",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.Center,
			};

			_Drop1 = new DropDownPicker
			{
				//WidthRequest = Device.OnPlatform(100, 120, 100),
				WidthRequest = _width,
				//HeightRequest = 25,
				DropDownHeight = 150,
				Title = "Sexo",
				SelectedText = "",
				//FontSize = Device.OnPlatform(10, 14, 10),
				CellHeight = 20,
				SelectedBackgroundColor = Color.FromRgb(0, 70, 172),
				SelectedTextColor = Color.White,
				BorderColor = Color.Purple,
				ArrowColor = Color.Blue
			};
			Items();
			tipoSexoStack.Children.Add(tipoSexoIcone);
			tipoSexoStack.Children.Add(_Drop1);

			StackLayout dataStack = new StackLayout()
			{
				Orientation = StackOrientation.Horizontal,
				Spacing = 2
			};

			Label dataNascimentoLabel = new Label();
			dataNascimentoLabel.Text = "Data Nascimento:";

			Image dataIcone = new Image()
			{
				Source = "ic_event_black_24dp.png",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.Center,
			};

			var dataNascimento = new DatePicker
			{
				IsVisible = true,
				IsEnabled = true,
			};


			dataStack.Children.Add(dataIcone);
			dataStack.Children.Add(dataNascimentoLabel);
			dataStack.Children.Add(dataNascimento);


			StackLayout senhaStack = new StackLayout()
			{
				Orientation = StackOrientation.Horizontal
			};

			Image senhaIcone = new Image()
			{
				Source = "ic_vpn_key_black_24dp.png",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.Center,
			};

			var senha = new Entry
			{
				Placeholder = "Senha:",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.Center,
				WidthRequest = _width,
			};
			senhaStack.Children.Add(senhaIcone);
			senhaStack.Children.Add(senha);

			StackLayout confirmarsenhaStack = new StackLayout()
			{
				Orientation = StackOrientation.Horizontal
			};

			Image confirmarsenhaIcone = new Image()
			{
				Source = "ic_vpn_key_black_24dp.png",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.Center,
			};

			var confirmarsenha = new Entry
			{
				Placeholder = "Confirmar Senha:",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.Center,
				WidthRequest = _width,
			};
			confirmarsenhaStack.Children.Add(confirmarsenhaIcone);
			confirmarsenhaStack.Children.Add(confirmarsenha);

			StackLayout fotoStack = new StackLayout()
			{
				Orientation = StackOrientation.Horizontal
			};

			_fotoPessoal = new Image()
			{
				Source = "ic_add_a_photo_48pt.png",
				VerticalOptions = LayoutOptions.Center,
				HorizontalOptions = LayoutOptions.CenterAndExpand,
				WidthRequest = TelaUtils.LarguraSemPixel * 0.4,
				HeightRequest = TelaUtils.LarguraSemPixel * 0.4
			};

			_fotoPessoal.GestureRecognizers.Add(
					new TapGestureRecognizer()
					{
						Command = new Command(() =>
						{
							tirarFoto();
						}
					)
					});

			fotoStack.Children.Add(_fotoPessoal);


			main.Children.Add(emailStack);
			main.Children.Add(nomeStack);
			main.Children.Add(sobrenomeStack);
			main.Children.Add(tipoSexoStack);
			main.Children.Add(dataStack);
			main.Children.Add(senhaStack);
			main.Children.Add(confirmarsenhaStack);
			main.Children.Add(fotoStack);

			scrollMain.Content = main;
			Content = scrollMain;
		}

          		private async void tirarFoto()
		{

			if (CrossMedia.Current.IsCameraAvailable && CrossMedia.Current.IsTakePhotoSupported)
			{

				var mediaOptions = new Plugin.Media.Abstractions.StoreCameraMediaOptions
				{

					Directory = "Cupons",
					Name = $"{DateTime.UtcNow}.jpg",
					//SaveToAlbum = true

				};

				// Take a photo of the business receipt.
				var file = await CrossMedia.Current.TakePhotoAsync(mediaOptions);


				if (file == null)
					return;

				//DisplayAlert("Salvar em", file.Path, "OK");
				var path = file.Path;
				_fotoPessoal.Source = ImageSource.FromStream(() =>
				{
					var stream = file.GetStream();
					file.Dispose();
					return stream;
				});

				_fotoPessoal.Source = path;
				_fotoPessoal.WidthRequest = TelaUtils.LarguraSemPixel * 0.5;
				_fotoPessoal.HeightRequest = TelaUtils.LarguraSemPixel * 0.5;


			}
			else {
				DisplayAlert("Dispositivo não possiu camera ou camera desativada", null, "OK");
			}
		}

        protected override void OnAppearing()
		{
			base.OnAppearing();

		}

		protected override void OnDisappearing()
		{
			base.OnDisappearing();

		}

		private void Items()
		{
			var d = new List<string>();
			d.Add("Masculino");
			d.Add("Feminino");

			this._Drop1.Source = d;
		}
		      
    }
}
