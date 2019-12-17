using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Controls
{
    public class QuantidadeControl : ContentView
    {
        private Frame _adicionarButton;
        private Frame _quantidadeLayout;
        private Label _quantidadeLabel;
        private Frame _removerButton;

        public string FontFamily {
            get {
                return (string)GetValue(FontFamilyProperty);
            }
            set {
                SetValue(FontFamilyProperty, value);
                _quantidadeLabel.FontFamily = value;
            }
        }

        public ProdutoInfo Produto {
            get
            {
                return (ProdutoInfo)GetValue(ProdutoProperty);
            }
            set
            {
                SetValue(ProdutoProperty, value);
            }
        }

        public int Quantidade
        {
            get
            {
                return (int)GetValue(QuantidadeProperty);
            }
            set
            {
                SetValue(QuantidadeProperty, value);
                _quantidadeLabel.Text = value.ToString();
            }
        }

        public QuantidadeControl()
        {
            //BackgroundColor = Color.Aqua;
            WidthRequest = 40;
            MinimumWidthRequest = 40;
            inicializarComponente();
            Content = new AbsoluteLayout
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                WidthRequest = 40,
                Children = {
                    _adicionarButton,
                    _removerButton,
                    _quantidadeLayout
                }
            };
        }

        private void inicializarComponente() {
            _adicionarButton = new Frame {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                BackgroundColor = Estilo.Current.BotaoInfo.BackgroundColor,
                CornerRadius = 20,
                Padding = new Thickness(10, 10, 10, 25),
                Content = new IconImage {
                    HorizontalOptions = LayoutOptions.Center,
                    VerticalOptions = LayoutOptions.Center,
                    Icon = "fa-plus",
                    IconColor = Estilo.Current.BotaoInfo.TextColor,
                    IconSize = 20
                }
            };
            AbsoluteLayout.SetLayoutBounds(_adicionarButton, new Rectangle(0, 0, 1, 0.5));
            AbsoluteLayout.SetLayoutFlags(_adicionarButton, AbsoluteLayoutFlags.All);

            _quantidadeLabel = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalTextAlignment = TextAlignment.Center,
                VerticalTextAlignment = TextAlignment.Center,
                Text = "0",
                FontSize = 20,
                FontAttributes = FontAttributes.Bold
            };
            _quantidadeLayout = new Frame
            {
                Padding = 5,
                BackgroundColor = Color.Silver,
                //Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Content = _quantidadeLabel
            };
            AbsoluteLayout.SetLayoutBounds(_quantidadeLayout, new Rectangle(0, 0.5, 1, 0.4));
            AbsoluteLayout.SetLayoutFlags(_quantidadeLayout, AbsoluteLayoutFlags.All);

            _removerButton = new Frame
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.End,
                BackgroundColor = Estilo.Current.BotaoInfo.BackgroundColor,
                CornerRadius = 20,
                Padding = new Thickness(10, 25, 10, 10),
                Content = new IconImage
                {
                    HorizontalOptions = LayoutOptions.Center,
                    VerticalOptions = LayoutOptions.Center,
                    Icon = "fa-minus",
                    IconColor = Estilo.Current.BotaoInfo.TextColor,
                    IconSize = 20
                }
            };
            AbsoluteLayout.SetLayoutBounds(_removerButton, new Rectangle(0, 1, 1, 0.5));
            AbsoluteLayout.SetLayoutFlags(_removerButton, AbsoluteLayoutFlags.All);


            var adicionarClick = new TapGestureRecognizer();
            adicionarClick.Tapped += (sender, e) => {
                if (Produto != null)
                {
                    var regraCarrinho = CarrinhoFactory.create();
                    Quantidade = regraCarrinho.adicionar(Produto);
                    //UserDialogs.Instance.Toast(string.Format("{0} foi adicionado ao seu carrinho.", Produto.Nome));
                }
            };
            _adicionarButton.GestureRecognizers.Add(adicionarClick);

            var removerClick = new TapGestureRecognizer();
            removerClick.Tapped += (sender, e) => {
                if (Produto != null) {
                    var regraCarrinho = CarrinhoFactory.create();
                    Quantidade = regraCarrinho.remover(Produto);
                    //UserDialogs.Instance.Toast(string.Format("{0} foi removido ao seu carrinho.", Produto.Nome));
                }
            };
            _removerButton.GestureRecognizers.Add(removerClick);
        }

        public static readonly BindableProperty FontFamilyProperty = BindableProperty.Create(
            nameof(FontFamily), typeof(string), typeof(QuantidadeControl), default(string),
            propertyChanged: FontFamilyPropertyChanged
        );

        private static void FontFamilyPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (QuantidadeControl)bindable;
            control.FontFamily = (string)newValue;
        }

        public static readonly BindableProperty ProdutoProperty = BindableProperty.Create(
            nameof(Produto), typeof(ProdutoInfo), typeof(QuantidadeControl), default(ProdutoInfo),
            propertyChanged: ProdutoPropertyChanged
        );

        private static void ProdutoPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (QuantidadeControl)bindable;
            control.Produto = (ProdutoInfo)newValue;
        }

        public static readonly BindableProperty QuantidadeProperty = BindableProperty.Create(
            nameof(Quantidade), typeof(int), typeof(QuantidadeControl), default(int),
            propertyChanged: QuantidadePropertyChanged
        );

        private static void QuantidadePropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (QuantidadeControl)bindable;
            control.Quantidade = (int)newValue;
        }
    }
}