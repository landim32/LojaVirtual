using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Produto.Controls
{
    public class QuantidadeBaseControl : ContentView
    {
        protected Frame _adicionarButton;
        protected Frame _quantidadeLayout;
        protected Label _quantidadeLabel;
        protected Frame _removerButton;

        public string FontFamily
        {
            get
            {
                return (string)GetValue(FontFamilyProperty);
            }
            set
            {
                SetValue(FontFamilyProperty, value);
                _quantidadeLabel.FontFamily = value;
            }
        }

        public ProdutoInfo Produto
        {
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

        protected virtual void inicializarComponente()
        {
            _adicionarButton = new Frame
            {
                Style = Estilo.Current[EstiloQuantidade.QUANTIDADE_ADICIONAR_BOTAO],
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                CornerRadius = 20,
                Padding = new Thickness(5, 5, 15, 5),
                Content = new IconImage
                {
                    Style = Estilo.Current[EstiloQuantidade.QUANTIDADE_ADICIONAR_ICONE],
                    HorizontalOptions = LayoutOptions.CenterAndExpand,
                    VerticalOptions = LayoutOptions.CenterAndExpand,
                    Icon = "fa-plus"
                }
            };

            _removerButton = new Frame
            {
                Style = Estilo.Current[EstiloQuantidade.QUANTIDADE_REMOVER_BOTAO],
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                BackgroundColor = Estilo.Current.BotaoInfo.BackgroundColor,
                CornerRadius = 20,
                Padding = new Thickness(15, 5, 5, 5),
                Content = new IconImage
                {
                    Style = Estilo.Current[EstiloQuantidade.QUANTIDADE_REMOVER_ICONE],
                    HorizontalOptions = LayoutOptions.Center,
                    VerticalOptions = LayoutOptions.Center,
                    Icon = "fa-minus",
                }
            };

            _quantidadeLabel = new Label
            {
                Style = Estilo.Current[EstiloQuantidade.QUANTIDADE_TEXTO],
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalTextAlignment = TextAlignment.Center,
                VerticalTextAlignment = TextAlignment.Center,
                Text = "0",
            };
            _quantidadeLayout = new Frame
            {
                Style = Estilo.Current[EstiloQuantidade.QUANTIDADE_FUNDO],
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Content = _quantidadeLabel
            };

            var adicionarClick = new TapGestureRecognizer();
            adicionarClick.Tapped += async (sender, e) => {
                if (Produto != null)
                {
                    var regraCarrinho = CarrinhoFactory.create();
                    if (regraCarrinho.temProdutoDeOutraLoja(Produto.IdLoja)) {
                        await UserDialogs.Instance.AlertAsync("Você precisa concluir a compra na outra loja antes de iniciar nessa.", "Aviso", "Entendi");
                    }
                    else
                    {
                        Quantidade = regraCarrinho.adicionar(Produto);
                    }
                    //UserDialogs.Instance.Toast(string.Format("{0} foi adicionado ao seu carrinho.", Produto.Nome));
                }
            };
            _adicionarButton.GestureRecognizers.Add(adicionarClick);

            var removerClick = new TapGestureRecognizer();
            removerClick.Tapped += (sender, e) => {
                if (Produto != null)
                {
                    var regraCarrinho = CarrinhoFactory.create();
                    Quantidade = regraCarrinho.remover(Produto.Id);
                    //UserDialogs.Instance.Toast(string.Format("{0} foi removido ao seu carrinho.", Produto.Nome));
                }
            };
            _removerButton.GestureRecognizers.Add(removerClick);
        }

        public static readonly BindableProperty FontFamilyProperty = BindableProperty.Create(
            nameof(FontFamily), typeof(string), typeof(QuantidadeBaseControl), default(string),
            propertyChanged: FontFamilyPropertyChanged
        );

        private static void FontFamilyPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (QuantidadeBaseControl)bindable;
            control.FontFamily = (string)newValue;
        }

        public static readonly BindableProperty ProdutoProperty = BindableProperty.Create(
            nameof(Produto), typeof(ProdutoInfo), typeof(QuantidadeBaseControl), default(ProdutoInfo),
            propertyChanged: ProdutoPropertyChanged
        );

        private static void ProdutoPropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (QuantidadeBaseControl)bindable;
            control.Produto = (ProdutoInfo)newValue;
        }

        public static readonly BindableProperty QuantidadeProperty = BindableProperty.Create(
            nameof(Quantidade), typeof(int), typeof(QuantidadeBaseControl), default(int),
            propertyChanged: QuantidadePropertyChanged
        );

        private static void QuantidadePropertyChanged(BindableObject bindable, object oldValue, object newValue)
        {
            var control = (QuantidadeBaseControl)bindable;
            control.Quantidade = (int)newValue;
        }
    }
}
