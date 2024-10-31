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
    public class QuantidadeHControl : QuantidadeBaseControl
    {
        public QuantidadeHControl()
        {
            HeightRequest = 40;
            MinimumHeightRequest = 40;
            inicializarComponente();
            Content = new AbsoluteLayout
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                HeightRequest = 40,
                Children = {
                    //_adicionarButton,
                    _removerButton,
                    _adicionarButton,
                    _quantidadeLayout
                }
            };
        }

        protected override void inicializarComponente() {
            base.inicializarComponente();
            //AbsoluteLayout.SetLayoutBounds(_adicionarButton, new Rectangle(0, 0, 0.5, 1));
            _adicionarButton.Padding = new Thickness(15, 5, 5, 5);
            AbsoluteLayout.SetLayoutBounds(_adicionarButton, new Rectangle(1, 0, 0.5, 1));
            AbsoluteLayout.SetLayoutFlags(_adicionarButton, AbsoluteLayoutFlags.All);

            //AbsoluteLayout.SetLayoutBounds(_removerButton, new Rectangle(1, 0, 0.5, 1));
            _removerButton.Padding = new Thickness(5, 5, 15, 5);
            AbsoluteLayout.SetLayoutBounds(_removerButton, new Rectangle(0, 0, 0.5, 1));
            AbsoluteLayout.SetLayoutFlags(_removerButton, AbsoluteLayoutFlags.All);

            AbsoluteLayout.SetLayoutBounds(_quantidadeLayout, new Rectangle(0.5, 0, 0.3, 1));
            AbsoluteLayout.SetLayoutFlags(_quantidadeLayout, AbsoluteLayoutFlags.All);
        }
    }
}