using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Emagine.Base.Estilo;
using Xamarin.Forms;

namespace Emagine.Base.Pages
{
	public class DocumentoPage : ContentPage
	{
        private StackLayout _mainLayout;
        private WebView _conteudoWebView;
        private Button _simButton;
        private Button _naoButton;
        private string _nomeArquivo;

        public event EventHandler AoConfirmar {
            add {
                _simButton.Clicked += value;
            }
            remove {
                _simButton.Clicked -= value;
            }
        }

        public event EventHandler AoNegar {
            add {
                _naoButton.Clicked += value;
            }
            remove {
                _naoButton.Clicked -= value;
            }
        }

        public string NomeArquivo {
            get {
                return _nomeArquivo;
            }
            set {
                _nomeArquivo = value;
                if (!string.IsNullOrEmpty(_nomeArquivo))
                {
                    _conteudoWebView.Source = new UrlWebViewSource
                    {
                        Url = "file:///android_asset/html/" + _nomeArquivo
                    };
                }
                else {
                    _conteudoWebView.Source = null;
                }
            }
        }

        public bool SimVisivel {
            get {
                return _mainLayout.Children.Contains(_simButton);
            }
            set {
                if (value) {
                    if (!_mainLayout.Children.Contains(_simButton)) {
                        _mainLayout.Children.Add(_simButton);
                    }
                }
                else {
                    _mainLayout.Children.Remove(_simButton);
                }
            }
        }

        public bool NaoVisivel {
            get {
                return _mainLayout.Children.Contains(_naoButton);
            }
            set {
                if (value) {
                    if (!_mainLayout.Children.Contains(_naoButton)) {
                        _mainLayout.Children.Add(_naoButton);
                    }
                }
                else {
                    _mainLayout.Children.Remove(_naoButton);
                }
            }
        }

        public DocumentoPage()
		{
            //Title = "Termos e Condições";
            inicializarComponente();
            _mainLayout = new StackLayout {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Padding = new Thickness(3, 5),
                Spacing = 5,
                Children = {
                    _conteudoWebView,
                    _simButton,
                    _naoButton
                }
            };
            Content = _mainLayout;
        }

        private void inicializarComponente() {
            _conteudoWebView = new WebView
            {
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill,
                Source = new HtmlWebViewSource {
                    Html = "<html><body>...</body></html>"
                }
            };
            _simButton = new Button()
            {
                Text = "CONCORDO",
                Style = Estilo.Estilo.Current[Estilo.Estilo.BTN_SUCESSO],
                VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Fill,
                //Margin = new Thickness(10, 0)
            };
            _naoButton = new Button()
            {
                Text = "NÃO CONCORDO",
                Style = Estilo.Estilo.Current[Estilo.Estilo.BTN_DANGER],
                VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Fill,
                //Margin = new Thickness(10, 0)
            };
        }
	}
}