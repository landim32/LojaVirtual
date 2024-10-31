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
    public class IntoPageOld : ContentPage
    {
        private Button _avancarButton;

        public event EventHandler AoAvancar {
            add {
                _avancarButton.Clicked += value;
            }
            remove {
                _avancarButton.Clicked -= value;
            }
        }

        public IntoPageOld()
        {
            NavigationPage.SetHasNavigationBar(this, false);
            Title = "Introdução";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            inicializarComponente();

            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Padding = 5,
                Children = {
                    new ScrollView {
                        Orientation = ScrollOrientation.Vertical,
                        VerticalOptions = LayoutOptions.Fill,
                        HorizontalOptions = LayoutOptions.Fill,
                        Content = new StackLayout {
                            Orientation = StackOrientation.Vertical,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.Fill,
                            Children = {
                                new Image{
                                    Source = "logo.png",
                                    HeightRequest = 160,
                                    Margin = new Thickness(0, 10),
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Center
                                },
                                new StackLayout{
                                    Orientation = StackOrientation.Horizontal,
                                    VerticalOptions = LayoutOptions.Fill,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Margin = new Thickness(10, 0),
                                    Spacing = 10,
                                    Children = {
                                        new IconImage() {
                                            Icon = "fa-check",
                                            IconColor = Color.Black,
                                            IconSize = 25,
                                            WidthRequest = 30,
                                            VerticalOptions = LayoutOptions.Start,
                                            HorizontalOptions = LayoutOptions.Start
                                        },
                                        new Label {
                                            HorizontalOptions = LayoutOptions.Fill,
                                            //Style = Estilo.Current[Estilo.TITULO3],
                                            Text = "Parabéns! Você agora poderá usufruir da facilidade de comprar " +
                                                "Pão no Sinal! Utilizando a opção de entrega: Pão no Sinal, você " +
                                                "conta com a comodidade de receber seus pedidos sem precisar ter o " +
                                                "trabalho de estacionar e sair do carro!!",
                                            FontSize = 15
                                        }
                                    }
                                },
                                new StackLayout{
                                    Orientation = StackOrientation.Horizontal,
                                    VerticalOptions = LayoutOptions.Fill,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Margin = new Thickness(10, 0),
                                    Spacing = 10,
                                    Children = {
                                        new IconImage() {
                                            Icon = "fa-check",
                                            IconColor = Color.Black,
                                            IconSize = 25,
                                            WidthRequest = 30,
                                            VerticalOptions = LayoutOptions.Start,
                                            HorizontalOptions = LayoutOptions.Start
                                        },
                                        new Label {
                                            HorizontalOptions = LayoutOptions.Fill,
                                            //Style = Estilo.Current[Estilo.TITULO3],
                                            Text = "Para isto, basta escolher a opção de entrega: Pão no Sinal (caso " +
                                                "ela esteja ativa naquele momento). E informar se o pagamento será em " +
                                                "cartão, vale-refeição ou dinheiro",
                                            FontSize = 15
                                        }
                                    }
                                },
                                new StackLayout{
                                    Orientation = StackOrientation.Horizontal,
                                    VerticalOptions = LayoutOptions.Fill,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Margin = new Thickness(10, 0),
                                    Spacing = 10,
                                    Children = {
                                        new IconImage() {
                                            Icon = "fa-check",
                                            IconColor = Color.Black,
                                            IconSize = 25,
                                            WidthRequest = 30,
                                            VerticalOptions = LayoutOptions.Start,
                                            HorizontalOptions = LayoutOptions.Start
                                        },
                                        new Label {
                                            HorizontalOptions = LayoutOptions.Fill,
                                            Text = "Você precisará informar também o modelo, a placa e a cor do seu " +
                                                "carro, para que nossos entregadores o localizem no trânsito.",
                                            FontSize = 15
                                        }
                                    }
                                },
                                new StackLayout{
                                    Orientation = StackOrientation.Horizontal,
                                    VerticalOptions = LayoutOptions.Fill,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Margin = new Thickness(10, 0),
                                    Spacing = 10,
                                    Children = {
                                        new IconImage() {
                                            Icon = "fa-check",
                                            IconColor = Color.Black,
                                            IconSize = 25,
                                            WidthRequest = 30,
                                            VerticalOptions = LayoutOptions.Start,
                                            HorizontalOptions = LayoutOptions.Start
                                        },
                                        new Label {
                                            HorizontalOptions = LayoutOptions.Fill,
                                            Text = "Ao confirmar o pedido, você precisa habilitar o envio de sua " +
                                                "localização em seu celular Então, é só aguardar e receber seu pedido " +
                                                "no conforto do seu carro.",
                                            FontSize = 15
                                        }
                                    }
                                },
                            }
                        }
                    },
                    _avancarButton
                }
            };
        }

        private void inicializarComponente() {
            _avancarButton = new Button()
            {
                Text = "AVANÇAR",
                Style = Estilo.Current[Estilo.BTN_INFO],
                TextColor = Color.White,
                BackgroundColor = Estilo.Current.PrimaryColor,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(10, 0)
            };
        }
    }
}