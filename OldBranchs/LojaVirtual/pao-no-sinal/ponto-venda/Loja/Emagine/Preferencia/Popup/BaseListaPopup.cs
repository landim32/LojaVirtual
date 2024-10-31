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
    public abstract class BaseListaPopup : BasePopup
    {
        private Button _SalvarButton;
        private Button _FecharButton;

        public bool SalvarVisivel { get; set; }
        protected virtual string getSalvar()
        {
            return "Alterar";
        }
        protected virtual void salvar() {
            // nada
        }

        protected abstract string getTitulo();
        public abstract View inicializarConteudo();
        protected virtual string getFechar()
        {
            return "Sair";
        }

        protected override void inicializarComponente()
        {
            if (SalvarVisivel) {
                _SalvarButton = new Button
                {
                    Style = EstiloUtils.Popup.Botao,
                    Text = getSalvar(),
                };
                _SalvarButton.Clicked += (sender, e) => {
                    salvar();
                    PopupNavigation.PopAsync();
                };
            }
            _FecharButton = new Button
            {
                Style = EstiloUtils.Popup.Botao,
                Text = getFechar(),
            };
            _FecharButton.Clicked += (sender, e) => {
                PopupNavigation.PopAsync();
            };
        }

        protected override View inicializarTela()
        {
            var footerLayout = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.CenterAndExpand
            };

            if (SalvarVisivel) {
                footerLayout.Children.Add(_SalvarButton);
            }
            footerLayout.Children.Add(_FecharButton);

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
                    footerLayout
                }
            };
        }
    }
}
