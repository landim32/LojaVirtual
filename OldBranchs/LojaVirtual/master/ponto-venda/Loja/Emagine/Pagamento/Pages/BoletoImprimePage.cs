using Emagine.Pagamento.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pagamento.Pages
{
    public class BoletoImprimePage : ContentPage
    {
        private WebView _boletoWebView;

        public PagamentoInfo Pagamento {
            get {
                return (PagamentoInfo)BindingContext;
            }
            set {
                BindingContext = value;
                if (value != null) {
                    _boletoWebView.Source = new UrlWebViewSource
                    {
                        Url = Pagamento.BoletoUrl
                    };
                }
            }
        }

        public BoletoImprimePage()
        {
            Title = "Boleto Bancário";
            inicializarComponente();
            Content = _boletoWebView;
        }

        private void inicializarComponente() {
            _boletoWebView = new WebView
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill
            };
        }
    }
}