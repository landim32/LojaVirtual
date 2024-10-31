using Radar.Estilo;
using Radar.Utils;
using Rg.Plugins.Popup.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Popup
{
    public abstract class BasePopup: PopupPage
    {
        protected abstract void inicializarComponente();
        protected abstract View inicializarTela();

        protected virtual double getHeight() {
            return 240;
        }

        public BasePopup() {

            inicializarComponente();

            var stackLayout = new StackLayout
            {
                Style = EstiloUtils.Popup.MainStackLayout,
                BackgroundColor = Color.Transparent,
                Padding = new Thickness(0, 10, 0, 0),
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    new Frame {
                        Content = inicializarTela()
                    }
                }
            };
            AbsoluteLayout.SetLayoutBounds(stackLayout, new Rectangle(0, 0, 1, 1));
            AbsoluteLayout.SetLayoutFlags(stackLayout, AbsoluteLayoutFlags.All);

            Content = new AbsoluteLayout {
                Style = EstiloUtils.Popup.MainAbsoluteLayout,
                VerticalOptions = LayoutOptions.Center,
                Opacity = 0.0,
                HorizontalOptions = LayoutOptions.Fill,
                Padding = new Thickness(20, 20, 20, 20),
                HeightRequest = getHeight(),
                Children = {
                    stackLayout
                }
            };
        }

        protected Label criarTitulo(string Titulo) {
            return new Label {
                Text = Titulo,
                Style = EstiloUtils.Popup.Titulo
            };
        }

        protected BoxView criarLinha() {
            return new BoxView {
                Style = EstiloUtils.Popup.Linha
            };
        }

        protected override Task OnAppearingAnimationEnd()
        {
            return Content.FadeTo(1);
        }

        protected override Task OnDisappearingAnimationBegin()
        {
            return Content.FadeTo(1);
        }

    }
}
