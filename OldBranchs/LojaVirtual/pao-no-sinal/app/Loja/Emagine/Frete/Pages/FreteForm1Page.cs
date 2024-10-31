using System;
using System.Collections.Generic;
using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Frete.Model;
using Emagine.Login.BLL;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class FreteForm1Page : ContentPage
    {

        private SelecionarEntry _Tipo;
        private TipoVeiculoSelecionaPage _TipoVeiculo;
        private SelecionarEntry _Carroceria;
        private CarroceriaSelecionaPage _TipoCarroceria;

        private Button _EnviarButton;
        private Label _ObservacaoLbl;

        private Entry _PesoEntry;
        private Entry _LarguraEntry;
        private Entry _AlturaEntry;
        private Entry _ProfundidadeEntry;

        public FreteForm1Page()
        {
            Title = "Escolha o transporte";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    _Tipo,
                    _Carroceria,
                    gerarPainelProduto(),
                    _EnviarButton
                }
            };
        }

        private void inicializarComponente() {
            _Tipo = new SelecionarEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = 5
            };
            _Tipo.setHintText("Tipo de Veículo");
            _TipoVeiculo = new TipoVeiculoSelecionaPage();
            _TipoVeiculo.AoSelecionar += (sender, e) => {
                _Tipo.Info = e;
            };
            _Tipo.Clicked += (sender, e) =>
            {
                Navigation.PushAsync(_TipoVeiculo);
            };


            _Carroceria = new SelecionarEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = 5
            };
            _Carroceria.setHintText("Tipo de Carroceria");
            _TipoCarroceria = new CarroceriaSelecionaPage();
            _TipoCarroceria.AoSelecionar += (sender, e) => {
                _Carroceria.Info = e;
            };
            _Carroceria.Clicked += (sender, e) =>
            {
                Navigation.PushAsync(_TipoCarroceria);
            };

            _PesoEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                //WidthRequest = 120,
                //HeightRequest = 30,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 14,
                Keyboard = Keyboard.Numeric,
                HorizontalTextAlignment = TextAlignment.End,
                //Text = "80"
            };

            _LarguraEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 14,
                Keyboard = Keyboard.Numeric,
                HorizontalTextAlignment = TextAlignment.End,
                //Text = "40"
            };

            _AlturaEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 14,
                Keyboard = Keyboard.Numeric,
                HorizontalTextAlignment = TextAlignment.End,
                //Text = "60"
            };

            _ProfundidadeEntry = new Entry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 14,
                Keyboard = Keyboard.Numeric,
                HorizontalTextAlignment = TextAlignment.End,
                //Text = "10"
            };



            _EnviarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Continuar"
            };
            _EnviarButton.Clicked += (sender, e) => {
                continuaPedido();
            };
        }

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

        private Grid gerarPainelProduto()
        {
            var grid = new Grid {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(10, 5),
                Padding = 5,
                RowSpacing = 0,
                BackgroundColor = Estilo.Current.PrimaryColor,
            };
            grid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(4, GridUnitType.Star) });
            grid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(3, GridUnitType.Absolute) });
            grid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(2, GridUnitType.Star) });
            grid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(23, GridUnitType.Absolute) });
            grid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(3, GridUnitType.Absolute) });
            grid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });

            var labelProduto = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                VerticalTextAlignment = TextAlignment.Center,
                Text = "Medidas do Produto",
                TextColor = Color.White,
                FontSize = 16
            };
            grid.Children.Add(labelProduto, 0, 0);
            Grid.SetColumnSpan(labelProduto, 3);

            var linhaHorizontal = new BoxView
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                BackgroundColor = Color.White,
                HeightRequest = 1
            };
            grid.Children.Add(linhaHorizontal, 0, 1);
            Grid.SetColumnSpan(linhaHorizontal, 3);

            var labelPeso = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Center,
                Text = "Peso(ton)",
                TextColor = Color.White,
                FontSize = 14
            };
            var linhaVertical = new BoxView
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Fill,
                BackgroundColor = Color.White,
                WidthRequest = 1//,
                //HeightRequest = 70
            };
            grid.Children.Add(labelPeso, 0, 2);
            grid.Children.Add(linhaVertical, 1, 2);
            grid.Children.Add(_PesoEntry, 2, 2);
            Grid.SetRowSpan(linhaVertical, 4);

            var labelLargura = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Text = "Largura(cm)",
                TextColor = Color.White,
                FontSize = 14
            };
            grid.Children.Add(labelLargura, 0, 3);
            grid.Children.Add(_LarguraEntry, 2, 3);

            var labelAltura = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Text = "Altura(cm)",
                TextColor = Color.White,
                FontSize = 14
            };
            grid.Children.Add(labelAltura, 0, 4);
            grid.Children.Add(_AlturaEntry, 2, 4);

            var labelProfundidade = new Label
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Text = "Comprimento(cm)",
                TextColor = Color.White,
                FontSize = 14
            };
            grid.Children.Add(labelProfundidade, 0, 5);
            grid.Children.Add(_ProfundidadeEntry, 2, 5);
            return grid;
        }

    }
     
}

