using Emagine.Pedido.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pedido.Controls
{
	public class PedidoView : ContentView
	{
        private Grid _gridLayout;

        public PedidoInfo Pedido {
            get {
                return (PedidoInfo)BindingContext;
            }
            set {
                BindingContext = value;
            }
        }

		public PedidoView ()
		{
            inicializarComponente();

            _gridLayout = new Grid
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
            };

            Content = _gridLayout;
        }

        protected override void OnBindingContextChanged()
        {
            base.OnBindingContextChanged();
            _gridLayout.Children.Clear();
            if (Pedido != null) {
                gerarLista(Pedido);
            }
        }

        private void gerarCabecalho(ref int linha) {
            _gridLayout.RowDefinitions.Add(new RowDefinition { Height = GridLength.Auto });
            _gridLayout.Children.Add(new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                FontAttributes = FontAttributes.Bold,
                FontSize = 10,
                Text = "Produto"
            }, 0, linha);
            _gridLayout.Children.Add(new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.End,
                FontAttributes = FontAttributes.Bold,
                FontSize = 10,
                Text = "Quantidade"
            }, 1, linha);
            _gridLayout.Children.Add(new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.End,
                FontAttributes = FontAttributes.Bold,
                FontSize = 10,
                Text = "Preço"
            }, 2, linha);
            linha++;
        }

        private void gerarItem(ref int linha, PedidoItemInfo item)
        {
            _gridLayout.RowDefinitions.Add(new RowDefinition { Height = GridLength.Auto });
            _gridLayout.Children.Add(new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 10,
                Text = item.Produto.Nome
            }, 0, linha);
            _gridLayout.Children.Add(new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.End,
                FontSize = 10,
                Text = item.Quantidade.ToString()
            }, 1, linha);
            _gridLayout.Children.Add(new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.End,
                FontSize = 10,
                Text = item.Produto.ValorFinal.ToString("N2")
            }, 2, linha);
            linha++;
        }

        private void gerarValorFrete(ref int linha, PedidoInfo pedido)
        {
            _gridLayout.RowDefinitions.Add(new RowDefinition { Height = GridLength.Auto });
            var lb = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.End,
                FontAttributes = FontAttributes.Bold,
                FontSize = 10,
                Text = "Valor de Frete:"
            };
            _gridLayout.Children.Add(lb, 0, linha);
            Grid.SetColumnSpan(lb, 2);

            _gridLayout.Children.Add(new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.End,
                FontAttributes = FontAttributes.Bold,
                FontSize = 10,
                Text = pedido.ValorFreteStr
            }, 2, linha);
            linha++;
        }

        private void gerarValorTotal(ref int linha, PedidoInfo pedido)
        {
            _gridLayout.RowDefinitions.Add(new RowDefinition { Height = GridLength.Auto });
            var lb = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.End,
                FontAttributes = FontAttributes.Bold,
                FontSize = 10,
                Text = "Valor Total:"
            };
            _gridLayout.Children.Add(lb, 0, linha);
            Grid.SetColumnSpan(lb, 2);

            _gridLayout.Children.Add(new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.End,
                FontAttributes = FontAttributes.Bold,
                FontSize = 10,
                Text = pedido.TotalStr
            }, 2, linha);
            linha++;
        }

        private void gerarLinha(ref int linha) {
            _gridLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            var hr = new BoxView
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HeightRequest = 1,
                BackgroundColor = Color.Black
            };
            _gridLayout.Children.Add(hr, 0, linha);
            Grid.SetColumnSpan(hr, 3);
            linha++;
        }

        private void gerarLista(PedidoInfo pedido) {
            int linha = 0;
            gerarCabecalho(ref linha);
            gerarLinha(ref linha);
            foreach (var item in pedido.Itens) {
                gerarItem(ref linha, item);
            }
            gerarLinha(ref linha);
            gerarValorFrete(ref linha, pedido);
            gerarValorTotal(ref linha, pedido);
        }

        private void inicializarComponente() {
        }
	}
}