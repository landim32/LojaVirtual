using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Frete.BLL;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Login.Pages;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;
using Xfx;

namespace Emagine.Frete.Pages
{
    public class FreteUsuarioFormPage : UsuarioFormPage
    {
        protected Picker _TipoSwitch;
        protected XfxEntry _CNHEntry;
        private int _OlderIndex;

        public event EventHandler<UsuarioInfo> AoCadastrarMotorista;
        public event EventHandler<UsuarioInfo> AoCadastrarEmpresa;

        public override UsuarioInfo Usuario {
            get {
                var usuario = base.Usuario;
                /*
                var cnh = usuario.Preferencias.Where(x => x.Chave == "CNH").FirstOrDefault();
                if (_CNHEntry != null && !String.IsNullOrEmpty(_CNHEntry.Text) && cnh != null)
                {
                    if (cnh != null)
                    {
                        cnh.Valor = _CNHEntry.Text;
                    }
                    else {
                        usuario.Preferencias.Add(new UsuarioPreferenciaInfo
                        {
                            Chave = "CNH",
                            Valor = _CNHEntry.Text
                        });
                    }
                }
                else if (_TipoSwitch.SelectedIndex == 1){
                    usuario.Preferencias.Add(new UsuarioPreferenciaInfo {
                        Chave = "CNH",
                        Valor = _CNHEntry.Text
                    });
                }
                */
                return usuario;
            }
            set {
                base.Usuario = value;
                if (base.Usuario != null)
                {
                    var motorista = new MotoristaBLL().pegarAtual();
                    if (motorista != null)
                    {
                        _CPFEntry.Placeholder = "CPF";
                        /*
                        var cnh = base.Usuario.Preferencias.Where(x => x.Chave == "CNH").FirstOrDefault();
                        if (cnh != null)
                        {
                            _CNHEntry.Text = cnh.Valor;
                        }
                        */
                        _TipoSwitch.SelectedIndex = 1;
                    }
                    else
                    {
                        _CPFEntry.Placeholder = "CNPJ";
                        _TipoSwitch.SelectedIndex = 2;
                    }
                }
            }
        }

        protected override void atualizarTela()
        {
            var usuario = this.Usuario;
            _mainStack.Children.Clear();
            _mainStack.Children.Add(_TipoSwitch);
            _mainStack.Children.Add(_NomeEntry);
            _mainStack.Children.Add(_EmailEntry);
            _mainStack.Children.Add(_TelefoneEntry);
            _mainStack.Children.Add(_CPFEntry);
            //_mainStack.Children.Add(_CNHEntry);
            if (!(usuario != null && usuario.Id > 0))
            {
                _mainStack.Children.Add(_SenhaEntry);
                _mainStack.Children.Add(_ConfirmaEntry);

            } else {
                _CadastroButton.Text = "ATUALIZAR";
            }
            _mainStack.Children.Add(_CadastroButton);
        }

        protected virtual bool validarCpfCnpj() {
            if (_TipoSwitch.SelectedIndex == 1)
            {
                if (String.IsNullOrEmpty(_CPFEntry.Text))
                {
                    DisplayAlert("Aviso", "Preencha o CPF", "Fechar");
                    return false;
                }
                if (!FormatUtils.validarCpf(_CPFEntry.Text))
                {
                    DisplayAlert("Aviso", "Preencha com um CPF válido", "Fechar");
                    return false;
                }
            }
            else if (_TipoSwitch.SelectedIndex == 2)
            {
                if (String.IsNullOrEmpty(_CPFEntry.Text))
                {
                    DisplayAlert("Aviso", "Preencha o CNPJ", "Fechar");
                    return false;
                }
                if (!FormatUtils.validarCnpj(_CPFEntry.Text))
                {
                    DisplayAlert("Aviso", "Preencha com um CNPJ válido", "Fechar");
                    return false;
                }
            }
            return true;
        }

        protected virtual bool validarCNH() {
            if (_TipoSwitch.SelectedIndex == 1)
            {
                if (String.IsNullOrEmpty(_CNHEntry.Text))
                {
                    DisplayAlert("Aviso", "Preencha a CNH", "Fechar");
                    return false;
                }
            }
            return true;
        }

        protected override bool validar()
        {
            if (!base.validar() || !validarCpfCnpj() || !validarCNH()) {
                return false;
            }
            return true;
        }

        protected override void efetuarCadastro(UsuarioInfo usuario)
        {
            base.efetuarCadastro(usuario);
            if (_TipoSwitch.SelectedIndex == 1)
            {
                AoCadastrarMotorista?.Invoke(this, usuario);
            }
            else if (_TipoSwitch.SelectedIndex == 2)
            {
                AoCadastrarEmpresa?.Invoke(this, usuario);
            }
        }

        protected virtual List<string> listarTipoConta() {
            return new List<string> {
                "--Tipo de conta--",
                "Motorista",
                "Empresa"
            };
        }

        protected override void inicializarComponente()
        {
            base.inicializarComponente();

            _TipoSwitch = new Picker
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };
            _TipoSwitch.ItemsSource = listarTipoConta();
            _TipoSwitch.SelectedIndex = 0;
            _TipoSwitch.SelectedIndexChanged += (sender, e) => {
                if(_TipoSwitch.SelectedIndex == 1 && _OlderIndex != 1){
                    _CPFEntry.Placeholder = "CPF";
                    if (!_mainStack.Children.Contains(_CPFEntry))
                    {
                        _mainStack.Children.Insert(_mainStack.Children.Count - 1, _CPFEntry);
                    }
                    /*
                    if (!_mainStack.Children.Contains(_CNHEntry))
                    {
                        _mainStack.Children.Insert(_mainStack.Children.Count - 1, _CNHEntry);
                    }
                    */
                } else if (_TipoSwitch.SelectedIndex == 2 && _OlderIndex != 2){
                    _CPFEntry.Placeholder = "CNPJ";
                    if (!_mainStack.Children.Contains(_CPFEntry))
                    {
                        _mainStack.Children.Insert(_mainStack.Children.Count - 1, _CPFEntry);
                    }
                    /*
                    if (_mainStack.Children.Contains(_CNHEntry))
                    {
                        _mainStack.Children.Remove(_CNHEntry);
                    }
                    */
                }
                _OlderIndex = _TipoSwitch.SelectedIndex;
            };
            /*
            _CNHEntry = new XfxEntry
            {
                Placeholder = "CNH",
                Keyboard = Keyboard.Numeric,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                ErrorDisplay = ErrorDisplay.None,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL]
            };
            */
        }
    }
}