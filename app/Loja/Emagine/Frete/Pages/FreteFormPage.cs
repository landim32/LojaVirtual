using System;
using System.Collections.Generic;
using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Endereco.Model;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Login.BLL;
using Emagine.Login.Factory;
using Emagine.Mapa.Controls;
using Emagine.Mapa.Model;
using FormsPlugin.Iconize;
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace Emagine.Frete.Pages
{
    public class FreteFormPage : ContentPage
    {
        private Entry _ObservacaoEntry;
        private StackLayout _TipoVeiculoLayout;
        private Button _AddTipoVeiculoButton;
        //private DropDownList _TipoVeiculoEntry;
        private StackLayout _CarroceriaLayout;
        private Button _AddCarroceriaButton;
        //private DropDownList _CarroceriaEntry;
        private Entry _PesoEntry;
        private Entry _LarguraEntry;
        private Entry _AlturaEntry;
        private Entry _ProfundidadeEntry;
        private Switch _EmBlocoSwitch;

        private StackLayout _LocalRetirada;
        private StackLayout _LocalEntrega;

        private DatePicker _DataRetirada;
        private DatePicker _DataEntrega;
        private TimePicker _HoraRetirada;
        private TimePicker _HoraEntrega;

        private Switch _UsaDataEntrega;
        private StackLayout _ContainerDataEntregaAux;
        private StackLayout _ContainerDataEntregaConteudo;

        private Entry _PrecoEntry;
        private Switch _ValorACombinar;

        private Button _EnviarButton;
        private bool _Edit;

        public FreteFormPage(FreteInfo frete = null)
        {
            Title = "Novo Frete";
            _Edit = frete != null;
            inicializarComponente();
            Frete = frete;
            _ContainerDataEntregaConteudo = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    new IconImage {
                        Icon = "fa-calendar",
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Style = Estilo.Current[Estilo.ICONE_PADRAO]
                    },
                    _DataEntrega,
                    new IconImage {
                        Icon = "fa-clock-o",
                        HorizontalOptions = LayoutOptions.Start,
                        VerticalOptions = LayoutOptions.Center,
                        Style = Estilo.Current[Estilo.ICONE_PADRAO]
                    },
                    _HoraEntrega
                }
            };
            _ContainerDataEntregaAux = new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                Children =
                {
                    _ContainerDataEntregaConteudo
                }
            };
            Content = new ScrollView {
                Orientation = ScrollOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Vertical,
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Padding = new Thickness(3, 3),
                    Children = {
                        _ObservacaoEntry,
                        _TipoVeiculoLayout,
                        _AddTipoVeiculoButton,
                        _CarroceriaLayout,
                        _AddCarroceriaButton,
                        _PesoEntry,
                        new Label {
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Style = Estilo.Current[Estilo.TITULO2],
                            Text = "Informações de carga:"
                        },
                        new StackLayout{
                            Orientation = StackOrientation.Horizontal,
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Spacing = 5,
                            Children = {
                                _EmBlocoSwitch,
                                new Label {
                                    HorizontalOptions = LayoutOptions.Center,
                                    VerticalOptions = LayoutOptions.Start,
                                    Style = Estilo.Current[Estilo.LABEL_SWITCH],
                                    Text = "Carga em blocos, não se aplica"
                                }
                            }
                        },
                        _AlturaEntry,
                        _ProfundidadeEntry,
                        _LarguraEntry,
                        new Label {
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Style = Estilo.Current[Estilo.TITULO1],
                            Text = "Informações de Endereços:"
                        },
                        new Label {
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Style = Estilo.Current[Estilo.TITULO2],
                            Text = "Local de Retirada:"
                        },
                        _LocalRetirada,
                        new Label {
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Style = Estilo.Current[Estilo.TITULO2],
                            Text = "Local de entrega:"
                        },
                        _LocalEntrega,
                        new Label {
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Text = "Data hora da Retirada:",
                            Style = Estilo.Current[Estilo.LABEL_CONTROL]
                        },
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Children = {
                                new IconImage {
                                    Icon = "fa-calendar",
                                    HorizontalOptions = LayoutOptions.Start,
                                    VerticalOptions = LayoutOptions.Center,
                                    Style = Estilo.Current[Estilo.ICONE_PADRAO]
                                },
                                _DataRetirada,
                                new IconImage {
                                    Icon = "fa-clock-o",
                                    HorizontalOptions = LayoutOptions.Start,
                                    VerticalOptions = LayoutOptions.Center,
                                    Style = Estilo.Current[Estilo.ICONE_PADRAO]
                                },
                                _HoraRetirada
                            }
                        },
                        new Label {
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Text = "Data hora máxima para entrega:",
                            Style = Estilo.Current[Estilo.LABEL_CONTROL]
                        },
                        _ContainerDataEntregaAux,
                        new StackLayout{
                            Orientation = StackOrientation.Horizontal,
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Spacing = 5,
                            Children = {
                                _UsaDataEntrega,
                                new Label {
                                    HorizontalOptions = LayoutOptions.Fill,
                                    VerticalOptions = LayoutOptions.Center,
                                    Style = Estilo.Current[Estilo.LABEL_SWITCH],
                                    Text = "Não se aplica"
                                }
                            }
                        },
                        _PrecoEntry,
                        new StackLayout{
                            Orientation = StackOrientation.Horizontal,
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Spacing = 5,
                            Children = {
                                _ValorACombinar,
                                new Label {
                                    HorizontalOptions = LayoutOptions.Fill,
                                    VerticalOptions = LayoutOptions.Center,
                                    Style = Estilo.Current[Estilo.LABEL_SWITCH],
                                    Text = "Valor à combinar"
                                }
                            }
                        },
                        _EnviarButton
                    }
                }
            };
        }

        public FreteInfo Frete {
            get {
                var regraUsuario = UsuarioFactory.create();
                var usuario = regraUsuario.pegarAtual();
                var frete = new FreteInfo
                {
                    Observacao = _ObservacaoEntry.Text,
                    Situacao = FreteSituacaoEnum.ProcurandoMotorista,
                    IdUsuario = usuario.Id
                };
                foreach (var obj in _TipoVeiculoLayout.Children) {
                    if (obj is DropDownList && ((DropDownList)obj).Value is TipoVeiculoInfo) {
                        var tipo = (TipoVeiculoInfo)((DropDownList)obj).Value;
                        frete.Veiculos.Add(tipo);
                    }
                }
                foreach (var obj in _CarroceriaLayout.Children)
                {
                    if (obj is DropDownList && ((DropDownList)obj).Value is TipoCarroceriaInfo)
                    {
                        var carroceria = (TipoCarroceriaInfo)((DropDownList)obj).Value;
                        frete.Carrocerias.Add(carroceria);
                    }
                }
                double peso = 0;
                if (double.TryParse(_PesoEntry.Text, out peso))
                {
                    frete.Peso = peso;
                }
                if (!_EmBlocoSwitch.IsToggled)
                {
                    double altura = 0;
                    if (double.TryParse(_AlturaEntry.Text, out altura))
                    {
                        frete.Altura = altura;
                    }
                    double largura = 0;
                    if (double.TryParse(_LarguraEntry.Text, out largura))
                    {
                        frete.Largura = largura;
                    }
                    double profundidade = 0;
                    if (double.TryParse(_ProfundidadeEntry.Text, out profundidade))
                    {
                        frete.Profundidade = profundidade;
                    }
                }


                if (frete.DataRetirada == null) {
                    frete.DataRetirada = new DateTime(_DataRetirada.Date.Year, _DataRetirada.Date.Month, _DataRetirada.Date.Day, 0, 0, 0, DateTimeKind.Unspecified);
                    frete.DataRetirada = ((DateTime)frete.DataRetirada).AddHours(_HoraRetirada.Time.Hours);
                    frete.DataRetirada = ((DateTime)frete.DataRetirada).AddMinutes(_HoraRetirada.Time.Minutes);
                }

                if (frete.DataEntrega == null)
                {
                    if (!_UsaDataEntrega.IsToggled)
                    {
                        frete.DataEntrega = new DateTime(_DataEntrega.Date.Year, _DataEntrega.Date.Month, _DataEntrega.Date.Day, 0, 0, 0, DateTimeKind.Unspecified);
                        frete.DataEntrega = ((DateTime)frete.DataEntrega).AddHours(_HoraEntrega.Time.Hours);
                        frete.DataEntrega = ((DateTime)frete.DataEntrega).AddMinutes(_HoraEntrega.Time.Minutes);
                    }
                    else
                        frete.DataEntrega = frete.DataRetirada;
                }

                if (!_ValorACombinar.IsToggled) {
                    double preco = 0;
                    if (double.TryParse(_PrecoEntry.Text, out preco))
                    {
                        frete.Preco = preco;
                    }
                }

                int i = 0;
                foreach (var obj in _LocalEntrega.Children) {
                    var controle = (DropDownList)obj;
                    var local = (EnderecoInfo)controle.Value;
                    if (local != null) {
                        frete.Locais.Add(new FreteLocalInfo
                        {
                            Cep = local.Cep,
                            Uf = local.Uf,
                            Cidade = local.Cidade,
                            Bairro = local.Bairro,
                            Numero = local.Numero,
                            Latitude = local.Latitude??0,
                            Longitude = local.Longitude??0,
                            Logradouro = local.Logradouro,
                            Tipo = frete.TemSaida() ? FreteLocalTipoEnum.Parada : FreteLocalTipoEnum.Origem,
                            Ordem = i
                        });
                        i++;
                    }
                }
                foreach (var obj in _LocalRetirada.Children)
                {
                    var controle = (DropDownList)obj;
                    var local = (EnderecoInfo)controle.Value;
                    if (local != null)
                    {
                        frete.Locais.Add(new FreteLocalInfo
                        {
                            Cep = local.Cep,
                            Uf = local.Uf,
                            Cidade = local.Cidade,
                            Bairro = local.Bairro,
                            Numero = local.Numero,
                            Latitude = local.Latitude ?? 0,
                            Longitude = local.Longitude ?? 0,
                            Logradouro = local.Logradouro,
                            Tipo = frete.TemDestino() ? FreteLocalTipoEnum.Parada : FreteLocalTipoEnum.Destino,
                            Ordem = i
                        });
                        i++;
                    }
                }
                return frete;
            }
            set {
                if (value == null)
                    return;

                _ObservacaoEntry.Text = value.Observacao;
                _PesoEntry.Text = value.Peso.ToString();
                _AlturaEntry.Text = value.Altura.ToString();
                _LarguraEntry.Text = value.Largura.ToString();
                _ProfundidadeEntry.Text = value.Profundidade.ToString();
                _PrecoEntry.Text = value.Preco.ToString();
                if (value.DataRetirada != null) {
                    _DataRetirada.Date = (DateTime)value.DataRetirada;
                    _DataRetirada.Date.AddHours(((DateTime)value.DataRetirada).Hour * -1);
                    _DataRetirada.Date.AddMinutes(((DateTime)value.DataRetirada).Minute * -1);
                    _HoraRetirada.Time = TimeSpan.Parse(((DateTime)value.DataRetirada).Hour + ":" + ((DateTime)value.DataRetirada).Minute);
                }

                if (value.DataEntrega != null)
                {
                    _DataEntrega.Date = (DateTime)value.DataEntrega;
                    _DataEntrega.Date.AddHours(((DateTime)value.DataEntrega).Hour * -1);
                    _DataEntrega.Date.AddMinutes(((DateTime)value.DataEntrega).Minute * -1);
                    _HoraEntrega.Time = TimeSpan.Parse(((DateTime)value.DataEntrega).Hour + ":" + ((DateTime)value.DataEntrega).Minute);
                }
                _TipoVeiculoLayout.Children.Clear();
                foreach (var tipo in value.Veiculos) {
                    _TipoVeiculoLayout.Children.Add(criarTipoVeiculoEntry(tipo));
                }
                _TipoVeiculoLayout.Children.Add(criarTipoVeiculoEntry());
                _CarroceriaLayout.Children.Clear();
                foreach (var carroceria in value.Carrocerias)
                {
                    _CarroceriaLayout.Children.Add(criarCarroceriaEntry(carroceria));
                }
                _CarroceriaLayout.Children.Add(criarCarroceriaEntry());

                if (value.Locais != null) {
                    foreach (var local in value.Locais)
                    {
                        if (local.Tipo == FreteLocalTipoEnum.Origem) {
                            var vw = getEnderecoRetirada();
                            vw.Value = new EnderecoInfo
                            {
                                Cep = local.Cep,
                                Uf = local.Uf,
                                Cidade = local.Cidade,
                                Bairro = local.Bairro,
                                Numero = local.Numero,
                                Latitude = local.Latitude,
                                Longitude = local.Longitude,
                                Logradouro = local.Logradouro
                            };
                            _LocalRetirada.Children.Add(vw);

                        } else if (local.Tipo == FreteLocalTipoEnum.Destino) {
                            var vw = getEnderecoEntrega();
                            vw.Value = new EnderecoInfo
                            {
                                Cep = local.Cep,
                                Uf = local.Uf,
                                Cidade = local.Cidade,
                                Bairro = local.Bairro,
                                Numero = local.Numero,
                                Latitude = local.Latitude,
                                Longitude = local.Longitude,
                                Logradouro = local.Logradouro
                            };
                            _LocalEntrega.Children.Add(vw);
                        }


                    }
                }

            }
        }

        private DropDownList criarTipoVeiculoEntry(TipoVeiculoInfo tipo = null) {
            var tipoVeiculoEntry = new DropDownList
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                //Margin = 5,
                Placeholder = "Tipo de Veículo",
                TextColor = Color.Black,
                PlaceholderColor = Color.Silver,
                Value = tipo
            };
            tipoVeiculoEntry.Clicked += (sender, e) =>
            {
                var tipoVeiculoPage = new TipoVeiculoSelecionaPage();
                tipoVeiculoPage.AoSelecionar += (object s2, TipoVeiculoInfo e2) =>
                {
                    ((DropDownList) sender).Value = e2;
                };
                Navigation.PushAsync(tipoVeiculoPage);
            };
            return tipoVeiculoEntry;
        }

        private DropDownList criarCarroceriaEntry(TipoCarroceriaInfo carroceria = null) {
            var carroceriaEntry = new DropDownList
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                //Margin = 5,
                Placeholder = "Tipo de Carroceria",
                TextColor = Color.Black,
                PlaceholderColor = Color.Silver,
                Value = carroceria
            };
            carroceriaEntry.Clicked += (sender, e) =>
            {
                var carroceriaPage = new CarroceriaSelecionaPage();
                carroceriaPage.AoSelecionar += (object s2, TipoCarroceriaInfo e2) =>
                {
                    ((DropDownList)sender).Value = e2;
                };
                Navigation.PushAsync(carroceriaPage);
            };
            return carroceriaEntry;
        }

        private DropDownList getEnderecoRetirada()
        {
            var ret = new DropDownList
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
            };
            ret.Clicked += (sender, e) => {
                var endrVw = new LocalRetiradaListaPage();
                endrVw.Selected += (sender2, e2) =>
                {
                    ret.Value = e2;
                };
                Navigation.PushAsync(endrVw);
            };
            return ret;
        }

        private DropDownList getEnderecoEntrega()
        {
            var ret = new DropDownList
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start
            };
            ret.Clicked += (sender, e) => {
                var endrVw = new EnderecoEntregaPage((EnderecoInfo)ret.Value);
                endrVw.Finished += (sender2, e2) =>
                {
                    ret.Value = e2;
                };
                Navigation.PushAsync(endrVw);
            };
            return ret;
        }

        private void inicializarComponente() {
            _ObservacaoEntry = new Entry {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Placeholder = "Qual a carga?"
            };
            _TipoVeiculoLayout = new StackLayout {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    criarTipoVeiculoEntry()
                }
            };
            _AddTipoVeiculoButton = new Button()
            {
                Text = "Adicionar outro tipo de veículo",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                FontSize = 12
            };
            _AddTipoVeiculoButton.Clicked += (sender, e) => {
                _TipoVeiculoLayout.Children.Add(criarTipoVeiculoEntry());
            };

            _CarroceriaLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    criarCarroceriaEntry()
                }
            };

            _AddCarroceriaButton = new Button()
            {
                Text = "Adicionar outro tipo de carroceria",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                FontSize = 12
            };
            _AddCarroceriaButton.Clicked += (sender, e) => {
                _CarroceriaLayout.Children.Add(criarCarroceriaEntry());
            };

            _PesoEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                Placeholder = "Peso em Toneladas"
            };

            _EmBlocoSwitch = new Switch {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                IsToggled = false
            };

            _EmBlocoSwitch.Toggled += (object sender, ToggledEventArgs e) => {
                if(e.Value)
                {
                    _AlturaEntry.IsEnabled = false;
                    _LarguraEntry.IsEnabled = false;
                    _ProfundidadeEntry.IsEnabled = false;
                } 
                else
                {
                    _AlturaEntry.IsEnabled = true;
                    _LarguraEntry.IsEnabled = true;
                    _ProfundidadeEntry.IsEnabled = true;
                }
            };

            _LarguraEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                Placeholder = "Largura total (m)"
            };

            _AlturaEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                Placeholder = "Altura total (m)"
            };

            _ProfundidadeEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                Placeholder = "Comprimento total (m)"
            };

            _LocalRetirada = new StackLayout {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };
            if(!_Edit){
                _LocalRetirada.Children.Add(getEnderecoRetirada());
            }

            /*_AdicionarRetirardaButton = new Button
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PADRAO],
                Text = "Adicionar mais um endereço"
            };
            _AdicionarRetirardaButton.Clicked += (sender, e) => {
                _LocalRetirada.Children.Add(getEnderecoRetirada());
            };*/

            _LocalEntrega = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };
            if (!_Edit)
            {
                _LocalEntrega.Children.Add(getEnderecoEntrega());
            }

            /*_AdicionarEntregaButton = new Button
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PADRAO],
                Text = "Adicionar mais um endereço"
            };
            _AdicionarEntregaButton.Clicked += (sender, e) => {
                _LocalEntrega.Children.Add(getEnderecoEntrega());
            };*/

            _DataRetirada = new DatePicker
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_DATA]
            };
            _HoraRetirada = new TimePicker
            {
                WidthRequest = 100,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_TEMPO]
            };

            _DataEntrega = new DatePicker
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_DATA]
            };
            _HoraEntrega = new TimePicker
            {
                WidthRequest = 100,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_TEMPO]
            };

            _UsaDataEntrega = new Switch
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                IsToggled = false
            };
            _UsaDataEntrega.Toggled += (sender, e) => {
                if (e.Value)
                {
                    _ContainerDataEntregaAux.Children.Remove(_ContainerDataEntregaConteudo);
                }
                else
                {
                    _ContainerDataEntregaAux.Children.Add(_ContainerDataEntregaConteudo);
                }
            };

            _PrecoEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Keyboard = Keyboard.Numeric,
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                Placeholder = "Valor do Frete"
            };

            _ValorACombinar = new Switch
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                IsToggled = false
            };

            _ValorACombinar.Toggled += (object sender, ToggledEventArgs e) => {
                if (e.Value){
                    _PrecoEntry.IsEnabled = false;
                }
                else{
                    _PrecoEntry.IsEnabled = true;
                }
                   
            };

            _EnviarButton = new Button
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = !_Edit ? "SALVAR" : "ATUALIZAR"
            };
            _EnviarButton.Clicked += async (sender, e) => {
                var regraEntrega = FreteFactory.create();
                UserDialogs.Instance.ShowLoading("Enviando...");
                try
                {
                    if(!_Edit)
                    {
                        var id_frete = await regraEntrega.inserir(Frete);    
                        UserDialogs.Instance.HideLoading();
                        await DisplayAlert("Aviso", "Frete cadastro com sucesso.", "Fechar");
                    } else {
                        await regraEntrega.alterar(Frete);
                        UserDialogs.Instance.HideLoading();
                        await DisplayAlert("Aviso", "Frete atualizado com sucesso.", "Fechar");
                    }


                    await Navigation.PopAsync();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.ShowError(erro.Message, 8000);
                }
            };
        }

        /*
        private void continuaPedido(){
            if(validaPedido()){
                var infoPedido = new FreteInfo()
                {
                    Altura = double.Parse(_AlturaEntry.Text),
                    Largura = double.Parse(_LarguraEntry.Text),
                    Peso = double.Parse(_PesoEntry.Text),
                    Profundidade = double.Parse(_ProfundidadeEntry.Text),
                    CodigoVeiculo = TipoVeiculoEnum.Caminhao,
                    IdUsuario = new UsuarioBLL().pegarAtual().Id
                };
                Navigation.PushAsync(new FreteForm2Page(infoPedido));
            } else {
                DisplayAlert("Atenção", "Dados inválidos, verifique todas as entradas.", "Entendi");
            }
        }
        */

        /*
        private bool validaPedido(){
            double dblAux;
            if (_AlturaEntry.Text == String.Empty || !double.TryParse(_AlturaEntry.Text, out dblAux))
                return false;
            if (_LarguraEntry.Text == String.Empty || !double.TryParse(_LarguraEntry.Text, out dblAux))
                return false;
            if (_PesoEntry.Text == String.Empty || !double.TryParse(_PesoEntry.Text, out dblAux))
                return false;
            if (_ProfundidadeEntry.Text == String.Empty || !double.TryParse(_ProfundidadeEntry.Text, out dblAux))
                return false;
            if (_Tipo != null && _Tipo.Info == null)
            {
                return false;
            }
            if (_Carroceria != null && _Carroceria.Info == null)
            {
                return false;
            }
            return true;
        }
        */
    }
     
}

