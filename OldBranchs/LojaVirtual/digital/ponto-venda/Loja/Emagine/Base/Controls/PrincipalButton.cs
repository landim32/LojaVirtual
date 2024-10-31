using System;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Base.Controls
{
    public class PrincipalButton : ContentView
    {
        private StackLayout _Fundo;
        private IconImage _Icone;
        private Image _IconeImagem;
        private Label _Texto;
        private EventHandler _AoClicar;

        public EventHandler AoClicar { 
            get{
                return _AoClicar;
            } 
            set{
                _AoClicar = value;
                if (_AoClicar != null) {
                    var tapGestureRecognizer = new TapGestureRecognizer();
                    tapGestureRecognizer.Tapped += _AoClicar;
                    if (_Fundo != null)
                        _Fundo.GestureRecognizers.Add(tapGestureRecognizer);
                    if(_Icone != null)
                        _Icone.GestureRecognizers.Add(tapGestureRecognizer);
                    if(_IconeImagem != null)
                        _IconeImagem.GestureRecognizers.Add(tapGestureRecognizer);
                    if (_Texto != null)
                        _Texto.GestureRecognizers.Add(tapGestureRecognizer);
                }
            } 
        }

        private void inicializarComponente(string icone, string texto) {
            _Icone = new IconImage
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 10, 0, 0),
                Icon = icone,
                IconSize = 60,
                IconColor = Color.White
            };
            _Texto = new Label
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.Center,
                Text = texto,
                Margin = new Thickness(0, 0, 0, 10),
                FontSize = 16,
                TextColor = Color.White
            };
        }

        private void inicializarComponente(ImageSource icone)
        {
            _IconeImagem = new Image
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 10, 0, 0),
                Source = icone,
                Aspect = Aspect.AspectFit,
                WidthRequest = 140
            };
        }

        public PrincipalButton(string icone, string texto)
        {
            inicializarComponente(icone, texto);
            _Fundo = new StackLayout {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                BackgroundColor = Estilo.Estilo.Current.PrimaryColor,
                Padding = new Thickness(10,20),
                Children = {
                    _Texto,
                    _Icone
                }
            };
            Content = _Fundo;
        }

        public PrincipalButton(ImageSource icone)
        {
            inicializarComponente(icone);
            Content = _IconeImagem;
        }
    }
}

