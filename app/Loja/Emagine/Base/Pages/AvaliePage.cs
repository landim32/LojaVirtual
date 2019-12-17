using System;
using Emagine.Base.Estilo;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Base.Pages
{
    public class AvaliePage : ContentPage
    {
        private int _Avaliacao = 0;

        private Label _descricaoLabel;
        private IconImage _Estrela1Icon;
        private IconImage _Estrela2Icon;
        private IconImage _Estrela3Icon;
        private IconImage _Estrela4Icon;
        private IconImage _Estrela5Icon;
        private Button _ConfirmarButton;

        public string Descricao {
            get {
                return _descricaoLabel.Text;
            }
            set {
                _descricaoLabel.Text = value;
            }
        }
        public event EventHandler<int> AoAvaliar;

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
                        Margin = new Thickness(0, 10, 0, 0),
                        Children = {
                            _Estrela1Icon,
                            _Estrela2Icon,
                            _Estrela3Icon,
                            _Estrela4Icon,
                            _Estrela5Icon
                        }
                    },
                    _ConfirmarButton
                }
            };
        }

        private IconImage criarEstrela(EventHandler aoClicar) {
            var estrelaIcone = new IconImage
            {
                Icon = "fa-star-o",
                IconColor = Color.Gold,
                IconSize = 28
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

            _Estrela1Icon = criarEstrela((sender, e) => {
                _Avaliacao = 1;
                _Estrela1Icon.Icon = "fa-star";
                _Estrela2Icon.Icon = "fa-star-o";
                _Estrela3Icon.Icon = "fa-star-o";
                _Estrela4Icon.Icon = "fa-star-o";
                _Estrela5Icon.Icon = "fa-star-o";
            });

            _Estrela2Icon = criarEstrela((sender, e) => {
                _Avaliacao = 2;
                _Estrela1Icon.Icon = "fa-star";
                _Estrela2Icon.Icon = "fa-star";
                _Estrela3Icon.Icon = "fa-star-o";
                _Estrela4Icon.Icon = "fa-star-o";
                _Estrela5Icon.Icon = "fa-star-o";
            });

            _Estrela3Icon = criarEstrela((sender, e) => {
                _Avaliacao = 3;
                _Estrela1Icon.Icon = "fa-star";
                _Estrela2Icon.Icon = "fa-star";
                _Estrela3Icon.Icon = "fa-star";
                _Estrela4Icon.Icon = "fa-star-o";
                _Estrela5Icon.Icon = "fa-star-o";
            });

            _Estrela4Icon = criarEstrela((sender, e) => {
                _Avaliacao = 4;
                _Estrela1Icon.Icon = "fa-star";
                _Estrela2Icon.Icon = "fa-star";
                _Estrela3Icon.Icon = "fa-star";
                _Estrela4Icon.Icon = "fa-star";
                _Estrela5Icon.Icon = "fa-star-o";
            });

            _Estrela5Icon = criarEstrela((sender, e) => {
                _Avaliacao = 5;
                _Estrela1Icon.Icon = "fa-star";
                _Estrela2Icon.Icon = "fa-star";
                _Estrela3Icon.Icon = "fa-star";
                _Estrela4Icon.Icon = "fa-star";
                _Estrela5Icon.Icon = "fa-star";
            });

            _ConfirmarButton = new Button()
            {
                Text = "Confirmar",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Estilo.Current[Estilo.Estilo.BTN_SUCESSO]
            };
            _ConfirmarButton.Clicked += (sender, e) =>
            {
                AoAvaliar?.Invoke(this, _Avaliacao);
                Navigation.PopAsync();
            };

        }
    }
}

