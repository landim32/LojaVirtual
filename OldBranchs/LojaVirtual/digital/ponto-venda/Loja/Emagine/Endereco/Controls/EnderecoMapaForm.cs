using System;
using Acr.UserDialogs;
using Emagine.Endereco.Model;
using Emagine.Mapa.Controls;
using Emagine.Mapa.Model;
using Xamarin.Forms;

namespace Emagine.Endereco.Controls
{
    public class EnderecoMapaForm : ContentView
    {
        private Label _tituloLabel;
        private Entry _cepEntry;
        private Entry _ufEntry;
        private Entry _cidadeEntry;
        private Entry _ruaEntry;
        private Entry _numeroEntry;
        private MapaDropDownList _verNoMapa;


        public EnderecoMapaForm()
        {
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _tituloLabel,
                    _cepEntry,
                    _ufEntry,
                    _cidadeEntry,
                    _ruaEntry,
                    _numeroEntry,
                    _verNoMapa
                }
            };
        }

        public string Titulo
        {
            get
            {
                return _tituloLabel.Text;
            }
            set
            {
                _tituloLabel.Text = value;
            }
        }

        public EnderecoInfo Endereco
        {
            get
            {
                double? Latitude = null;
                double? Longitude = null;
                if(_verNoMapa.Value != null){
                    Latitude = _verNoMapa.Value.Latitude;
                    Longitude = _verNoMapa.Value.Longitude;
                }
                return new EnderecoInfo
                {
                    Cep = _cepEntry.Text,
                    Uf = _ufEntry.Text,
                    Cidade = _cidadeEntry.Text,
                    Logradouro = _ruaEntry.Text,
                    Numero = _numeroEntry.Text,
                    Latitude = Latitude,
                    Longitude = Longitude
                };
            }
            set
            {
                if (value != null)
                {
                    _cepEntry.Text = value.Cep;
                    _ufEntry.Text = value.Uf;
                    _cidadeEntry.Text = value.Cidade;
                    _ruaEntry.Text = value.Logradouro;
                    _numeroEntry.Text = value.Numero;
                    _ruaEntry.Text = value.Logradouro;
                    if(value.Latitude != null && value.Longitude != null)
                        _verNoMapa.Value = new MapaLocalInfo
                        {
                            Titulo = "Mapa",
                            Latitude = (float)value.Latitude,
                            Longitude = (float)value.Longitude,
                            Endereco = value.Bairro + ", " +  value.Cidade + " - " + value.Bairro 
                        };
                }
                else
                {
                    _cepEntry.Text = "";
                    _ufEntry.Text = "";
                    _cidadeEntry.Text = "";
                    _ruaEntry.Text = "";
                    _numeroEntry.Text = "";
                    _verNoMapa.Value = null;
                }
            }
        }

        private void inicializarComponente()
        {
            _tituloLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                FontAttributes = FontAttributes.Bold,
                Text = "Endereço"
            };
            _cepEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "CEP",
                Keyboard = Keyboard.Numeric
            };
            _cepEntry.TextChanged += async (object sender, TextChangedEventArgs e) => {
                if(e.NewTextValue.Length == 8){
                    _cepEntry.Unfocus();
                    UserDialogs.Instance.ShowLoading("Obtendo Endereço");
                    try{
                        Endereco = await new BLL.CepBLL().pegarPorCep(e.NewTextValue);
                        UserDialogs.Instance.HideLoading();
                    }
                    catch(Exception error){
                        UserDialogs.Instance.HideLoading();
                        UserDialogs.Instance.ShowError("Erro ao tentar obter o endereço, verifique sua conexão, ou tente novamente em alguns instantes");
                    }
                }
            };
            _ufEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "UF",
                IsEnabled = false
            };
            _cidadeEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "Cidade",
                IsEnabled = false
            };
            _ruaEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "Rua",
                IsEnabled = false
            };
            _numeroEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "Número",
                Keyboard = Keyboard.Numeric
            };
            _verNoMapa = new MapaDropDownList
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                TituloPadrao = "Mapa",
                Placeholder = "Ver endereço no mapa"
            };

        }

    }
}

