using System;
using Emagine.Base.Estilo;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Base.Controls
{
    public abstract class BaseDropDownList : ContentView
    {
        protected Label _textoLabel;
        private IconImage _ImgArrow;

        protected object _value = null;
        protected string _placeholder = "";

        public BaseDropDownList()
        {
            inicializarComponente();

            PlaceholderColor = Color.Silver;
            TextColor = Color.Black;

            var mainStack = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(4, 3),
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            _textoLabel,
                            _ImgArrow
                        }
                    },
                    new BoxView{
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Color = Color.Black,
                        BackgroundColor = Color.Black,
                        HeightRequest = 1
                    }
                }
            };
            Content = mainStack;
            var tapGestureRecognizer = new TapGestureRecognizer();
            tapGestureRecognizer.Tapped += OnClicked;
            mainStack.GestureRecognizers.Add(tapGestureRecognizer);
            _textoLabel.GestureRecognizers.Add(tapGestureRecognizer);
            _ImgArrow.GestureRecognizers.Add(tapGestureRecognizer);
            GestureRecognizers.Add(tapGestureRecognizer);
        }

        public Color TextColor { get; set; }
        public Color PlaceholderColor { get; set; }

        public string Placeholder {
            get {
                return _placeholder;
            }
            set {
                _placeholder = value;
                atualizarPlaceholder();
            }
        }

        protected void atualizarPlaceholder() {
            if (_value == null)
            {
                _textoLabel.Text = _placeholder;
                _textoLabel.TextColor = PlaceholderColor;
            }
            else
            {
                _textoLabel.TextColor = TextColor;
            }
        }

        private void inicializarComponente()
        {
            _textoLabel = new Label
            {
                Text = Placeholder,
                LineBreakMode = LineBreakMode.TailTruncation,
                HorizontalOptions = LayoutOptions.StartAndExpand,
                VerticalOptions = LayoutOptions.Center,
                HorizontalTextAlignment = TextAlignment.Start,
                FontSize = 18,
                TextColor = PlaceholderColor,
            };
            _ImgArrow = new IconImage
            {
                HorizontalOptions = LayoutOptions.End,
                VerticalOptions = LayoutOptions.Center,
                IconColor = Estilo.Estilo.Current.PrimaryColor,
                //Icon = "fa-arrow-circle-down",
                Icon = "fa-search",
                IconSize = 22
            };
        }

        protected abstract void OnClicked(object sender, EventArgs e);
    }
}

