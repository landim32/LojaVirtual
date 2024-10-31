using Emagine.Base.Controls;
using Emagine.Frete.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class CadastroMotoristaDocumentoPage: CadastroMotoristaPage
    {
        private FotoImageButton _FotoCNHButton;
        private FotoImageButton _FotoResidenciaButton;
        private FotoImageButton _FotoVeiculoButton;
        private FotoImageButton _FotoCPFButton;

        public CadastroMotoristaDocumentoPage() {
            var gridLayout = new Grid
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
            };
            gridLayout.Children.Add(_FotoCNHButton, 0, 0);
            gridLayout.Children.Add(_FotoResidenciaButton, 1, 0);
            gridLayout.Children.Add(_FotoVeiculoButton, 0, 1);
            gridLayout.Children.Add(_FotoCPFButton, 1, 1);
            gridLayout.RowDefinitions.Add(new RowDefinition
            {
                Height = new GridLength(140)
            });
            gridLayout.RowDefinitions.Add(new RowDefinition
            {
                Height = new GridLength(140)
            });
            _mainLayout.Children.Add(gridLayout);
        }

        public override MotoristaInfo Motorista
        {
            get {
                return base.Motorista;
            }
            set {
                base.Motorista = value;
            }
        }

        protected override void inicializarComponente() {
            base.inicializarComponente();
            _FotoCNHButton = new FotoImageButton()
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Source = "FotoCNH.png"
            };

            _FotoResidenciaButton = new FotoImageButton()
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Source = "FotoResidencia.png"
            };

            _FotoVeiculoButton = new FotoImageButton()
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Source = "FotoVeiculo.png"
            };

            _FotoCPFButton = new FotoImageButton()
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Source = "FotoCPF.png"
            };
        }
    }
}
