using Emagine.Base.Estilo;
using Emagine.Pagamento.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pagamento.Pages
{
    public class BoletoPage : ContentPage
    {
        private Button _ImprimirButton;

        public PagamentoInfo Pagamento { get; set; }

        public BoletoPage()
        {
            Title = "Boleto Bancário";
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];

            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(5, 20),
                Spacing = 15,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.TITULO1],
                        Text = "Seu pagamento ainda não foi efetuado!"
                    },
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.TITULO3],
                        Text = "Clique no botão abaixo para imprimir o boleto. Pode demorar de um a dois dias para baixa no pagamento."
                    },
                    _ImprimirButton
                }
            };
        }

        private void inicializarComponente() {
            _ImprimirButton = new Button {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Imprimir Boleto"
            };
            _ImprimirButton.Clicked += (sender, e) => {
                var boletoImprimePage = new BoletoImprimePage {
                    Pagamento = Pagamento
                };
                Navigation.PushAsync(boletoImprimePage);
            };
        }
    }
}