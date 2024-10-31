using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Endereco.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Text.RegularExpressions;
using Xamarin.Forms;
using Xfx;

namespace Emagine.Endereco.Controls
{
    public class EnderecoForm : ContentView
    {
        private bool _podeEditar = true;

        private StackLayout _mainStack;
        private Label _tituloLabel;
        private CepEntry _cepEntry;
        private XfxEntry _ufEntry;
        private XfxEntry _cidadeEntry;
        private XfxEntry _bairroEntry;
        private XfxEntry _logradouroEntry;
        private XfxEntry _complementoEntry;
        private XfxEntry _numeroEntry;

        private bool setCepOnly = false;

        private double _latitude;
        private double _longitude;

        public EnderecoForm()
        {
            inicializarComponente();
            _mainStack = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _cepEntry,
                    _ufEntry,
                    _cidadeEntry,
                    _bairroEntry,
                    _logradouroEntry,
                    _complementoEntry,
                    _numeroEntry
                }
            };
            Content = _mainStack;
            doFocus();
        }

        public string Titulo {
            get {
                return _tituloLabel.Text;
            }
            set {
                _tituloLabel.Text = value;
                if (string.IsNullOrEmpty(_tituloLabel.Text))
                {
                    if (_mainStack.Children[0] == _tituloLabel)
                    {
                        _mainStack.Children.RemoveAt(0);
                    }
                }
                else {
                    _mainStack.Children.Insert(0, _tituloLabel);
                    verificarEdicao();
                }
            }
        }

        public bool PodeEditar {
            get {
                return _podeEditar;
            }
            set {
                _podeEditar = value;
                verificarEdicao();
            }
        }

        protected bool doFocus() {
            if (_cepEntry.IsEnabled && string.IsNullOrEmpty(_cepEntry.TextOnlyNumber)) {
                return _cepEntry.Focus();
            }
            else if (_logradouroEntry.IsEnabled && string.IsNullOrEmpty(_logradouroEntry.Text)) {
                return _logradouroEntry.Focus();
            }
            else if (_complementoEntry.IsEnabled && string.IsNullOrEmpty(_complementoEntry.Text)) {
                return _complementoEntry.Focus();
            }
            else if (_numeroEntry.IsEnabled && string.IsNullOrEmpty(_numeroEntry.Text)) {
                return _numeroEntry.Focus();
            }
            else if (_bairroEntry.IsEnabled && string.IsNullOrEmpty(_bairroEntry.Text)) {
                return _bairroEntry.Focus();
            }
            else if (_cidadeEntry.IsEnabled && string.IsNullOrEmpty(_cidadeEntry.Text)) {
                return _cidadeEntry.Focus();
            }
            else if (_ufEntry.IsEnabled && string.IsNullOrEmpty(_ufEntry.Text)) {
                return _ufEntry.Focus();
            }
            return false;
        }

        private void permitirEdicao() {
            _cepEntry.IsEnabled = true;
            _logradouroEntry.IsEnabled = true;
            _complementoEntry.IsEnabled = true;
            _numeroEntry.IsEnabled = true;
            _bairroEntry.IsEnabled = true;
            _cidadeEntry.IsEnabled = true;
            _ufEntry.IsEnabled = true;
        }

        private void bloquearEdicao() {
            _cepEntry.IsEnabled = string.IsNullOrEmpty(_cepEntry.Text);
            _logradouroEntry.IsEnabled = string.IsNullOrEmpty(_logradouroEntry.Text);
            _complementoEntry.IsEnabled = string.IsNullOrEmpty(_complementoEntry.Text);
            _numeroEntry.IsEnabled = string.IsNullOrEmpty(_numeroEntry.Text);
            _bairroEntry.IsEnabled = string.IsNullOrEmpty(_bairroEntry.Text);
            _cidadeEntry.IsEnabled = string.IsNullOrEmpty(_cidadeEntry.Text);
            _ufEntry.IsEnabled = string.IsNullOrEmpty(_ufEntry.Text);
        }

        private void verificarEdicao() {
            if (_podeEditar)
            {
                permitirEdicao();
            }
            else {
                bloquearEdicao();
            }
        }

        public EnderecoInfo Endereco
        {
            get {
                return new EnderecoInfo {
                    Cep = _cepEntry.TextOnlyNumber,
                    Uf = _ufEntry.Text,
                    Cidade = _cidadeEntry.Text,
                    Bairro = _bairroEntry.Text,
                    Logradouro = _logradouroEntry.Text,
                    Complemento = _complementoEntry.Text,
                    Numero = _numeroEntry.Text,
                    Latitude = _latitude,
                    Longitude = _longitude
                };
            }
            set {
                if (value != null)
                {
                    //if(value.Cep != Regex.Replace(_cepEntry.Text, "[^0-9]+", ""))
                    _cepEntry.Text = value.Cep;
                    _ufEntry.Text = value.Uf;
                    _cidadeEntry.Text = value.Cidade;
                    _bairroEntry.Text = value.Bairro;
                    _logradouroEntry.Text = value.Logradouro;
                    _complementoEntry.Text = value.Complemento;
                    _numeroEntry.Text = value.Numero;
                    _latitude = value.Latitude.HasValue ? value.Latitude.Value : 0;
                    _longitude = value.Longitude.HasValue ? value.Longitude.Value : 0;
                }
                else {
                    _cepEntry.Text = "";
                    _ufEntry.Text = "";
                    _cidadeEntry.Text = "";
                    _bairroEntry.Text = "";
                    _logradouroEntry.Text = "";
                    _complementoEntry.Text = "";
                    _numeroEntry.Text = "";
                    _latitude = 0;
                    _longitude = 0;
                }
                if(!setCepOnly)
                    verificarEdicao();
            }
        }

        public void setOnlyCep()
        {
            setCepOnly = true;
            _cepEntry.TextChanged += async (object sender, TextChangedEventArgs e) => {
                if (e.NewTextValue.Length == 9)
                {
                    _cepEntry.Unfocus();
                    UserDialogs.Instance.ShowLoading("Obtendo Endereço");
                    try 
                    {
                        Endereco = await new BLL.CepBLL().pegarPorCep(Regex.Replace(e.NewTextValue, "[^0-9]+", ""));
                        UserDialogs.Instance.HideLoading();
                    }
                    catch (Exception erro)
                    {
                        UserDialogs.Instance.HideLoading();
                        UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
                        //UserDialogs.Instance.Alert("Erro ao tentar obter o endereço, verifique sua conexão, ou tente novamente em alguns instantes", "Erro", "Entendi");
                    }
                }
            };
            _ufEntry.IsEnabled = false;
            _cidadeEntry.IsEnabled = false;
            _bairroEntry.IsEnabled = false;
            _logradouroEntry.IsEnabled = false;
        }

        private void inicializarComponente() {
            _tituloLabel = new Label {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                FontAttributes = FontAttributes.Bold,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Text = "Endereço"
            };
            _cepEntry = new CepEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "CEP"
            };
            _ufEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "UF",
                ErrorDisplay = ErrorDisplay.None
            };
            _cidadeEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "Cidade",
                ErrorDisplay = ErrorDisplay.None
            };
            _bairroEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "Bairro",
                ErrorDisplay = ErrorDisplay.None
            };
            _logradouroEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "Logradouro",
                ErrorDisplay = ErrorDisplay.None
            };
            _complementoEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "Complemento",
                ErrorDisplay = ErrorDisplay.None
            };
            _numeroEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "Número",
                ErrorDisplay = ErrorDisplay.None
            };
        }
    }
}