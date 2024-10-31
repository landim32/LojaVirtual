using System;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Frete.BLL;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class FreteAvisoPage : ContentPage
    {
        private FreteInfo _frete;

        private Label _Titulo;
        private Label _Origem;
        private Label _Destino;
        private IconImage _DivisoriaOrigemDestino;
        private Label _DataSaida;
        private Label _DataChegada;
        private IconImage _DivisoriaData;

        private Label _Valor;

        private Button _Confirmar;

        private bool selecionado = false;

        public FreteAvisoPage(FreteInfo info)
        {
            _frete = info;
            Title = "Frete";
            inicializarComponente();
            Content = new ScrollView{
                Content = new StackLayout
                {
                    Margin = 10,
                    Children = {
                        _Titulo,
                        new StackLayout
                        {
                            Orientation = StackOrientation.Horizontal,
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Spacing = 5,
                            Children = {
                                _Origem,
                                _DivisoriaOrigemDestino,
                                _Destino
                            }
                        },
                        new StackLayout
                        {
                            Orientation = StackOrientation.Horizontal,
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Spacing = 5,
                            Children = {
                                _DataSaida,
                                _DivisoriaData,
                                _DataChegada
                            }
                        }
                    }
                }
            };
            var stckLayout = ((ScrollView)Content).Content;
            foreach(var veiculo in _frete.Veiculos){
                ((StackLayout)stckLayout).Children.Add(getTipoVeiculoView(veiculo));
            }
            ((StackLayout)stckLayout).Children.Add(_Valor);
            ((StackLayout)stckLayout).Children.Add(_Confirmar);
        }

        private StackLayout getTipoVeiculoView(TipoVeiculoInfo veiculo)
        {

            var text = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                FontSize = 20,
                Margin = 5,
                Text = veiculo.Nome
            };

            var img = new Image
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                WidthRequest = 100,
                HeightRequest = 25,
                Aspect = Aspect.AspectFit,
                Margin = 5,
                Source = veiculo.Foto
            };
            return new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 5,
                Children = {
                    img,
                    text
                }
            };
        }

        /*
        private string getImagemCaminhao(int? codCaminhao)
        {
            switch (codCaminhao)
            {
                case 1:
                    return "Veiculo_01.png";
                case 2:
                    return "Veiculo_02.png";
                case 3:
                    return "Veiculo_03.png";
                case 4:
                    return "Veiculo_04.png";
                case 5:
                    return "Veiculo_05.png";
                case 6:
                    return "Veiculo_06.png";
                case 7:
                    return "Veiculo_07.png";
                case 8:
                    return "Veiculo_08.png";
                case 9:
                    return "Veiculo_09.png";
                default:
                    return "Veiculo_01.png";
            }
        }
        */

        private void inicializarComponente()
        {
            
            _Titulo = new Label
            {
                Text = _frete.TituloFreteMotoristaLbl,
                Style = Estilo.Current[Estilo.TITULO1]
            };

            _Origem = new Label
            {
                Text = _frete.EnderecoOrigem,
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };

            _DivisoriaOrigemDestino = new IconImage
            {
                IconSize = 20,
                Margin = new Thickness(10, 0, 10, 0),
                Icon = "fa-arrow-right"
            };

            _Destino = new Label
            {
                Text = _frete.EnderecoDestino,
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };

            _DataSaida = new Label
            {
                Text = _frete.DataRetiradaStr,
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };

            _DivisoriaData = new IconImage
            {
                IconSize = 20,
                Margin = new Thickness(10, 0, 10, 0),
                Icon = "fa-arrow-right"
            };

            _DataChegada = new Label
            {
                Text = _frete.DataEntregaStr,
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };





            _Valor = new Label
            {
                Text = string.Format("Valor do frete: {0:N}", _frete.Preco),
                Style = Estilo.Current[Estilo.LABEL_CONTROL]
            };

            _Confirmar = new Button()
            {
                Text = "Quero esta carga !",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };

            if(_frete.MostraP){
                selecionado = true;
                _Confirmar.Text = "Deixar de pedir carga";
            }


            _Confirmar.Clicked += async (sender, e) =>
            {
                var test = await UserDialogs.Instance.ConfirmAsync((selecionado ? "Você tem certeza que quer deixa a carga ? ": "Você tem certeza que quer esta carga ?"), "Atenção", "Confirmo !", "Voltar");
                if(test)
                {
                    if(!selecionado){
                        UserDialogs.Instance.ShowLoading("Aceitando...");
                        //await FreteFactory.create().aceitar(true, _frete.Id, new MotoristaBLL().pegarAtual().Id);
                        await FreteFactory.create().aceitar(new AceiteEnvioInfo
                        {
                            IdFrete = _frete.Id,
                            IdMotorista = new MotoristaBLL().pegarAtual().Id,
                            Aceite = true
                        });
                        UserDialogs.Instance.HideLoading();
                        _Confirmar.Text = "Deixar de pedir carga";
                        selecionado = true;   
                    } else {
                        UserDialogs.Instance.ShowLoading("Liberando...");
                        _frete.Situacao = FreteSituacaoEnum.ProcurandoMotorista;
                        await FreteFactory.create().alterar(_frete);
                        UserDialogs.Instance.HideLoading();
                        _Confirmar.Text = "Quero esta carga !";
                        selecionado = false;
                    }
                }
            };
        }
    }
}

