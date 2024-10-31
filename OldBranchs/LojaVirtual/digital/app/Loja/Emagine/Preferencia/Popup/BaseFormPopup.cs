using Radar.Estilo;
using Radar.Utils;
using Rg.Plugins.Popup.Services;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.Popup
{
    public abstract class BaseFormPopup : BasePopup
    {
        Button _OkButton;
        Button _CancelarButton;

        protected abstract string getTitulo();
        public abstract View inicializarConteudo();
        public abstract void gravar();
        protected virtual string getOk()
        {
            return "Ok";
        }
        protected virtual string getCancelar()
        {
            return "Cancelar";
        }

        protected override void inicializarComponente()
        {
            _OkButton = new Button
            {
                Style = EstiloUtils.Popup.Botao,
                Text = getOk(),
            };
            _OkButton.Clicked += (sender, e) => {
                gravar();
            };

            _CancelarButton = new Button
            {
                Style = EstiloUtils.Popup.Botao,
                Text = getCancelar(),
            };
            _CancelarButton.Clicked += (sender, e) => {
                PopupNavigation.PopAsync();
            };
        }

        protected override View inicializarTela()
        {
            return new StackLayout
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    criarTitulo(getTitulo()),
                    criarLinha(),
                    new ScrollView {
                        Orientation = ScrollOrientation.Vertical,
                        VerticalOptions = LayoutOptions.StartAndExpand,
                        Content = inicializarConteudo()
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.CenterAndExpand,
                        Children = {
                            _OkButton,
                            _CancelarButton
                        }
                    }
                }
            };
        }
    }
}
