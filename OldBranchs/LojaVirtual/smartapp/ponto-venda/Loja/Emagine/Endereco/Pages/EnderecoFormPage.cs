using Emagine.Base.Estilo;
using Emagine.Endereco.Controls;
using Emagine.Endereco.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Endereco.Pages
{
    public class EnderecoFormPage: ContentPage
    {
        private EnderecoForm _enderecoForm;
        private Button _avancarButton;

        public event EventHandler<EnderecoInfo> AoSelecionar;

        public EnderecoFormPage() {
            inicializarComponente();
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];

            Content = new ScrollView { 
                Orientation = ScrollOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Vertical,
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Margin = new Thickness(5, 10),
                    Children = {
                        _enderecoForm,
                        _avancarButton
                    }
                }
            };
        }

        public bool PodeEditar {
            get {
                return _enderecoForm.PodeEditar;
            }
            set {
                _enderecoForm.PodeEditar = value;
            }
        }

        public EnderecoInfo Endereco {
            get {
                return _enderecoForm.Endereco;
            }
            set {
                _enderecoForm.Endereco = value;
            }
        }

        private void inicializarComponente() {
            _enderecoForm = new EnderecoForm {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill
            };
            _avancarButton = new Button {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.End,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Avançar"
            };
            _avancarButton.Clicked += async (sender, e) => {
                var endereco = this.Endereco;
                if (string.IsNullOrEmpty(endereco.Numero)) {
                    await DisplayAlert("Aviso", "Preencha o número.", "Entendi");
                    return;
                }
                AoSelecionar?.Invoke(this, Endereco);
            };
        }
    }
}
