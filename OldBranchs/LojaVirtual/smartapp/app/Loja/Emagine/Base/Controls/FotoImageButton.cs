using Acr.UserDialogs;
using FormsPlugin.Iconize;
using Plugin.Media;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Base.Controls
{
    public class FotoImageButton : ContentView
    {
        private Image _Imagem;
        private IconImage _CheckImage;

        public FotoImageButton()
        {
            inicializarComponente();

            AbsoluteLayout.SetLayoutFlags(_Imagem, AbsoluteLayoutFlags.All);
            AbsoluteLayout.SetLayoutBounds(_Imagem, new Rectangle(0, 0, 1, 1));

            AbsoluteLayout.SetLayoutFlags(_CheckImage, AbsoluteLayoutFlags.All);
            AbsoluteLayout.SetLayoutBounds(_CheckImage, new Rectangle(0, 0, 1, 1));

            Content = new AbsoluteLayout
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    _Imagem,
                    _CheckImage
                }
            };
        }

        public Stream Imagem { get; set; }

        public ImageSource Source
        {
            get
            {
                if (_Imagem != null)
                {
                    return _Imagem.Source;
                }
                return null;
            }
            set
            {
                if (_Imagem != null)
                {
                    _Imagem.Source = value;
                }
            }
        }

        private void inicializarComponente()
        {

            var tapGestureRecognizer = new TapGestureRecognizer();
            tapGestureRecognizer.Tapped += ImagemButtonTapped;

            if (_Imagem == null)
            {
                _Imagem = new Image()
                {
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Aspect = Aspect.AspectFill
                };
                _Imagem.GestureRecognizers.Add(tapGestureRecognizer);
            }

            if (_CheckImage == null)
            {
                _CheckImage = new IconImage()
                {
                    HorizontalOptions = LayoutOptions.Center,
                    VerticalOptions = LayoutOptions.Center,
                    IconSize = 50,
                    IconColor = Color.Green,
                    Icon = "fa-check",
                    IsVisible = false
                };
                _CheckImage.GestureRecognizers.Add(tapGestureRecognizer);
            }
        }

        protected async void ImagemButtonTapped(object sender, EventArgs e)
        {
            await CrossMedia.Current.Initialize();

            if (!CrossMedia.Current.IsCameraAvailable || !CrossMedia.Current.IsTakePhotoSupported)
            {
                UserDialogs.Instance.Alert("No Camera", ":( No camera available.", "OK");
                return;
            }

            var file = await CrossMedia.Current.TakePhotoAsync(new Plugin.Media.Abstractions.StoreCameraMediaOptions
            {
                Directory = "EmgineFrete",
                Name = "foto_temp.jpg"
            });
            if (file == null)
                return;
            Imagem = file.GetStream();
            file.Dispose();
            _CheckImage.IsVisible = true;

            //await DisplayAlert("File Location", file.Path, "OK");
            /*
            var Imagem = (Image)sender;
            Imagem.Source = ImageSource.FromStream(() =>
            {
                var stream = file.GetStream();
                file.Dispose();
                return stream;
            });
            */
        }

        public string getBase64()
        {
            if (Imagem != null)
            {
                var reader = new StreamReader(this.Imagem);
                byte[] bytedata = System.Text.Encoding.UTF8.GetBytes(reader.ReadToEnd());
                return Convert.ToBase64String(bytedata);
            }
            return null;
        }
    }
}