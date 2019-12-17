using Emagine.Base.Estilo;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Produto.Controls
{
    public class TotalCarrinhoView: ContentView
    {
        private bool _exibeQuantidade = true;
        private bool _exibeTotal = true;
        private int _quantidade = 0;
        private double _total = 0;

        private Grid _totalLayout;
        private Label _quantidadeLabel;
        private Label _totalLabel;

        public int Quantidade {
            get {
                return _quantidade;
            }
            set {
                _quantidade = value;
                _quantidadeLabel.Text = _quantidade.ToString("N0");
            }
        }

        public bool ExibeQuantidade {
            get {
                return _exibeQuantidade;
            }
            set {
                _exibeQuantidade = value;
                atualizarTotal();
            }
        }

        public bool ExibeTotal {
            get {
                return _exibeTotal;
            }
            set {
                _exibeTotal = value;
                atualizarTotal();
            }
        }

        public double Total {
            get {
                return _total;
            }
            set {
                _total = value;
                _totalLabel.Text = "R$" + _total.ToString("N2");
            }
        }

        public TotalCarrinhoView() {
            inicializarComponente();
            atualizarTotal();
            Content = new Frame {
                Style = Estilo.Current[Estilo.TOTAL_FRAME],
                Content = _totalLayout
            };
        }

        private void inicializarComponente()
        {
            _totalLayout = new Grid
            {
                //Margin = new Thickness(10, 10),
                //RowSpacing = 10,
                //ColumnSpacing = 10
            };
            _totalLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });

            _quantidadeLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.TOTAL_TEXTO],
                Text = "0",
            };

            _totalLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.TOTAL_TEXTO],
                Text = "R$ 0,00",
            };
        }

        private void atualizarTotal()
        {
            _totalLayout.Children.Clear();
            _totalLayout.ColumnDefinitions.Clear();
            if (_exibeQuantidade)
            {
                _totalLayout.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            }
            if (_exibeTotal)
            {
                _totalLayout.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            }

            int left = 0;
            if (_exibeQuantidade)
            {
                _totalLayout.Children.Add(new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.CenterAndExpand,
                    Spacing = 2,
                    Children = {
                        new Label {
                            VerticalOptions = LayoutOptions.Center,
                            HorizontalOptions = LayoutOptions.Start,
                            Style = Estilo.Current[Estilo.TOTAL_LABEL],
                            Text = "Quantidade: "
                        },
                        _quantidadeLabel
                    }
                }, left, 0);
                left++;
            }

            if (_exibeTotal)
            {
                _totalLayout.Children.Add(new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.CenterAndExpand,
                    Spacing = 2,
                    Children = {
                        new Label {
                            VerticalOptions = LayoutOptions.Center,
                            HorizontalOptions = LayoutOptions.Start,
                            Style = Estilo.Current[Estilo.TOTAL_LABEL],
                            Text = "Total: "
                        },
                        _totalLabel
                    }
                }, left, 0);
                left++;
            }
        }

        private void CarrinhoAoAtualizar(object sender, CarrinhoEventArgs e)
        {
            this.Quantidade = e.Quantidade;
            this.Total = e.Total;
        }

        public void vincularComCarrinho() {
            var regraCarrinho = CarrinhoFactory.create();
            regraCarrinho.AoAtualizar += CarrinhoAoAtualizar;
            _quantidadeLabel.Text = regraCarrinho.getQuantidade().ToString("N0");
            _totalLabel.Text = "R$ " + regraCarrinho.getTotal().ToString("N2");
        }

        public void desvincularComCarrinho() {
            var regraCarrinho = CarrinhoFactory.create();
            regraCarrinho.AoAtualizar -= CarrinhoAoAtualizar;
        }
    }
}
