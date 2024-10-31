using System;
using Emagine.Base.Estilo;
using Xamarin.Forms;

namespace Emagine.Frete.Cells
{
    public class LocalRetiradaCell : ViewCell
    {
        private Label _Endereco;

        public LocalRetiradaCell()
        {
            inicializarComponente();
            View = new StackLayout
            {
                Margin = 10,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _Endereco,
                    new Label {
                        Text = "(Toque para selecionar ou editar o endereço)",
                        TextColor = Color.LightGray,
                        HorizontalOptions = LayoutOptions.Fill,
                        HorizontalTextAlignment = TextAlignment.Center,
                        Margin = new Thickness(0, 0, 0, 5)
                    }
                }
            };
        }

        private void inicializarComponente()
        {
            _Endereco = new Label
            {
                Style = Estilo.Current[Estilo.TITULO3]
            };
            _Endereco.SetBinding(Label.TextProperty, new Binding("EnderecoLbl"));


        }
    }
}

