using Rg.Plugins.Popup.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Popup
{
    public class ProgressBarPopUp: PopupPage
    {
        Label _tituloLabel;
        Label _descricaoLabel;
        ProgressBar _barraProgresso;
        Button _cancelarButton;

        private int _progresso = 0;

        public ProgressBarPopUp() {

            Content = new AbsoluteLayout {
                Padding = 20,
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.Fill,
                MinimumHeightRequest = 140,
                Children = {
                    inicializarComponente()
                }
            };
        }

        public int Progresso {
            get {
                return _progresso;
            }
            set {
                if (_progresso != value) {
                    _progresso = value;
                    double valor = ((double)_progresso / 100);
                    //_barraProgresso.Progress = valor;
                    _barraProgresso.ProgressTo(valor, 200, Easing.Linear);
                }
            }
        }

        public string Titulo {
            get {
                if (_tituloLabel != null)
                    return _tituloLabel.Text;
                return string.Empty;
            }
            set {
                if (_tituloLabel != null)
                    _tituloLabel.Text = value;
            }
        }

        public string Descricao
        {
            get
            {
                if (_descricaoLabel != null)
                    return _descricaoLabel.Text;
                return string.Empty;
            }
            set
            {
                if (_descricaoLabel != null)
                    _descricaoLabel.Text = value;
            }
        }

        private StackLayout inicializarComponente() {
            _tituloLabel = new Label {
                Text = "Baixando arquivos...",
            };
            _descricaoLabel = new Label
            {
                HorizontalTextAlignment = TextAlignment.End,
                Text = "0/0"
            };
            _barraProgresso = new ProgressBar {
                Progress = 0,
                HorizontalOptions = LayoutOptions.FillAndExpand
            };

            _cancelarButton = new Button {
                Text = "Cancelar",
                WidthRequest = 80
            };
            _cancelarButton.Clicked += (sender, e) =>
            {
                Navigation.PopModalAsync();
            };
            var div = new StackLayout
            {
                BackgroundColor = Color.White,
                Padding = new Thickness(5, 10),
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    _tituloLabel,
                    new StackLayout {
                        Orientation = StackOrientation.Vertical,
                        Padding = new Thickness(0, 15),
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.Center,
                        Children = {
                            _barraProgresso
                        }
                    },
                    _descricaoLabel,
                    _cancelarButton
                }
            };
            AbsoluteLayout.SetLayoutBounds(div, new Rectangle(0, 0, 1, 1));
            AbsoluteLayout.SetLayoutFlags(div, AbsoluteLayoutFlags.All);
            return div;
        }

    }
}
