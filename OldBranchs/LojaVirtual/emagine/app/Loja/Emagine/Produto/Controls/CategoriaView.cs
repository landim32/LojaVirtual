using Emagine.Base.Estilo;
using Emagine.Produto.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Produto.Controls
{
    public class CategoriaView: ContentView
    {
        private Image _iconeIcon;
        private Frame _botaoFrame;
        private Label _nomeLabel;
        private CategoriaInfo _categoria;

        public CategoriaInfo Categoria {
            get {
                return _categoria;
            }
            set {
                _categoria = value;
                atualizarSeguimento();
            }
        }

        public event EventHandler<CategoriaInfo> AoClicar;

        public CategoriaView() {
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                //HorizontalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                Children = {
                    _botaoFrame,
                    _nomeLabel
                }
            };
        }

        private void inicializarComponente() {
            var clique = new TapGestureRecognizer();
            clique.Tapped += (sender, e) => {
                AoClicar?.Invoke(this, _categoria);
            };
            _iconeIcon = new Image
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Center,
                //Icon = "fa-plus",
                //IconColor = Estilo.Current.BotaoInfo.TextColor,
                //IconSize = 30
            };
            _botaoFrame = new Frame
            {
                HorizontalOptions = LayoutOptions.Center,
                VerticalOptions = LayoutOptions.Center,
                BackgroundColor = Estilo.Current.BotaoInfo.BackgroundColor,
                CornerRadius = 45,
                WidthRequest = 50,
                //HeightRequest = 35,
                HeightRequest = 50,
                //Padding = new Thickness(10, 10, 10, 25),
                Content = _iconeIcon
            };
            _botaoFrame.GestureRecognizers.Add(clique);
            _nomeLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                HorizontalTextAlignment = TextAlignment.Center,
                FontSize = 12,
                //Text = seguimento.Nome
            };
            _nomeLabel.GestureRecognizers.Add(clique);
        }

        private void atualizarSeguimento() {
            if (_categoria != null) {
                //_iconeIcon.Icon = _categoria.Icone;
                //_iconeIcon.Icon = "fa-shopping-cart";
                if (!string.IsNullOrEmpty(_categoria.FotoUrl))
                {
                    _iconeIcon.Source = new UriImageSource
                    {
                        Uri = new Uri(_categoria.FotoUrl),
                        CachingEnabled = true,
                        CacheValidity = new TimeSpan(5, 0, 0, 0)
                    };
                }
                _nomeLabel.Text = _categoria.Nome;
            }
            else {
                //_iconeIcon.Icon = "fa-shopping-cart";
                _nomeLabel.Text = "Sem nome";
            }
        }
    }
}
