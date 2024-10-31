using System;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Base.Model;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Base.Pages
{
    public class AvaliePage : ContentPage
    {
        private int _Avaliacao = 0;

        private Label _descricaoLabel;
        private IconImage _estrela1Icon;
        private IconImage _estrela2Icon;
        private IconImage _estrela3Icon;
        private IconImage _estrela4Icon;
        private IconImage _estrela5Icon;
        private Editor _comentarioEditor;
        private Button _confirmarButton;

        public string Descricao {
            get {
                return _descricaoLabel.Text;
            }
            set {
                _descricaoLabel.Text = value;
            }
        }
        public event EventHandler<AvaliacaoInfo> AoAvaliar;

        public AvaliePage()
        {
            Title = "Avaliação";
            inicializarComponente();
            Content = new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(10, 20),
                Children = {
                    _descricaoLabel,
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Center,
                        VerticalOptions = LayoutOptions.Start,
                        Margin = new Thickness(0, 10, 0, 10),
                        Children = {
                            _estrela1Icon,
                            _estrela2Icon,
                            _estrela3Icon,
                            _estrela4Icon,
                            _estrela5Icon
                        }
                    },
                    new Label {
                        Style = Estilo.Estilo.Current[Estilo.Estilo.LABEL_CONTROL],
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Text = "Comente como foi sua experiência (opcional):",
                        FontSize = 10
                    },
                    _comentarioEditor,
                    _confirmarButton
                }
            };
        }

        private IconImage criarEstrela(EventHandler aoClicar) {
            var estrelaIcone = new IconImage
            {
                Icon = "fa-star-o",
                IconColor = Color.Gold,
                IconSize = 42
            };
            var tapGestureRecognizer = new TapGestureRecognizer();
            tapGestureRecognizer.Tapped += aoClicar;
            estrelaIcone.GestureRecognizers.Add(tapGestureRecognizer);
            return estrelaIcone;
        }

        private void inicializarComponente()
        {
            _descricaoLabel = new Label{
                Style = Estilo.Estilo.Current[Estilo.Estilo.TITULO3],
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.Center
            };

            _estrela1Icon = criarEstrela((sender, e) => {
                _Avaliacao = 1;
                _estrela1Icon.Icon = "fa-star";
                _estrela2Icon.Icon = "fa-star-o";
                _estrela3Icon.Icon = "fa-star-o";
                _estrela4Icon.Icon = "fa-star-o";
                _estrela5Icon.Icon = "fa-star-o";
            });

            _estrela2Icon = criarEstrela((sender, e) => {
                _Avaliacao = 2;
                _estrela1Icon.Icon = "fa-star";
                _estrela2Icon.Icon = "fa-star";
                _estrela3Icon.Icon = "fa-star-o";
                _estrela4Icon.Icon = "fa-star-o";
                _estrela5Icon.Icon = "fa-star-o";
            });

            _estrela3Icon = criarEstrela((sender, e) => {
                _Avaliacao = 3;
                _estrela1Icon.Icon = "fa-star";
                _estrela2Icon.Icon = "fa-star";
                _estrela3Icon.Icon = "fa-star";
                _estrela4Icon.Icon = "fa-star-o";
                _estrela5Icon.Icon = "fa-star-o";
            });

            _estrela4Icon = criarEstrela((sender, e) => {
                _Avaliacao = 4;
                _estrela1Icon.Icon = "fa-star";
                _estrela2Icon.Icon = "fa-star";
                _estrela3Icon.Icon = "fa-star";
                _estrela4Icon.Icon = "fa-star";
                _estrela5Icon.Icon = "fa-star-o";
            });

            _estrela5Icon = criarEstrela((sender, e) => {
                _Avaliacao = 5;
                _estrela1Icon.Icon = "fa-star";
                _estrela2Icon.Icon = "fa-star";
                _estrela3Icon.Icon = "fa-star";
                _estrela4Icon.Icon = "fa-star";
                _estrela5Icon.Icon = "fa-star";
            });

            _comentarioEditor = new Editor {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HeightRequest = 100
            };

            _confirmarButton = new Button()
            {
                Text = "Confirmar",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Estilo.Current[Estilo.Estilo.BTN_SUCESSO]
            };
            _confirmarButton.Clicked += (sender, e) =>
            {
                if (_Avaliacao > 0)
                {
                    AoAvaliar?.Invoke(this, new AvaliacaoInfo(_Avaliacao, _comentarioEditor.Text));
                    Navigation.PopAsync();
                }
                else {
                    UserDialogs.Instance.AlertAsync("Marque pelomenos uma estrela.", "Aviso", "Entendi");
                }
            };

        }
    }
}

