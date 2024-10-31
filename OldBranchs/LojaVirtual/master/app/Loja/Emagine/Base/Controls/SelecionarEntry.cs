using System;
using Emagine.Base.Estilo;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Base.Controls
{
    [Obsolete("Use DropDownList")]
    public class SelecionarEntry : ContentView
    {
        private Label _Text;
        private StackLayout _Container;
        private object _Info = null;
        public object Info { 
            get {
                return _Info;
            } 
            set {
                if (_Text != null)
                    _Text.Text = value.ToString();
                _Info = value;
            }
        }
        private IconImage _ImgArrow;
        public EventHandler<object> Clicked;

        public SelecionarEntry()
        {
            inicializarComponentes();
            Content = _Container;
        }

        public void setHintText(string value){
            if(Info == null){
                _Text.Text = value;
            }
        }

        private void inicializarComponentes()
        {
            _Text = new Label
            {
                Text = "Toque para selecionar",
                LineBreakMode = LineBreakMode.TailTruncation,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.Center,
                HorizontalTextAlignment = TextAlignment.Start,
                FontSize = 20,
                TextColor = Color.Black
            };
            _ImgArrow = new IconImage
            {
                HorizontalOptions = LayoutOptions.End,
                VerticalOptions = LayoutOptions.Center,
                IconColor = Estilo.Estilo.Current.PrimaryColor,
                Icon = "fa-arrow-circle-down",
                IconSize = 30,
                WidthRequest = 40
            };
            _Container = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _Text,
                    _ImgArrow
                }
            };



            var tapGestureRecognizer = new TapGestureRecognizer();
            tapGestureRecognizer.Tapped += (sender, e) => {
                if(Clicked != null){
                    this.Clicked(this, Info);
                }
            };
            _Text.GestureRecognizers.Add(tapGestureRecognizer);
            _ImgArrow.GestureRecognizers.Add(tapGestureRecognizer);
            _Container.GestureRecognizers.Add(tapGestureRecognizer);
        }
    }
}

