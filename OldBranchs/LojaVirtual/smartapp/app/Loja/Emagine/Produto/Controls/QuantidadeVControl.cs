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
    public class QuantidadeVControl : QuantidadeBaseControl
    {
        public QuantidadeVControl()
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

        protected override void inicializarComponente()
        {
            base.inicializarComponente();

            _adicionarButton.Padding = new Thickness(10, 10, 10, 25);
            AbsoluteLayout.SetLayoutBounds(_adicionarButton, new Rectangle(0, 0, 1, 0.5));
            AbsoluteLayout.SetLayoutFlags(_adicionarButton, AbsoluteLayoutFlags.All);

            _removerButton.Padding = new Thickness(10, 25, 10, 10);
            AbsoluteLayout.SetLayoutBounds(_removerButton, new Rectangle(0, 1, 1, 0.5));
            AbsoluteLayout.SetLayoutFlags(_removerButton, AbsoluteLayoutFlags.All);

            _quantidadeLayout.Padding = 5;
            AbsoluteLayout.SetLayoutBounds(_quantidadeLayout, new Rectangle(0, 0.5, 1, 0.4));
            AbsoluteLayout.SetLayoutFlags(_quantidadeLayout, AbsoluteLayoutFlags.All);
        }
    }
}