using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Endereco.Model;
using Emagine.Endereco.Pages;
using Emagine.Produto.Controls;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Endereco.Cells
{
    public class PedidoCell: ViewCell
    {
        private Grid _gridLayout;
        private Label _CodigoLabel;
        private Label _FormaPagamentoLabel;
        private Label _MetodoEntregaLabel;
        private Label _ValorTotalLabel;
        private Label _SituacaoLabel;

        public PedidoCell() {
            inicializarComponente();

            _gridLayout = new Grid
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
            };

            _gridLayout.Children.Add(new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Orientation = StackOrientation.Vertical,
                Spacing = 0,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                        Text = "Método de Entrega:"
                    },
                    _MetodoEntregaLabel
                }
            }, 0, 0);

            _gridLayout.Children.Add(new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Orientation = StackOrientation.Vertical,
                Spacing = 0,
                Children = {
                    new StackLayout{
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.Start,
                        Orientation = StackOrientation.Horizontal,
                        Spacing = 0,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.FillAndExpand,
                                VerticalOptions = LayoutOptions.Start,
                                Style = Estilo.Current[Estilo.LABEL_CONTROL],
                                Text = "Valor Total:"
                            },
                            _CodigoLabel
                        }
                    },
                    _ValorTotalLabel
                }
            }, 1, 0);

            _gridLayout.Children.Add(new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Orientation = StackOrientation.Vertical,
                Spacing = 0,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                        Text = "Forma de Pagamento:"
                    },
                    _FormaPagamentoLabel
                }
            }, 0, 1);

            _gridLayout.Children.Add(new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Orientation = StackOrientation.Vertical,
                Spacing = 0,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                        Text = "Situação:"
                    },
                    _SituacaoLabel
                }
            }, 1, 1);

            View = new Frame
            {
                CornerRadius = 5,
                Padding = new Thickness(4, 3),
                Margin = new Thickness(2, 4),
                BackgroundColor = Color.White,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Content = _gridLayout
            };
        }

        private void inicializarComponente() {
            _CodigoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.End,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 10,
                HorizontalTextAlignment = TextAlignment.End,
                VerticalTextAlignment = TextAlignment.Start
            };
            _CodigoLabel.SetBinding(Label.TextProperty, new Binding("Id", stringFormat: "Pedido nº {0}"));
            _MetodoEntregaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CAMPO]
            };
            _MetodoEntregaLabel.SetBinding(Label.TextProperty, new Binding("EntregaStr"));
            _FormaPagamentoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CAMPO],
                LineBreakMode = LineBreakMode.TailTruncation,
            };
            _FormaPagamentoLabel.SetBinding(Label.TextProperty, new Binding("PagamentoStr"));
            _ValorTotalLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CAMPO],
                LineBreakMode = LineBreakMode.TailTruncation,
            };
            _ValorTotalLabel.SetBinding(Label.TextProperty, new Binding("TotalStr", stringFormat: "R${0}"));
            _SituacaoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CAMPO],
                LineBreakMode = LineBreakMode.TailTruncation,
            };
            _SituacaoLabel.SetBinding(Label.TextProperty, new Binding("SituacaoStr"));
        }
    }
}
