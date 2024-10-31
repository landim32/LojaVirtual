using Emagine.Base.Estilo;
using Emagine.Endereco.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Endereco.Controls
{
    public class EnderecoListaForm : ContentView
    {
        private IList<EnderecoForm> _enderecos;
        private StackLayout _mainLayout;
        private Button _adicionarButton;

        public EnderecoListaForm()
        {
            _enderecos = new List<EnderecoForm>();
            _enderecos.Add(new EnderecoForm
            {
                Titulo = "Endereço 1"
            });
            _enderecos.FirstOrDefault().setOnlyCep();

            inicializarComponente();

            _mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };

            Content = _mainLayout;

            atualizarTela();
        }

        public IList<EnderecoInfo> Enderecos {
            get {
                var enderecos = new List<EnderecoInfo>();
                foreach (var enderecoForm in _enderecos) {
                    enderecos.Add(enderecoForm.Endereco);
                }
                return enderecos;
            }
            set {
                _enderecos.Clear();
                foreach (var endereco in value) {
                    _enderecos.Add(new EnderecoForm
                    {
                        Endereco = endereco
                    });
                    _enderecos.LastOrDefault().setOnlyCep();
                }
                atualizarTela();
            }
        }

        private void atualizarTela() {
            _mainLayout.Children.Clear();
            foreach (var enderecoForm in _enderecos) {
                _mainLayout.Children.Add(enderecoForm);
            }
            _mainLayout.Children.Add(_adicionarButton);
        }

        private void inicializarComponente() {
            _adicionarButton = new Button
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PADRAO],
                Text = "Adicionar mais um endereço"
            };
            _adicionarButton.Clicked += (sender, e) => {
                _enderecos.Add(new EnderecoForm {
                    Titulo = "Endereço " + (_enderecos.Count() + 1).ToString()
                });
                _enderecos.LastOrDefault().setOnlyCep();
                atualizarTela();
            };
        }
    }
}