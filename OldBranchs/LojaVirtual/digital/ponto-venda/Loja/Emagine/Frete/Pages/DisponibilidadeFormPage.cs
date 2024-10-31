using System;
using System.Collections.Generic;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Endereco.BLL;
using Emagine.Frete.BLL;
using Emagine.Frete.Model;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class DisponibilidadeFormPage : ContentPage
    {
        private DisponibilidadeInfo _Info;
        private Entry _Estado;
        private Entry _Cep;
        private Entry _Cidade;
        private DatePicker _Data;

        private Label _TituloData;
        private Label _TituloLocal;

        private Button _Salvar;
        private Button _Deletar;

        public EventHandler _Refreshed;

        public DisponibilidadeFormPage(DisponibilidadeInfo info = null)
        {
            _Info = info;
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = 10,
                Children = {
                    _TituloData,
                    _Data,
                    _TituloLocal,
                    _Cep,
                    _Estado,
                    _Cidade,
                    _Salvar
                }
            };
            if (info != null)
                ((StackLayout)Content).Children.Add(_Deletar);
        }

        private void inicializarComponente()
        {
            _TituloData = new Label
            {
                Text = "Diga quando você estará livre para carregar: ",
                Style = Estilo.Current[Estilo.TITULO1]
            };
            _TituloLocal = new Label
            {
                Text = "Onde ?",
                Style = Estilo.Current[Estilo.TITULO1]
            };

            _Data = new DatePicker
            {
                //Date = new DateTime(DateTime.Now.Year, DateTime.Now.Month, DateTime.Now.Day, 0, 0, 0, DateTimeKind.Unspecified)
            };
            _Cep = new Entry
            {
                Placeholder = "Buscar por CEP",
                Keyboard = Keyboard.Numeric
            };
            _Cep.TextChanged += async (object sender, TextChangedEventArgs e) => {
                if (e.NewTextValue.Length == 8)
                {
                    _Cep.Unfocus();
                    UserDialogs.Instance.ShowLoading("Obtendo Endereço");
                    try
                    {
                        var ret = await new CepBLL().pegarPorCep(e.NewTextValue);
                        _Estado.Text = ret.Uf;
                        _Cidade.Text = ret.Cidade;
                        UserDialogs.Instance.HideLoading();
                    }
                    catch (Exception error)
                    {
                        UserDialogs.Instance.HideLoading();
                        UserDialogs.Instance.ShowError("Erro ao tentar obter o endereço, verifique sua conexão, ou tente novamente em alguns instantes");
                    }
                }
            };
            _Estado = new Entry
            {
                Placeholder = "Estado",
                IsEnabled = false
            };

            _Cidade = new Entry
            {
                Placeholder = "Cidade",
                IsEnabled = false
            };

            if(_Info != null){
                _Data.Date = _Info.Data;
                _Estado.Text = _Info.Estado;
                _Cidade.Text = _Info.Cidade;
            }

            _Salvar = new Button()
            {
                Text = "Salvar",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };
            _Salvar.Clicked += async (sender, e) =>
            {
                if(String.IsNullOrEmpty(_Estado.Text) || String.IsNullOrEmpty(_Cidade.Text))
                {
                    UserDialogs.Instance.ShowError("Informe um número de CEP válido para preencher o estadp e a cidade");
                    return;
                }
                if(_Info != null){
                    _Info.Data = _Data.Date;
                    _Info.Estado = _Estado.Text;
                    _Info.Cidade = _Cidade.Text;
                    var ret = await new MotoristaBLL().alterarDisponibilidade(_Info);
                } else {
                    var ret = await new MotoristaBLL().inserirDisponibilidade(new DisponibilidadeInfo{
                        IdMotorista = new MotoristaBLL().pegarAtual().Id,
                        Data = new DateTime(_Data.Date.Year, _Data.Date.Month, _Data.Date.Day, 0, 0, 0, DateTimeKind.Unspecified),
                        Estado = _Estado.Text,
                        Cidade = _Cidade.Text
                    });    
                }
                Navigation.PopAsync();
                _Refreshed?.Invoke(this, null);
            };

            _Deletar = new Button()
            {
                Text = "Deletar",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO],
                BackgroundColor = Color.Red
            };
            _Deletar.Clicked += async (sender, e) =>
            {
                var ret = await new MotoristaBLL().excluirDisponibilidade(_Info);
                Navigation.PopAsync();
                _Refreshed?.Invoke(this, null);
            };
        }
    }
}

