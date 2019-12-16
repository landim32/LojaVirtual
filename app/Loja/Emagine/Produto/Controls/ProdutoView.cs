using Emagine.Base.Estilo;
using Emagine.Produto.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Produto.Controls
{
    public class ProdutoView: ContentView
    {
        protected Image _fotoImage;
        protected Label _nomeLabel;
        protected Label _moedaValorLabel;
        protected Label _valorLabel;
        protected Label _volumeLabel;
        protected AbsoluteLayout _promocaoStack;
        protected Label _moedaPromocaoLabel;
        protected Label _valorPromocaoLabel;
        protected Label _estoqueLabel;
        protected QuantidadeHControl _quantidadeButton;


        public ProdutoInfo Produto {
            get {
                return (ProdutoInfo)BindingContext;
            }
            set {
                BindingContext = value;
                //atualizarProduto();
            }
        }

        public event EventHandler<ProdutoInfo> AoClicar;

        public ProdutoView() {
            inicializarComponente();
            Content = new Frame
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[EstiloProduto.PRODUTO_FRAME],
                Content = new StackLayout {
                    Orientation = StackOrientation.Vertical,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.FillAndExpand,
                    Spacing = 0,
                    Children = {
                        _fotoImage,
                        _nomeLabel,
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.CenterAndExpand,
                            Margin = new Thickness(0, 0, 0, 3),
                            Spacing = 1,
                            Children = {
                                _promocaoStack,
                                _moedaValorLabel,
                                _valorLabel
                            }
                        },
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.CenterAndExpand,
                            Margin = new Thickness(0, 0, 0, 3),
                            Spacing = 1,
                            Children = {
                                new Label {
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Style = Estilo.Current[EstiloProduto.PRODUTO_LABEL],
                                    Text = "Estoque: "
                                },
                                _estoqueLabel
                            }
                        },
                        _quantidadeButton
                    }
                }
            };
            /*
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Children = {
                    _botaoFrame,
                    _nomeLabel
                }
            };
            */
        }

        private void inicializarComponente() {
            var clique = new TapGestureRecognizer();
            clique.Tapped += (sender, e) => {
                AoClicar?.Invoke(this, Produto);
            };

            _fotoImage = new Image
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Style = Estilo.Current[EstiloProduto.PRODUTO_FOTO]
            };
            _fotoImage.SetBinding(Image.SourceProperty, new Binding("FotoUrl"));
            _fotoImage.GestureRecognizers.Add(clique);

            _nomeLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                HeightRequest = 70,
                //TextColor = Color.Black,
                HorizontalTextAlignment = TextAlignment.Center,
                VerticalTextAlignment = TextAlignment.Start,
                Style = Estilo.Current[EstiloProduto.PRODUTO_TITULO]
            };
            _nomeLabel.SetBinding(Label.TextProperty, new Binding("Nome"));
            _nomeLabel.GestureRecognizers.Add(clique);

            _moedaValorLabel = new Label
            {
                Text = "R$",
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[EstiloProduto.PRODUTO_PRECO_MOEDA]
            };
            _moedaValorLabel.SetBinding(Label.TextColorProperty, new Binding("PromocaoCor"));
            _moedaValorLabel.GestureRecognizers.Add(clique);

            _valorLabel = new Label
            {
                VerticalOptions = LayoutOptions.StartAndExpand,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[EstiloProduto.PRODUTO_PRECO_VALOR]
            };
            //_valorLabel.SetBinding(Label.TextProperty, new Binding("ValorPromocao", stringFormat: "{0:N2}"));
            _valorLabel.SetBinding(Label.TextProperty, new Binding("ValorFinal", stringFormat: "{0:N2} "));
            _valorLabel.SetBinding(Label.TextColorProperty, new Binding("PromocaoCor"));
            _valorLabel.GestureRecognizers.Add(clique);

            _volumeLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[EstiloProduto.PRODUTO_VOLUME]
            };
            _volumeLabel.SetBinding(Label.TextProperty, new Binding("VolumeStr"));
            _volumeLabel.GestureRecognizers.Add(clique);


            _moedaPromocaoLabel = new Label
            {
                Text = "R$",
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[EstiloProduto.PRODUTO_PROMOCAO_MOEDA]
            };
            _moedaPromocaoLabel.GestureRecognizers.Add(clique);

            _valorPromocaoLabel = new Label
            {
                VerticalOptions = LayoutOptions.StartAndExpand,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[EstiloProduto.PRODUTO_PROMOCAO_VALOR]
            };
            //_valorPromocaoLabel.SetBinding(Label.TextProperty, new Binding("Valor", stringFormat: "{0:N2}"));
            _valorPromocaoLabel.SetBinding(Label.TextProperty, new Binding("Valor", stringFormat: "{0:N2} "));
            _valorPromocaoLabel.GestureRecognizers.Add(clique);

            _estoqueLabel = new Label
            {
                VerticalOptions = LayoutOptions.StartAndExpand,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[EstiloProduto.PRODUTO_QUANTIDADE]
            };
            _estoqueLabel.SetBinding(Label.TextProperty, new Binding("Quantidade"));
            _estoqueLabel.GestureRecognizers.Add(clique);

            var valorLayout = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                Margin = new Thickness(0, 0, 0, 3),
                Spacing = 1,
                Children = {
                    _moedaPromocaoLabel,
                    _valorPromocaoLabel
                }
            };
            AbsoluteLayout.SetLayoutBounds(valorLayout, new Rectangle(0, 0, 1, 1));
            AbsoluteLayout.SetLayoutFlags(valorLayout, AbsoluteLayoutFlags.All);
            valorLayout.GestureRecognizers.Add(clique);

            var linha = new BoxView
            {
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                BackgroundColor = Estilo.Current.DangerColor,
                HeightRequest = 1,
            };
            AbsoluteLayout.SetLayoutBounds(linha, new Rectangle(0, 0, 1, 1));
            AbsoluteLayout.SetLayoutFlags(linha, AbsoluteLayoutFlags.All);
            linha.GestureRecognizers.Add(clique);

            _promocaoStack = new AbsoluteLayout
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Children = {
                    valorLayout,
                    linha
                }
            };
            _promocaoStack.SetBinding(AbsoluteLayout.IsVisibleProperty, new Binding("EmPromocao"));
            _promocaoStack.GestureRecognizers.Add(clique);

            _quantidadeButton = new QuantidadeHControl
            {
                Margin = new Thickness(0, 5, 5, 0),
                //VerticalOptions = LayoutOptions.CenterAndExpand,
                //HorizontalOptions = LayoutOptions.End,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                FontFamily = Estilo.Current.FontDefaultBold,
                //WidthRequest = 40,
                //HeightRequest = 120
                HeightRequest = 40
            };
            _quantidadeButton.SetBinding(QuantidadeHControl.QuantidadeProperty, new Binding("QuantidadeCarrinho"));
            _quantidadeButton.SetBinding(QuantidadeHControl.ProdutoProperty, new Binding("."));
        }

        /*
        private void atualizarProduto() {
            if (_produto != null) {
                _nomeLabel.Text = _produto.Nome;
            }
            else {
                _nomeLabel.Text = "Sem nome";
            }
        }
        */
    }
}
