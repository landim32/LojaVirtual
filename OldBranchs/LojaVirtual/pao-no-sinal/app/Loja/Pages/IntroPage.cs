using Emagine;
using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Loja.Pages
{
    public class IntroPage : ContentPage
    {
        private StackLayout _mainLayout;
        private Label _textoLabel;
        private Image _telaImage;
        private Button _avancarButton;

        public string Texto {
            get {
                return _textoLabel.Text;
            }
            set {
                _textoLabel.Text = value;
            }
        }

        public ImageSource Imagem {
            get {
                return _telaImage.Source;
            }
            set {
                _telaImage.Source = value;
            }
        }

        public event EventHandler AoAvancar {
            add {
                _avancarButton.Clicked += value;
            }
            remove {
                _avancarButton.Clicked -= value;
            }
        }

        public bool BotaoExibir {
            get {
                return _mainLayout.Children.Contains(_avancarButton);
            }
            set {
                if (value)
                {
                    if (!_mainLayout.Children.Contains(_avancarButton))
                    {
                        _mainLayout.Children.Add(_avancarButton);
                    }
                }
                else {
                    if (_mainLayout.Children.Contains(_avancarButton))
                    {
                        _mainLayout.Children.Remove(_avancarButton);
                    }
                }
            }
        }

        public IntroPage()
        {
            NavigationPage.SetHasNavigationBar(this, false);
            Title = "Introdução";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            inicializarComponente();

            _mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Spacing = 5,
                Children = {
                    new Image{
                        Source = "logo.png",
                        HeightRequest = 160,
                        Margin = new Thickness(0, 10),
                        HorizontalOptions = LayoutOptions.Center,
                        VerticalOptions = LayoutOptions.Start
                    },
                    _textoLabel,
                    _telaImage,
                    _avancarButton
                }
            };

            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Content = _mainLayout
            };
        }

        private void inicializarComponente() {
            _textoLabel = new Label {
                //MinimumHeightRequest = 200,
                Margin = new Thickness(10, 0),
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                //Style = Estilo.Current[Estilo.TITULO3],
                /*
                Text = 
                    "Parabéns! Você agora poderá usufruir da facilidade de comprar " +
                    "Pão no Sinal! Utilizando a opção de entrega: Pão no Sinal, você " +
                    "conta com a comodidade de receber seus pedidos sem precisar ter o " +
                    "trabalho de estacionar e sair do carro!!",
                */
                FontSize = 15
            };

            _telaImage = new Image {
                HeightRequest = 200,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Margin = new Thickness(20, 0),
                Aspect = Aspect.AspectFit
            };

            _avancarButton = new Button()
            {
                Text = "AVANÇAR",
                Style = Estilo.Current[Estilo.BTN_INFO],
                TextColor = Color.White,
                BackgroundColor = Estilo.Current.PrimaryColor,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.End,
                Margin = new Thickness(10, 0, 10, 15)
            };
        }
    }
}